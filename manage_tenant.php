<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM tenants WHERE id = " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-tenant">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row form-group">
            <div class="col-md-4">
                <label for="" class="control-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" value="<?php echo isset($lastname) ? $lastname : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">First Name</label>
                <input type="text" class="form-control" name="firstname" value="<?php echo isset($firstname) ? $firstname : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Middle Name</label>
                <input type="text" class="form-control" name="middlename" value="<?php echo isset($middlename) ? $middlename : '' ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="" class="control-label">Gender</label>
                <select class="custom-select" name="gender" required>
                    <option value="" class="disabled hidden">Select Gender</option>
                    <option value="Male" <?php echo isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?php echo isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?php echo isset($gender) && $gender == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="col-md-8">
                <label for="" class="control-label">Email</label>
                <input type="text" class="form-control" name="email" value="<?php echo isset($email) ? $email : '' ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <label for="" class="control-label">Address</label>
                <input type="address" class="form-control" name="address" value="<?php echo isset($address) ? $address : '' ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="" class="control-label">Room</label>
                <select name="room_id" class="custom-select select2">
                    <option value=""></option>
                    <?php
                    $room = $conn->query("SELECT * FROM room");
                    while ($row = $room->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($room_id) && $room_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['room_no'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label for="" class="control-label">Appliances</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="appliances" id="appliances" value="<?php echo isset($appliances) ? $appliances : '' ?>" required>
                </div>
                <div class="d-flex align-items-center mt-1">
                    <small>Total items:</small>
                    <div class="input-group ml-2">
                        <span class="input-group-text" id="total_items" style="background-color: #e9ecef;">0</span>
                    </div>
                </div>
                <input type="hidden" name="total_items" id="total_items_input" value="0">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="" class="control-label">Move-in Date</label>
                <input type="date" class="form-control" name="date_in" id="date_in" value="<?php echo isset($date_in) ? date("Y-m-d", strtotime($date_in)) : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Due Day</label>
                <input type="number" class="form-control" name="due_day" value="<?php echo isset($due_day) ? $due_day : '' ?>" required>
            </div>
        </div>
    </form>
</div>

<script>
function updateTotalItems() {
    const applianceInput = document.getElementById('appliances').value;
    const items = applianceInput.split(',').filter(item => item.trim() !== '');
    const totalItems = items.length;
    document.getElementById('total_items').textContent = totalItems;
    document.getElementById('total_items_input').value = totalItems;
}

document.getElementById('appliances').addEventListener('input', updateTotalItems);
updateTotalItems();

$('#manage-tenant').submit(function(e) {
    e.preventDefault();
    start_load();
    $.ajax({
        url: 'ajax.php?action=save_tenant',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Data successfully saved.", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        }
    });
});
</script>
