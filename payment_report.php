<?php include 'db_connect.php' ?>
<?php 
$month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m');
?>
<style>
    .text-center{
        text-align:center;
    }
    .text-right{
        text-align:right;
    }
    table{
        width: 100%; /* Adjust to fit within the container */
        border-collapse: collapse;
    }
    tr,td,th{
        border:1px solid black;
        text-align: center;
    }
    th{
        vertical-align: middle !important;
        text-align: center;
        width: auto; /* Auto width to fit content */
    }
</style>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12" id="main-content">
                    <form id="filter-report">
                        <div class="row form-group">
                            <label class="control-label col-md-2 offset-md-2 text-right">Month of: </label>
                            <input type="month" name="month_of" class='form-control col-md-4' value="<?php echo ($month_of) ?>">
                            <button class="btn btn-sm btn-block btn-success col-md-2 ml-1">Filter</button>
                        </div>
                    </form>
                    <div id="report">
                        <div class="on-print">
                            <p><center><b>Monthly Income Report</b></center></p>
                            <p><center>for the Month of <b><?php echo date('F, Y', strtotime($month_of . '-1')) ?></b></center></p>
                        </div>
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Room #</th>
                                        <th>Total Amount</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $tamount = 0;
                                    $rooms  = $conn->query("SELECT r.room_no, r.id, COALESCE(SUM(p.total_amount), 0) as total_amount 
                                                            FROM room r 
                                                            LEFT JOIN tenants t ON r.id = t.room_id 
                                                            LEFT JOIN payments p ON t.id = p.tenant_id 
                                                            AND DATE_FORMAT(p.date_created, '%Y-%m') = '$month_of'
                                                            GROUP BY r.id, r.room_no");
                                    if($rooms->num_rows > 0):
                                        while($row = $rooms->fetch_assoc()):
                                            $tamount += $row['total_amount'];
                                    ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $row['room_no'] ?></td>
                                        <td class="text-right"><?php echo number_format($row['total_amount'], 2) ?></td>
                                        <td><a href="javascript:void(0)" class="view-icon" data-room-id="<?php echo $row['id'] ?>" data-month-of="<?php echo $month_of ?>"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <tr>
                                        <th colspan="4"><center>No Data.</center></th>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align: left;">Total Amount</th>
                                        <th class='text-right'><?php echo number_format($tamount, 2) ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="room-details" style="display: none;">
                    <!-- Room details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#filter-report').submit(function(e){
        e.preventDefault();
        location.href = 'index.php?page=payment_report&' + $(this).serialize();
    });

    $(document).on('click', '.view-icon', function(){
        var room_id = $(this).data('room-id');
        var month_of = $(this).data('month-of');
        $.ajax({
            url: 'room_details.php',
            method: 'GET',
            data: { room_id: room_id, month_of: month_of },
            success: function(response){
                $('#main-content').hide();
                $('#room-details').html(response).show();
            }
        });
    });

    $(document).on('click', '#back-btn', function(){
        $('#room-details').hide();
        $('#main-content').show();
    });
</script>
