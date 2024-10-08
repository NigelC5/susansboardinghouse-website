<?php include 'db_connect.php'; ?>

<style>
    .status-available {
        color: green;
        font-weight: bold;
    }

    .status-occupied {
        color: red;
    }

    table.table th,
    table.table td {
        width: 20%; /* Adjusted width to fit the new column */
        text-align: center;
    }
    .btn{
        color: blue;
    }
</style>

<div class="container-fluid" style="width: 90%;">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <!-- Existing Cards -->
            <div class="card" style="width: 90%;">
                <div class="card-body" style="width: 100%; margin-left: 30px;">

                    <div class="card mt-4" style="width: 90%;">
                        <div class="card-body" style="margin-left: 50px; margin-right: 30px;">
                            <h4><center>Room Occupancy and Availability</center></h4><br>
                            <table class="table table-condensed table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Room No</th>
                                        <th>Occupancy</th>
                                        <th>Availability</th>
                                        <th>Status</th>
                                        <th>View Room</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $roomQuery = $conn->query("SELECT r.room_no, COUNT(t.room_id) AS occupancy FROM room r LEFT JOIN tenants t ON r.room_no = t.room_id AND t.status = 1 GROUP BY r.room_no");

                                    while ($row = $roomQuery->fetch_assoc()) {
                                        $room_no = $row['room_no'];
                                        $occupancy = $row['occupancy'];
                                        $availability = 10 - $occupancy; // Assuming default capacity is 10
                                        $status = $occupancy == 10 ? 'Occupied' : 'Available';

                                        $status_class = $status == 'Available' ? 'status-available' : 'status-occupied';
                                    ?>
                                        <tr>
                                            <td><?php echo $room_no; ?></td>
                                            <td><?php echo $occupancy; ?></td>
                                            <td><?php echo $availability; ?></td>
                                            <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                                            <td><a href="view_room.php?room_no=<?php echo $room_no; ?>"  class="btn"><i class="fa fa-eye"></i></a></td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
