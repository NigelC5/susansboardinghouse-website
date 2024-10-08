<?php
include('db_connect.php');
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" id="manage-room">
                    <div class="card" style="margin-top: 50px;">
                        <div class="card-header">
                            Room Form
                        </div>
                        <div class="card-body">
                            <div class="form-group" id="msg"></div>
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label class="control-label">Room #</label>
                                <input type="text" class="form-control" name="room_no" required="">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Price</label>
                                <input type="number" class="form-control text-right" name="price" step="any" required="">
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-success col-sm-3 offset-md-3"> Save</button>
                                    <button class="btn btn-sm btn-default col-sm-3" type="reset"> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card" style="margin-top: 50px;">
                    <div class="card-header">
                        <b>Room List</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Room</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $x = 1;
                                $room = $conn->query("SELECT * FROM room ORDER BY id ASC");
                                while ($row = $room->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $x++; ?></td>
                                        <td>
                                            <p>Room #: <b><?php echo $row['room_no'] ?></b></p>
                                            <p><small>Price: <b><?php echo number_format($row['price'],2) ?></b></small></p>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary edit_room" type="button" data-id="<?php echo $row['id'] ?>"
                                                data-room_no="<?php echo $row['room_no'] ?>" data-price="<?php echo $row['price'] ?>">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete_room" type="button" data-id="<?php echo $row['id'] ?>">
                                                <i class="fa fa-trash-alt"></i>
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
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset;
        padding: unset;
        line-height: 1em;
    }
</style>

<script>
    $('#manage-room').on('reset', function(e) {
        $('#msg').html('');
    });

    $('#manage-room').submit(function(e) {
        e.preventDefault();
        start_load();
        $('#msg').html('');
        $.ajax({
            url: 'ajax.php?action=save_room',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (resp == 2) {
                    $('#msg').html('<div class="alert alert-danger">Room number already exists.</div>');
                    end_load();
                }
            }
        });
    });

    $('.edit_room').click(function() {
        start_load();
        var cat = $('#manage-room');
        cat.get(0).reset();
        cat.find("[name='id']").val($(this).attr('data-id'));
        cat.find("[name='room_no']").val($(this).attr('data-room_no'));
        cat.find("[name='price']").val($(this).attr('data-price'));
        end_load();
    });

    $('.delete_room').click(function() {
        _conf("Are you sure you want to delete this room?", "delete_room", [$(this).attr('data-id')]);
    });

    function delete_room($id) {
        $.ajax({
            url: 'ajax.php?action=delete_room',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 0);
                }
            }
        });
    }

    $('table').dataTable();
</script>
