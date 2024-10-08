<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="row mb-4 mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <b>List of Tenant</b>
                        <span><a class="btn btn-primary btn-sm" href="javascript:void(0)" id="new_tenant">
                            <i class="fas fa-user-plus"></i> New Tenant
                        </a></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered table-hover" id="tenant_table">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Name</th>
                                    <th>Room</th>
                                    <th>Gender</th>
                                    <th style="text-align: center;">Email</th>
                                    <th style="text-align: center;">Address</th>
                                    <th class="appliance" style="text-align: center;">Appliances | #</th>
                                    <th class="move" style="text-align: center;">Move-in Date</th>
                                    <th class="text-center" style="text-align: center;">Due Day</th>
                                    <th class="text-center" style="width: 20px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $tenant = $conn->query("SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) as name, r.room_no FROM tenants t INNER JOIN room r ON r.id = t.room_id WHERE t.status = 1 ORDER BY r.room_no DESC");
                                while($row = $tenant->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td><?php echo ucwords($row['name']) ?></td>
                                        <td><?php echo $row['room_no'] ?></td>
                                        <td><?php echo $row['gender'] ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td><?php echo $row['address'] ?></td>
                                        <!-- Here we display appliances and total items in a subcolumn format -->
                                        <td class="appliance">
                                            <div class="row">
                                                <div class="col-md-8"><?php echo $row['appliances'] ?></div>
                                                <div class="col-md-3"><?php echo $row['total_items'] ?></div>
                                            </div>
                                        </td>
                                        <td style="width: 130px; text-align: center;"><?php echo date("M-d-Y", strtotime($row['date_in'])) ?></td>
                                        <td><?php echo $row['due_day'] ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-success view_payment" type="button" data-id="<?php echo $row['id'] ?>">
                                                <i class="fa fa-eye"></i> <!-- Font Awesome icon for Edit -->
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary edit_tenant" type="button" data-id="<?php echo $row['id'] ?>">
                                                <i class="fa fa-pencil-alt"></i> <!-- Font Awesome icon for Edit -->
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id'] ?>">
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
        </div>
    </div>
</div>

<style>
    td, th {
        max-width: 140px; /* Adjust the maximum width as needed */
        font-size: 15px;
    }
    /* Adjust the width of the appliance column */
    .appliance {
        width: 150px; /* Adjust as needed */
    }
    /* Adjust card width */
    .card {
        width: 99%; /* Set card width to 100% */
        font-size: 16px;
    }
</style>

<script>
    $(document).ready(function() {
        $('#tenant_table').dataTable();
    });
    
    $('#new_tenant').click(function() {
        uni_modal("New Tenant", "manage_tenant.php", "mid-large");
    });
    $('.view_payment').click(function(){
        uni_modal("Tenants Payments","view_payment.php?id="+$(this).attr('data-id'),"large")
        
    })
    $('.edit_tenant').click(function() {
        uni_modal("Manage Tenant Details", "manage_tenant.php?id=" + $(this).attr('data-id'), "mid-large");
    });

    $('.delete_tenant').click(function() {
        _conf("Are you sure to delete this Tenant?", "delete_tenant", [$(this).attr('data-id')]);
    });
    
    function delete_tenant($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_tenant',
            method: 'POST',
            data: {id: $id},
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
