<?php
// Include database connection
include('db_connect.php');

// Check if room_no is set in the request
if (isset($_GET['room_no'])) {
    // Validate room_no (ensure it's a valid integer)
    $room_no = intval($_GET['room_no']);

    if ($room_no > 0) {
        // Prepare SQL statement with a parameterized query
        $sql = "SELECT t.lastname, t.firstname, t.middlename, t.date_in, r.id AS room_id
        FROM tenants t 
        INNER JOIN room r ON t.room_id = r.id 
        WHERE r.room_no = ? AND t.status = 1";

        
        // Prepare and bind the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $room_no);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();

      // Check if any rows were returned
if ($result->num_rows > 0) {
    // Initialize counter variable
    $counter = 1;
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr data-roomId='" . $row['room_id'] . "' data-roomNo='" . $room_no . "'>";
        echo "<td class='text-center'>" . $counter . "</td>";
        echo "<td class='text-center'>" . $row['lastname'] . ", " . $row['firstname'] . " " . $row['middlename'] . "</td>";
        // Format date to month-day-year format
        $formatted_date = date("M-d-Y", strtotime($row['date_in']));
        echo "<td class='text-center'>" . $formatted_date . "</td>";
        echo "</tr>";
        // Increment counter
        $counter++;
    }
}
else {
            echo "<tr><td colspan='3' class='text-center'>No tenants found for this room.</td></tr>";
        }
        
        // Close statement
        $stmt->close();
    } else {
        echo "<tr><td colspan='3' class='text-center'>Invalid room number.</td></tr>";
    }
} else {
    echo "<tr><td colspan='3' class='text-center'>Room number not provided.</td></tr>";
}

// Close database connection
$conn->close();
?>
