<?php
include 'db_connect.php';

if (isset($_GET['room_id']) && isset($_GET['month_of'])) {
    $room_id = $_GET['room_id'];
    $month_of = $_GET['month_of'];

    $tenants = $conn->query("SELECT t.last_name, t.first_name, p.rent_payment, p.appliance_payment, p.total_amount, p.date_created
                            FROM tenants t
                            JOIN payments p ON t.id = p.tenant_id
                            WHERE t.room_id = $room_id AND DATE_FORMAT(p.date_created, '%Y-%m') = '$month_of'");

    if ($tenants->num_rows > 0) {
        $i = 1;
        while ($row = $tenants->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$i}</td>";
            echo "<td>{$row['last_name']}</td>";
            echo "<td>{$row['first_name']}</td>";
            echo "<td class='text-right'>" . number_format($row['rent_payment'], 2) . "</td>";
            echo "<td class='text-right'>" . number_format($row['appliance_payment'], 2) . "</td>";
            echo "<td class='text-right'>" . number_format($row['total_amount'], 2) . "</td>";
            echo "<td>{$row['date_created']}</td>";
            echo "</tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='7'><center>No Data.</center></td></tr>";
    }
}
?>
