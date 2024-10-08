<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		
			extract($_POST);		
			$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				if($_SESSION['login_type'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
					return 1;
			}else{
				return 3;
			}
	}
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'password' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}
	function save_room(){
		extract($_POST);
		$data = " room_no = '$room_no' ";
		$data .= ", price = '$price' ";
		$chk = $this->db->query("SELECT * FROM room where room_no = '$room_no' ")->num_rows;
		if($chk > 0 ){
			return 2;
			exit;
		}
			if(empty($id)){
				$save = $this->db->query("INSERT INTO room set $data");
			}else{
				$save = $this->db->query("UPDATE room set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_room(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM room where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_tenant(){
		extract($_POST);
		$data = " firstname = '$firstname' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", email = '$email' ";
		$data .= ", address = '$address' ";
		$data .= ", gender = '$gender' ";
		$data .= ", appliances = '$appliances' ";
		$data .= ", total_items = '$total_items' ";
		$data .= ", room_id = '$room_id' ";
		$data .= ", date_in = '$date_in' ";
		$data .= ", due_day = '$due_day' ";
			if(empty($id)){
				
				$save = $this->db->query("INSERT INTO tenants set $data");
			}else{
				$save = $this->db->query("UPDATE tenants set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_tenant(){
		extract($_POST);
		$delete = $this->db->query("UPDATE tenants set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_tdetails() {
    extract($_POST);
    $data = array();

    // Fetch tenant details from the database
    $tenants = $this->db->query("SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) as name, r.room_no, r.price, t.total_items 
                                FROM tenants t 
                                INNER JOIN room r ON r.id = t.room_id 
                                WHERE t.id = {$id}");
    foreach ($tenants->fetch_array() as $k => $v) {
        if (!is_numeric($k)) {
            $$k = $v;
        }
    }

    // Calculate months and payable amount
    $months = abs(strtotime(date('Y-m-d') . " 23:59:59") - strtotime($date_in . " 23:59:59"));
    $months = floor(($months) / (30 * 60 * 60 * 24));
    $data['months'] = $months;

    // Calculate payable amount
    $payable_amount = $price * $months;
    $data['payable_amount'] = number_format($payable_amount, 2);

    // Fetch total rent paid and last payment date
    $paid = $this->db->query("SELECT SUM(rent_payment) as paid FROM payments WHERE id != '$pid' AND tenant_id = " . $id);
    $last_payment = $this->db->query("SELECT * FROM payments WHERE id != '$pid' AND tenant_id = " . $id . " ORDER BY unix_timestamp(date_created) DESC LIMIT 1");
    $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
    $data['paid'] = number_format($paid, 2);
    $data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y", strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';

    // Calculate outstanding balance
    $rental_balance = $payable_amount - $paid;
    $data['rental_balance'] = number_format($rental_balance, 2);
    $data['price'] = number_format($price, 2);
    $data['name'] = ucwords($name);
    $data['rent_started'] = date('M d, Y', strtotime($date_in));

    // Calculate appliance fee
    $appliance_fee = $total_items * 150;
    $data['appliance_fee'] = number_format($appliance_fee, 2);

    // Fetch total appliance payment
    $appliance_payment_query = $this->db->query("SELECT SUM(appliance_payment) as appliance_payment 
                                                FROM payments 
                                                WHERE tenant_id = " . $id);
    $appliance_payment = $appliance_payment_query->num_rows > 0 ? $appliance_payment_query->fetch_array()['appliance_payment'] : 0;

    // Add total appliance payment to the data
    $data['appliance_paid'] = number_format($appliance_payment, 2);

    // Calculate appliance balance
    $appliance_balance = $appliance_fee - $appliance_payment;
    $data['appliance_balance'] = number_format($appliance_balance, 2);

    // Insert or update outstanding balance in the `outstanding_balance` table
    $check_balance = $this->db->query("SELECT * FROM outstanding_balance WHERE tenant_id = {$id}");
    if ($check_balance->num_rows > 0) {
        // Update existing record
        $update_balance = $this->db->query("
            UPDATE outstanding_balance
            SET rental_balance = {$rental_balance}, appliance_balance = {$appliance_balance}
            WHERE tenant_id = {$id}
        ");
    } else {
        // Insert new record
        $insert_balance = $this->db->query("
            INSERT INTO outstanding_balance (tenant_id, rental_balance, appliance_balance)
            VALUES ({$id}, {$rental_balance}, {$appliance_balance})
        ");
    }

    // Return JSON encoded data
    return json_encode($data);
}


	function save_payment(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','ref_code')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO payments set $data");
			$id=$this->db->insert_id;
		}else{
			$save = $this->db->query("UPDATE payments set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete){
			return 1;
		}
	}
}