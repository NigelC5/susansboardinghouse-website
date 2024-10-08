<?php
include 'db_connect.php';

$next_due_date = date('Y-m-d', strtotime('+6 days'));

// Query to fetch tenants with due dates 7 days from now
$sql = "SELECT lastname, firstname, middlename, room_id, date_in, due_day, email, email_sent,
               LAST_DAY(date_in) AS last_day_of_month,
               DATE_ADD(LAST_DAY(date_in), INTERVAL due_day DAY) AS due_date
        FROM tenants
        HAVING due_date = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $next_due_date);
$stmt->execute();
$result = $stmt->get_result();

// Start rendering the table structure
echo "<div style='margin: 20px;'>";
echo "<div style='border: 1px solid #dddddd; border-radius: 5px; padding: 20px; background-color: #ffffff;'>";
echo "<h3 style='margin-bottom: 20px; font-family: arial; text-align: center;'>Tenants with Upcoming Due Dates<br>7 Days from Now</h3>";
echo "<table style='border-collapse: collapse; width: 100%;'>";
echo "<tr>
        <th style='border: 1px solid #dddddd; text-align: left; padding: 8px; background-color: #f2f2f2;'>Room ID</th>
        <th style='border: 1px solid #dddddd; text-align: left; padding: 8px; background-color: #f2f2f2;'>Name</th>
        <th style='border: 1px solid #dddddd; text-align: left; padding: 8px; background-color: #f2f2f2;'>Email</th>
        <th style='border: 1px solid #dddddd; text-align: left; padding: 8px; background-color: #f2f2f2;'>Date In</th>
        <th style='border: 1px solid #dddddd; text-align: left; padding: 8px; background-color: #f2f2f2;'>Due Day</th>
        <th style='border: 1px solid #dddddd; text-align: left; padding: 8px; background-color: #f2f2f2;'>Due Date</th>
        <th style='border: 1px solid #dddddd; text-align: left; padding: 8px; background-color: #f2f2f2;'>Email Sent</th>
      </tr>";

// Check if any results were returned
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='border: 1px solid #dddddd; text-align: center; padding: 8px;'>".$row["room_id"]."</td>";
        echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>".$row["lastname"].", ".$row["firstname"]." ".$row["middlename"]."</td>";
        echo "<td style='border: 1px solid #dddddd; text-align: center; padding: 8px;'>".$row["email"]."</td>";
        echo "<td style='border: 1px solid #dddddd; text-align: center; padding: 8px;'>".$row["date_in"]."</td>";
        echo "<td style='border: 1px solid #dddddd; text-align: center; padding: 8px;'>".$row["due_day"]."</td>";
        $due_date = date('M-d-Y', strtotime($row["due_date"]));
        echo "<td style='border: 1px solid #dddddd; text-align: center; padding: 8px;'>".$due_date."</td>";
        $email_sent = $row["email_sent"] ? "Sent" : "Not Sent";
        echo "<td style='border: 1px solid #dddddd; text-align: center; padding: 8px;'>".$email_sent."</td>";
        echo "</tr>";
    }
} else {
    // Display message when no tenants are found
    echo "<tr><td colspan='7' style='border: 1px solid #dddddd; text-align: center; padding: 8px;'>No tenants have due dates 7 days from now.</td></tr>";
}

// Close the table and divs
echo "</table>";
echo "</div>";
echo "</div>";

$conn->close();
?>
