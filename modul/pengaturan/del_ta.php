<?php 
defined("RESMI") or die('Akses ditolak'); 

#require_once('fungsi.php');
#require_once('koneksi.php');

$kode = sanitasi($_GET['kode']);

$sql = "DELETE FROM ta WHERE kode='$kode'";

$db->exec($sql);

if($db->changes() > 0){
	header("location: index.php?mod=pengaturan&hal=ta&msg=hapus_sukses");
}else{
	header("location: index.php?mod=pengaturan&hal=ta&msg=hapus_gagal");
}
