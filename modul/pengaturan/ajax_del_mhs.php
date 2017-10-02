<?php 
defined("RESMI") or die('Akses ditolak'); 

#require_once('fungsi.php');
#require_once('koneksi.php');

$nim = sanitasi($_GET['nim']);

$sql = "DELETE FROM mhs WHERE nim='$nim'";

$db->exec($sql);

if($db->changes() > 0){
	echo '1';	
}else{
	echo '0';	
}
