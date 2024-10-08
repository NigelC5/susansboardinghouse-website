<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM payments where id= ".$_GET['id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k=$val;
    }
}
?>
<!-- Form for managing payment -->
<div class="container-fluid">
    <form action="" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>
        
        <!-- Form fields for tenant selection, appliance payment, rent payment, start date, end date, and total amount -->
        <div class="form-group">
            <label for="tenant_id" class="control-label">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2">
                <option value=""></option>
                <?php
                $tenant = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM tenants WHERE status = 1 ORDER BY name ASC");
                while ($row = $tenant->fetch_assoc()):
                ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($tenant_id) && $tenant_id == $row['id'] ? 'selected' : '' ?>>
                        <?php echo ucwords($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group" id="details">
            <!-- Details will be populated dynamically -->
        </div>
        <div class="form-group">
            <label for="appliance_payment" class="control-label">Appliance Payment:</label>
            <input type="number" class="form-control text-right" name="appliance_payment" value="<?php echo isset($appliance_payment) ? $appliance_payment : '' ?>">
        </div>
        <div class="form-group">
            <label for="rent_payment" class="control-label">Rent Payment:</label>
            <input type="number" class="form-control text-right" name="rent_payment" value="<?php echo isset($rent_payment) ? $rent_payment : '' ?>">
        </div>
        <div class="form-group">
            <label for="start_date" class="control-label">Rent Payment Start Date:</label>
            <input type="date" class="form-control" name="start_date" value="<?php echo isset($start_date) ? $start_date : '' ?>">
        </div>
        <div class="form-group">
            <label for="end_date" class="control-label">Rent Payment End Date:</label>
            <input type="date" class="form-control" name="end_date" value="<?php echo isset($end_date) ? $end_date : '' ?>">
        </div>
        <div class="form-group">
            <label for="total_amount" class="control-label">Total Paid:</label>
            <input type="number" class="form-control text-right" name="total_amount" value="<?php echo isset($total_amount) ? $total_amount : '' ?>">
        </div>
    </form>
</div>

<!-- Hidden receipt template -->
<div id="receipt_template" style="display: none;">
    <div>
        <center>
            <h2><b>Susan's Boarding House</b></h2>
            <p><b>Address:</b> Road 1, Tondo, Cannery Site, Polomolok, South Cotabato</p>
            <p><b>Email:</b> susansboardinghouse@gmail.com</p>
            <p><b>Contact #:</b> 0963 1293 063</p>
        </center>
        <hr>
        <div id="receipt-header" style="margin-left: 200px;">
            <p><b>Date:</b> <span id="date_created"></span></p>
        </div>
        <center>
            <h3><b>Acknowledgment Receipt</b></h3>
            <p>This is to acknowledge the receipt from <b><span id="name"></span></b><br>, the total amount of <b><span id="total_amount"></span></b> PHP, as payment for<br> <b>Susan's Boarding House</b> with details of payment below.</p>
        </center>
        <table id="receipt-details" style="margin-left: 200px;">
            <tr>
                <td><b>Appliance Payment:</b></td>
                <td id="appliance_payment"></td>
            </tr>
            <tr>
                <td><b>Rent Payment:</b></td>
                <td id="rent_payment"></td>
            </tr>
            <tr>
                <td><b>Rent Payment Start Date - End Date:</b></td>
                <td id="rent_period"></td>
            </tr>
        </table>
        <div id="receipt-footer" style="margin-left: 200px;">
            <p>Received by:</p>
            <p><b>_________________________</b></p>
            <p><b>Signature or Printed Name</b></p>
        </div>
    </div>
</div>

<style>
/* General Styles */
#receipt_template {
    font-family: Arial, sans-serif;
    width: 80%; /* Adjust the width according to your preference */
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #000;
}

/* Centered text */
#receipt_template center {
    text-align: center;
}

/* Header and footer styles */
#receipt-header, #receipt-footer {
    margin-left: 20px;
}

/* Details table styling */
#receipt-details {
    width: 100%; /* Ensure the table spans the width of the container */
    margin-top: 10px;
    border-collapse: collapse;
    border-top: 1px solid #000;
}

#receipt-details td {
    padding: 5px; /* Add padding for better readability */
}

#receipt-details td:first-child {
    font-weight: bold; /* Make the first column bold */
}

/* Signature line */
#receipt-footer p {
    margin: 10px 0; /* Add some margin for better spacing */
}

</style>



