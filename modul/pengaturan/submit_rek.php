<?php 
defined("RESMI") or die('Akses ditolak'); 

#require_once('fungsi.php');
#require_once('koneksi.php');

$kode = sanitasi($_POST['kode']);
$nama = sanitasi($_POST['nama']);

$sql = "INSERT OR REPLACE INTO rekening
        (kode, nama) 
        VALUES('$kode', '$nama')";

$db->query($sql);

header("location: index.php?mod=pengaturan&hal=rek");