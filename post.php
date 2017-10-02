<?php
session_start();
define("RESMI", "OK");

if(!isset($_SESSION['userid'])){
  header("Location: login.php");
}

require_once('fungsi.php');
require_once('koneksi.php');

if(isset($_GET['mod'])){
  $mod = sanitasi($_GET['mod']);
  $hal = sanitasi($_GET['hal']);

  include('modul/' . $mod . '/' . $hal . '.php');
}