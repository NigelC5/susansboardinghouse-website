<?php include 'db_connect.php'; ?>



<style>
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    tr, td, th {
        border: 1px solid black;
        font-size: 17px;
        word-wrap: break-word;
    }
    th {
        vertical-align: middle !important;
        text-align: center;
    }
    .card {
        width: 100%;
        overflow-x: auto;
        font-size: 18px;
    }
</style>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card" style="margin-top: 50px;">
            <div class="card-header"><b>Outstanding Balance</b></div>
            <div class="card-body">
                <div class="col-md-12">
                    <div class="row">
                    </div>
                    <div class="row">
                        <table class="table table-condensed table-bordered table-hover" id="tenant_table">
                            <thead>
                                <tr>
                                    <th class='text-center'>#</th>
                                    <th class='text-center'>Tenant</th>
                                    <th class='text-center'>Room #</th>
                                    <th class='text-center'>Rental Balance</th>
                                    <th class='text-center'>Appliance Balance</th>
                                    <th class='text-center'>Total Balance</th>
                                    <th class='text-center'>Last Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$i = 1;
// Query to fetch tenant data
$tenants = $conn->query("SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, r.room_no, r.price FROM tenants t INNER JOIN room r ON r.id = t.room_id WHERE t.status = 1 ORDER BY r.room_no DESC");

if ($tenants->num_rows > 0):
    while ($row = $tenants->fetch_assoc()):
        // Query to fetch outstanding balance from the outstanding_balance table
        $outstanding_balance_query = $conn->query("SELECT rental_balance, appliance_balance FROM outstanding_balance WHERE tenant_id = " . $row['id']);
        $outstanding_balance = $outstanding_balance_query->num_rows > 0 ? $outstanding_balance_query->fetch_assoc() : null;

        // Fetch rental and appliance balances as absolute values
        $rental_balance = $outstanding_balance ? abs($outstanding_balance['rental_balance']) : $row['price']; // Use room price if no balance found
        $appliance_balance = $outstanding_balance ? abs($outstanding_balance['appliance_balance']) : 0;

        // Query to fetch rent paid
        $paid_query = $conn->query("SELECT SUM(rent_payment) AS paid FROM payments WHERE tenant_id = " . $row['id']);
        $paid = $paid_query->num_rows > 0 ? $paid_query->fetch_assoc()['paid'] : 0;

        // Adjust rental balance by subtracting the amount paid
        $rental_balance = max(0, $rental_balance - $paid); // Ensure rental balance does not go below 0

        // Calculate total balance
        $total_balance = $rental_balance + $appliance_balance;

        // Query to fetch last payment date
        $last_payment_query = $conn->query("SELECT * FROM payments WHERE tenant_id = " . $row['id'] . " ORDER BY unix_timestamp(date_created) DESC LIMIT 1");
        $last_payment = $last_payment_query->num_rows > 0 ? date("M d, Y", strtotime($last_payment_query->fetch_assoc()['date_created'])) : 'N/A';

        // Update outstanding_balance table with the total balance
        $update_balance_query = $conn->query("UPDATE outstanding_balance SET rental_balance = $rental_balance, appliance_balance = $appliance_balance, total_balance = $total_balance WHERE tenant_id = " . $row['id']);
        if ($update_balance_query === false) {
            // Handle error, log or display an error message if necessary
        } else if ($conn->affected_rows === 0) {
            // If no existing record was updated, insert a new record
            $insert_balance_query = $conn->query("INSERT INTO outstanding_balance (tenant_id, rental_balance, appliance_balance, total_balance) VALUES (" . $row['id'] . ", $rental_balance, $appliance_balance, $total_balance)");
            if ($insert_balance_query === false) {
                // Handle error, log or display an error message if necessary
            }
        }

        // Display tenant data in the HTML table
        echo "<tr>
        <td>{$i}</td>
        <td>" . ucwords($row['name']) . "</td>
        <td>{$row['room_no']}</td>
        <td class='text-center'>" . number_format($rental_balance, 2) . "</td>
        <td class='text-center'>" . number_format($appliance_balance, 2) . "</td>
        <td class='text-center'><b>" . number_format($total_balance, 2) . "</b></td>
        <td class='text-center'>" . ($last_payment ? $last_payment : 'N/A') . "</td>
        </tr>";
        $i++;
    endwhile;
else:
    echo "<tr><th colspan='7'><center>No Data.</center></th></tr>";
endif;
?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Filter report
    $('#filter-report').submit(function(e) {
        e.preventDefault();
        location.href = 'index.php?page=balance_report&' + $(this).serialize();
    });
    $(document).ready(function() {
        $('#tenant_table').dataTable();
    });
</script>
