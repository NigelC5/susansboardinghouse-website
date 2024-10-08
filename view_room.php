<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rooms Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    /* Custom CSS for sidebar */
    .navsidebar {
        background-color: grey;
        color: #fff;
        width: 100%;
        margin-left: 100px;
    }

    .navsidebar-header {
        padding: 20px;
        background-color: dimgray;
    }

    .navsidebar-item {
        padding: 10px 20px;
        color: #fff;
        cursor: pointer;
    }

    .navsidebar-item:hover, .navsidebar-item.selected {
        background-color: #555;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    tr,
    td,
    th {
        border: 1px solid black;
        font-size: 17px;
        word-wrap: break-word;
        padding: 10px;
    }

    th {
        vertical-align: middle !important;
        text-align: center;
    }

    .card {
        width: 90%;
        overflow-x: auto;
        font-size: 18px;
        margin-left: 180px;
    }

    .back-button {
        margin-bottom: 20px;
        margin-left: 180px;
    }

    .room-display {
        font-family: 'Arial', sans-serif;
        font-size: 34px;
        text-align: center;
        margin-left: 250px;
        margin-bottom: 10px;
    }
  </style>
</head>
<body>
<div class="container-fluid" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-2 p-0">
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
        </div>
        <div class="col-md-8">
            <button class="btn btn-secondary back-button" onclick="goBack()">Back</button>
            <div class="room-display">Room Display</div>
            <div class="card">
                <div class="card-header text-center"><b>Tenants</b></div>
                <div class="card-body">
                    <div class="row">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class='text-center' style="width: 60px;">#</th>
                                    <th class='text-center'>Name</th>
                                    <th class='text-center'>Date in</th>
                                </tr>
                            </thead>
                            <tbody id="tenant-table-body">
                                <!-- Tenant data will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get the room_no parameter from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const roomNo = urlParams.get('room_no');

        if (roomNo) {
            // Find the sidebar item with the corresponding room number
            const roomItem = Array.from(document.querySelectorAll('.navsidebar-item')).find(item => item.textContent.includes(roomNo));
            
            if (roomItem) {
                // Add 'selected' class to the room item
                roomItem.classList.add('selected');
                // Trigger the click event on the room item to load its tenants
                selectRoom(roomItem);
            }
        }
    });

    function selectRoom(element) {
        // Remove 'selected' class from all sidebar items
        var navsidebarItems = document.querySelectorAll('.navsidebar-item');
        navsidebarItems.forEach(function(item) {
            item.classList.remove('selected');
        });

        // Add 'selected' class to the clicked sidebar item
        element.classList.add('selected');

        // Fetch tenants for the selected room using AJAX
        var roomNo = element.textContent.replace('Room ', ''); // Extract room number
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Update table body with fetched data
                document.getElementById("tenant-table-body").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "fetch_tenants.php?room_no=" + roomNo, true);
        xhttp.send();
    }

    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>
