<?php
// Receive data from JavaScript
$date_in = $_POST['date_in'];
$rent_payment = $_POST['rent_payment'];
$due_day = $_POST['due_day'];

// Execute Python script to calculate dates
$command = escapeshellcmd("python3 calculate_dates.py \"$date_in\" $rent_payment $due_day");
$output = shell_exec($command);

// Return the result to JavaScript
echo $output;
?>
