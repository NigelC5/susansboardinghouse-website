<?php
// Include database connection
include 'db_connect.php';

// Fetch tenant data
$tenant_id = intval($_GET['id']); // Ensure tenant ID is an integer
$tenants = $conn->query("
    SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, 
           r.room_no, r.price, t.date_in, t.total_items 
    FROM tenants t 
    INNER JOIN room r ON r.id = t.room_id 
    WHERE t.id = {$tenant_id}
");

if ($tenants->num_rows > 0) {
    $tenant_data = $tenants->fetch_assoc();
    foreach ($tenant_data as $k => $v) {
        if (!is_numeric($k)) {
            $$k = $v;
        }
    }

    // Calculate the number of months since the tenant moved in
    $date_in = new DateTime($date_in);
    $now = new DateTime();
    $interval = $date_in->diff($now);
    $months = $interval->y * 12 + $interval->m;

    // Calculate payable amount (price * months)
    $payable_amount = floatval($price) * $months;

    // Fetch the total amount paid
    $paid_query = $conn->query("
        SELECT SUM(rent_payment) AS paid 
        FROM payments 
        WHERE tenant_id = {$tenant_id}
    ");
    $paid = $paid_query->num_rows > 0 ? floatval($paid_query->fetch_assoc()['paid']) : 0;

    // Calculate rental balance
    $rental_balance = max(0, floatval($payable_amount) - floatval($paid));

    // Calculate appliance fee
    $appliance_fee = intval($total_items) * 150;

    // Fetch the total appliance payment
    $appliance_payment_query = $conn->query("
        SELECT SUM(appliance_payment) AS appliance_payment 
        FROM payments 
        WHERE tenant_id = {$tenant_id}
    ");
    $appliance_payment = $appliance_payment_query->num_rows > 0 ? floatval($appliance_payment_query->fetch_assoc()['appliance_payment']) : 0;

    // Calculate appliance balance
    $appliance_balance = max(0, floatval($appliance_fee) - floatval($appliance_payment));

    // Insert or update outstanding balance in the `outstanding_balance` table
    $check_balance = $conn->query("
        SELECT * FROM outstanding_balance 
        WHERE tenant_id = {$tenant_id}
    ");
    if ($check_balance->num_rows > 0) {
        // Update existing record
        $update_balance = $conn->query("
            UPDATE outstanding_balance 
            SET rental_balance = {$rental_balance}, appliance_balance = {$appliance_balance} 
            WHERE tenant_id = {$tenant_id}
        ");
    } else {
        // Insert new record
        $insert_balance = $conn->query("
            INSERT INTO outstanding_balance (tenant_id, rental_balance, appliance_balance) 
            VALUES ({$tenant_id}, {$rental_balance}, {$appliance_balance})
        ");
    }

    // Debugging output
    echo "<!-- Debug Info: rental_balance = {$rental_balance}, paid = {$paid}, payable_amount = {$payable_amount}, months = {$months}, appliance_fee = {$appliance_fee}, appliance_payment = {$appliance_payment}, appliance_balance = {$appliance_balance} -->";
} else {
    echo "<!-- Tenant not found. -->";
}
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-4">
                <div id="details">
                    <large><b>Details</b></large>
                    <hr>
                    <p>Tenant: <b><?php echo ucwords($name) ?></b></p>
                    <p>Room no: <b><?php echo ucwords($room_no) ?></b></p><hr>

                    <p><b>Rent Payment</b></p>
                    <p>Monthly Rental Rate: <b><?php echo number_format($price, 2) ?></b></p>
                    <p>Rental Paid: <b><?php echo number_format($paid, 2) ?></b></p>
                    <p>Rental Balance: <b><?php echo number_format($rental_balance, 2) ?></b></p>
                    <p>Rent Started: <b><?php echo $date_in->format("M d, Y") ?></b></p>
                    <p>Payable Months: <b><?php echo $months ?></b></p>
                    <p>Payable Rent Amount: <b><?php echo number_format($payable_amount, 2) ?></b></p><hr>
                    <p><b>Appliance Payment</b></p>
                    <p>Appliance Fee: <b><?php echo number_format($appliance_fee, 2) ?></b></p>
                    <p>Appliance Paid: <b><?php echo number_format($appliance_payment, 2) ?></b></p>
                    <p>Appliance Balance: <b><?php echo number_format($appliance_balance, 2) ?></b></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #details p {
        margin: unset;
        padding: unset;
        line-height: 1.3em;
    }
    td, th {
        padding: 3px !important;
    }
</style>