<div id="details_clone" style="display: none">
    <div class='d'>
        <large><b>Details</b></large>
        <hr>
        <p>Tenant: <b class="tname"></b></p>
        <p><b>Rent Payment</b></p>
        <p>Monthly Rental Rate: <b class="price"></b></p>
        <p>Paid: <b class="total_paid"></b></p>
        <p>Balance: <b class="rental_balance"></b></p>
        <p>Rent Started: <b class='rent_started'></b></p>
        <p>Payable Months: <b class="payable_months"></b></p>
        <p>Payable Rent Amount: <b class="payable_amount"></b></p><br>
        <p><b>Appliance Payment</b></p>
        <p>Appliance Fee: <b class="appliance_fee"></b></p>
        <p>Paid: <b class="appliance_paid"></b></p>
        <p>Balance: <b class="appliance_balance"></b></p>
        <hr>
    </div>
</div>
<script>
   $(document).ready(function() {
    // Initialize select2 for tenant dropdown
    $('.select2').select2({
        placeholder: "Please Select Here",
        width: "100%"
    });

    // Calculate total amount based on appliance payment and rent payment
    function calculateTotalAmount() {
        var appliancePayment = parseFloat($('input[name="appliance_payment"]').val()) || 0;
        var rentPayment = parseFloat($('input[name="rent_payment"]').val()) || 0;
        var totalAmount = appliancePayment + rentPayment;
        $('input[name="total_amount"]').val(totalAmount.toFixed(2));
    }

    // Initialize event listeners for appliance_payment and rent_payment fields
    $('input[name="appliance_payment"], input[name="rent_payment"]').on('input', function() {
        calculateTotalAmount();
    });

    // Trigger initial total amount calculation
    calculateTotalAmount();

    // Fetch and display details for the selected tenant
    $('#tenant_id').change(function() {
        if ($(this).val() <= 0) return;

        start_load();
        $.ajax({
            url: 'ajax.php?action=get_tdetails',
            method: 'POST',
            data: {
                id: $(this).val(),
                pid: '<?php echo isset($id) ? $id : '' ?>'
            },
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp);
                    var details = $('#details_clone .d').clone();
                    details.find('.tname').text(resp.name);;
                    details.find('.price').text(resp.price);
                    details.find('.total_paid').text(resp.paid);
                    details.find('.rental_balance').text(resp.rental_balance);
                    details.find('.rent_started').text(resp.rent_started);
                    details.find('.payable_months').text(resp.months);
                    details.find('.payable_amount').text(resp.payable_amount);
                    details.find('.appliance_fee').text(resp.appliance_fee);
                    details.find('.appliance_paid').text(resp.appliance_paid);
                    details.find('.appliance_balance').text(resp.appliance_balance);
                    $('#details').html(details);
                }
            },
            complete: function() {
                end_load();
            }
        });
    });

    // Form submission handler
    $('#manage-payment').submit(function(e) {
        e.preventDefault();
        start_load();
        $('#msg').html('');
        $.ajax({
            url: 'ajax.php?action=save_payment',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp === '1') {
                    alert_toast("Data successfully saved.", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);

                    // Generate receipt after saving data
                    const data = {
                        date_created: new Date().toLocaleDateString(), // Payment date is the current date
                        tenant_name: $('select[name="tenant_id"]').find('option:selected').text(),
                        total_amount: $('input[name="total_amount"]').val(),
                        appliance_payment: $('input[name="appliance_payment"]').val(),
                        rent_payment: $('input[name="rent_payment"]').val(),
                        start_date: $('input[name="start_date"]').val(),
                        end_date: $('input[name="end_date"]').val()
                    };

                    generateReceipt(data);
                }
            }
        });
    });

    // Function to generate and print receipt
    function generateReceipt(data) {
        // Populate the receipt template with data
        document.getElementById('date_created').textContent = data.date_created;
        document.getElementById('name').textContent = data.tenant_name;
        document.getElementById('total_amount').textContent = data.total_amount;
        document.getElementById('appliance_payment').textContent = data.appliance_payment;
        document.getElementById('rent_payment').textContent = data.rent_payment;
        document.getElementById('rent_period').textContent = data.start_date + ' - ' + data.end_date;

        // Display the receipt template in a new window and print
        const receiptWindow = window.open('', 'Receipt', 'width=600,height=800');
        receiptWindow.document.write(document.getElementById('receipt_template').innerHTML);
        receiptWindow.document.close();

        // Print the receipt
        receiptWindow.print();
    }
});
</script>
