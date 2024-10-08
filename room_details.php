<?php include 'db_connect.php' ?>
<?php 
$room_id = isset($_GET['room_id']) ? $_GET['room_id'] : 0;
$month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m');

$room_details = $conn->query("SELECT room_no FROM room WHERE id = $room_id")->fetch_assoc();
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
 <div class="row">
        <button id="back-btn" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i> Back</button>
    </div>
<div class="card" style="margin-top: 20px;">
    <div class="card-body">
        <div class="col-md-12">
            <div id="report">
                <div class="on-print">
                    <p><center><b>Tenant Payment Details</b></center></p>
                    <p><center>for Room <b><?php echo $room_details['room_no'] ?></b> for the Month of <b><?php echo date('F, Y', strtotime($month_of . '-1')) ?></b></center></p>
                </div>
                <div class="row">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Rent Payment</th>
                                <th>Appliance Payment</th>
                                <th>Total Amount</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $tenant_payments = $conn->query("SELECT p.id as payment_id, CONCAT(t.lastname, ', ', t.firstname) as name, p.rent_payment, p.appliance_payment, p.total_amount, p.date_created 
                                                            FROM payments p 
                                                            JOIN tenants t ON p.tenant_id = t.id 
                                                            WHERE t.room_id = $room_id 
                                                            AND DATE_FORMAT(p.date_created, '%Y-%m') = '$month_of'");
                            if($tenant_payments->num_rows > 0):
                                while($row = $tenant_payments->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $row['name'] ?></td>
                                <td class="text-right"><?php echo number_format($row['rent_payment'], 2) ?></td>
                                <td class="text-right"><?php echo number_format($row['appliance_payment'], 2) ?></td>
                                <td class="text-right"><?php echo number_format($row['total_amount'], 2) ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['date_created'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <th colspan="6"><center>No Data.</center></th>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
