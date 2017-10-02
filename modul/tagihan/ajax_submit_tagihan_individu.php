<?php 
defined("RESMI") or die('Akses ditolak'); 

$nim = sanitasi($_POST['nim']);
$ta = sanitasi($_POST['ta']);
$rekening = $_POST['rekening'];
$tgl_sekarang = tgl_database(tgl_sekarang());

var_dump($rekening);

$sql = "DELETE FROM mhs_tagihan WHERE mhs_nim = '$nim' AND ta_kode = '$ta'";
$db->exec($sql);

foreach ($rekening as $key => $value) {
	$value = sanitasi($value);
	if($value > 0){	
		$sql = "INSERT INTO mhs_tagihan (mhs_nim, ta_kode, rekening_kode, nominal, tgl)
				VALUES ('$nim', '$ta', '$key', $value, '$tgl_sekarang')";

		$db->exec($sql);
	}
}