<!-- sidebar.php -->
<div class="navsidebar">
    <div class="navsidebar-header text-center">
        <h3>Rooms</h3>
    </div>
    <ul class="nav flex-column">
        <?php
        include('db_connect.php');

        // Fetch room numbers in ascending order
        $sql = "SELECT room_no FROM room ORDER BY room_no ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li class="nav-item">';
                echo '<div class="navsidebar-item" onclick="selectRoom(this)">Room ' . $row['room_no'] . '</div>';
                echo '</li>';
            }
        } else {
            echo "0 results";
        }

        // Close database connection
        $conn->close();
        ?>
    </ul>
</div>
