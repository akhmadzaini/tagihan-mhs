<?php 
defined("RESMI") or die('Akses ditolak'); 

#require_once('fungsi.php');
#require_once('koneksi.php');

$kode = sanitasi($_GET['kode']);

$sql = "DELETE FROM rekening WHERE kode='$kode'";

$db->exec($sql);

if($db->changes() > 0){
	echo '1';	
}else{
	echo '0';	
}
