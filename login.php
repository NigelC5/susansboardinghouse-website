<!DOCTYPE html>
<html lang="en">

<?php 
session_start();
include('./db_connect.php');
ob_start();
if (!isset($_SESSION['system'])) {
    $system = $conn->query("SELECT * FROM system_settings LIMIT 1")->fetch_array();
    foreach ($system as $k => $v) {
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo $_SESSION['system']['name']; ?></title>
    <?php include('./header.php'); ?>
    <?php 
    if (isset($_SESSION['login_id'])) {
        header("location:index.php?page=home");
    }
    ?>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
        background-image: url('assets/uploads/image2.jpg'); /* Your background image */
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Container for the entire login section */
    .login-container {
        display: flex;
        background: rgba(0, 0, 0, 0.6); /* Slight dark overlay on background */
        border-radius: 10px;
        overflow: hidden;
        width: 80%;
        max-width: 1000px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
    }

    /* Left section for the welcome message */
    .welcome-section {
        width: 50%;
        padding: 50px;
        color: #ffffff; /* White color for contrast */
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: rgba(0, 0, 0, 0.7); /* Dark overlay */
    }

    .welcome-section h1 {
        font-size: 36px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .welcome-section p {
        font-size: 16px;
        line-height: 1.6;
    }

    /* Right section for the login form */
    .login-form-section {
        width: 50%;
        padding: 50px;
        background: rgba(255, 255, 255, 0.95); /* Light background for the form */
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    .login-form-section h2 {
        margin-bottom: 20px;
        font-size: 28px;
        text-align: center;
        color: #2c3e50; /* Dark shade for contrast */
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 14px;
        color: #34495e; /* Slightly darker shade */
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        padding-left: 30px; /* Add padding to make space for the icon */
    }

    .form-group input:focus {
        border-color: #3498db; /* Blue tone for input focus */
        outline: none;
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        left: 10px; /* Space from the left */
        top: 50%; /* Center vertically */
        transform: translateY(-50%);
        color: #34495e; /* Color of the icon */
    }

    /* Styling for the sign-in button */
    .login-button {
        width: 100%;
        padding: 12px;
        background-color: rgba(255, 173, 0, 0.95);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .login-button:hover {
        background-color: rgba(255, 153, 0, 0.95); /* Darker red shade on hover */
    }
</style>

<body>
    <div class="login-container">
        <!-- Left Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome Back To <br> Susan's Boarding House</h1>
            <p>
                Admin and Cashier, please log in to access your account and manage the system efficiently.
            </p>
        </div>

        <!-- Right Login Form Section -->
        <div class="login-form-section">
            <form id="login-form">
                <h2>User Login</h2>
                <div class="form-group">
                    <label for="username">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#login-form').submit(function (e) {
            e.preventDefault();
            const $form = $(this);
            $form.find('button[type="submit"]').attr('disabled', true).text('Logging in...');

            $.ajax({
                url: 'ajax.php?action=login',
                method: 'POST',
                data: $form.serialize(),
                error: err => {
                    console.error(err);
                    $form.find('button[type="submit"]').removeAttr('disabled').text('Sign in now');
                },
                success: function (resp) {
                    if (resp == 1) {
                        window.location.href = 'index.php?page=home';
                    } else {
                        $form.prepend('<div class="alert alert-danger">Email or password is incorrect.</div>');
                        $form.find('button[type="submit"]').removeAttr('disabled').text('Sign in now');
                    }
                }
            });
        });
    </script>
</body>

</html>