<?php 
defined("RESMI") or die('Akses ditolak');

$nominal = $_POST['nominal'];
$nim = $_POST['nim'];
$atribut = @$_POST['atribut'];
$tanggal = tgl_database($_POST['tanggal']);

foreach ($nominal as $key => $value) {
  $value = sanitasi($value);
  if($value > 0){  
    $potongan = (sanitasi(@$atribut[$key]) == "1") ? "1" : "0";
    $kunci = explode('-', $key);
    $sql = "INSERT INTO mhs_pembayaran (mhs_nim, ta_kode, rekening_kode, nominal, tgl, potongan)
            VALUES ('$nim', '$kunci[0]', '$kunci[1]', $value, '$tanggal', $potongan) ";
    $db->exec($sql);
  }
}