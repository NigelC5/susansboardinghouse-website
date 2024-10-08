<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'update_account'){
	$save = $crud->update_account();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_room"){
	$save = $crud->save_room();
	if($save)
		echo $save;
}
if($action == "delete_room"){
	$save = $crud->delete_room();
	if($save)
		echo $save;
}

if($action == "save_tenant"){
	$save = $crud->save_tenant();
	if($save)
		echo $save;
}
if($action == "delete_tenant"){
	$save = $crud->delete_tenant();
	if($save)
		echo $save;
}
if($action == "get_tdetails"){
	$get = $crud->get_tdetails();
	if($get)
		echo $get;
}

if($action == "save_payment"){
	$save = $crud->save_payment();
	if($save)
		echo $save;
}
if($action == "delete_payment"){
	$save = $crud->delete_payment();
	if($save)
		echo $save;
}

ob_end_flush();
?>
