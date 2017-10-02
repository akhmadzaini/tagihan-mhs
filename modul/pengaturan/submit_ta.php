<?php 
defined("RESMI") or die('Akses ditolak'); 

#require_once('fungsi.php');
#require_once('koneksi.php');

$kode = sanitasi($_POST['kode']);
$tahun = sanitasi($_POST['tahun']);
$semester = sanitasi($_POST['semester']);

$sql = "INSERT OR REPLACE INTO ta
        (kode, tahun, semester) 
        VALUES('$kode', '$tahun', '$semester')";

$db->query($sql);

header("location: index.php?mod=pengaturan&hal=ta");