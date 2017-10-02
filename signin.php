<?php
session_start();
define("RESMI", "OK");

require('fungsi.php');
$userid = sanitasi($_POST['userid']);
$password = sanitasi($_POST['password']);

if(($userid == 'admin') && ($password == 'admin')){
	$_SESSION['userid'] = $userid;
	$_SESSION['nama_pengguna'] = 'Pengelola Keuangan';
	header("Location: index.php");
}else{
	header("Location: login.php?error=login_gagal");
}