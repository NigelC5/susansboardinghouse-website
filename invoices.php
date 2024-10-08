<?php
include('db_connect.php');

if(isset($_POST['submit'])) {
    $tenant_id = $_POST['tenant_id'];
    $rent_payment = $_POST['rent_payment'];
    $appliance_payment = $_POST['appliance_payment'];
    $total_amount = $rent_payment + $appliance_payment;
    $period_covered = $_POST['period_covered'];
    $date_created = date('Y-m-d H:i:s');
    
    // Fetch the room number for the tenant
    $query = "SELECT room_no FROM room r INNER JOIN tenants t ON r.id = t.room_id WHERE t.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    $room_no = $room['room_no'];
    
    // Insert payment details including the room number
    $query = "INSERT INTO payments (tenant_id, room_no, rent_payment, appliance_payment, total_amount, period_covered, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issddss", $tenant_id, $room_no, $rent_payment, $appliance_payment, $total_amount, $period_covered, $date_created);
    
    if($stmt->execute()) {
        echo "New payment added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
<div class="container-fluid">
    
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">
                
            </div>
        </div>
        <div class="row">
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Payments</b>
                        <span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_invoice">
                    <i class="fa fa-plus"></i> New Entry
                </a></span>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Room #</th>
                                    <th class="">Tenant</th>
                                    <th class="">Payment Date</th>
                                    <th class="">Start Date</th>
                                    <th class="">End Date</th>
                                    <th class="">Rent Payment</th>
                                    <th class="">Appliance Payment</th>
                                    <th class="">Total Paid</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $invoices = $conn->query("SELECT p.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, t.room_id, r.room_no 
                                                FROM payments p 
                                                INNER JOIN tenants t ON t.id = p.tenant_id 
                                                INNER JOIN room r ON r.id = t.room_id 
                                                WHERE t.status = 1 
                                                ORDER BY DATE(p.date_created) DESC");

                                while($row = $invoices->fetch_assoc()):
                                    
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td class="">
                                        <p><?php echo ucwords($row['room_no']) ?></p>
                                    </td>

                                    <td class="">
                                         <p><?php echo ucwords($row['name']) ?></p>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y',strtotime($row['date_created'])) ?>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y',strtotime($row['start_date'])) ?>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y',strtotime($row['end_date'])) ?>
                                    </td>

                                    <td class="">
                                         <p><?php echo number_format($row['rent_payment']) ?></p>
                                    </td>
                                    <td class="">
                                         <p><?php echo number_format($row['appliance_payment']) ?></p>
                                    </td>
                                    <td class="text-right">
                                         <p> <b><?php echo number_format($row['total_amount'],2) ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary edit_invoice" type="button" data-id="<?php echo $row['id'] ?>">
                                            <i class="fa fa-eye"></i> <!-- Font Awesome icon for View -->
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete_invoice" type="button" data-id="<?php echo $row['id'] ?>">
                                            <i class="fa fa-trash-alt"></i> <!-- Font Awesome icon for Delete -->
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>  

</div>
<style>
    
    th{
        vertical-align: middle !important;
        text-align: center;
    }
    td{
        vertical-align: middle !important;
        font-size: 15px;
        text-align: center;
    }
    td p{
        margin: unset
    }
    img{
        max-width:100px;
        max-height: :150px;
    }
    .card {
        width: 100%; /* Set card width to 100% */
        font-size: 15px;
    }
</style>
<script>
    $(document).ready(function(){
        $('table').dataTable()
    })
    
    $('#new_invoice').click(function(){
        uni_modal("New invoice","manage_payment.php","mid-large")
        
    })
    $('.edit_invoice').click(function(){
        uni_modal("Manage invoice Details","manage_payment.php?id="+$(this).attr('data-id'),"mid-large")
        
    })
    $('.delete_invoice').click(function(){
        _conf("Are you sure to delete this payment?","delete_invoice",[$(this).attr('data-id')])
    })
    
    function delete_invoice($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_payment',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully deleted",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)

                }
            }
        })
    }
</script>
