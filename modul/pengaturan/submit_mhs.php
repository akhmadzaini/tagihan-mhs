<?php 
defined("RESMI") or die('Akses ditolak'); 

#require_once('fungsi.php');
#require_once('koneksi.php');

$nim = sanitasi($_POST['nim']);
$nama = sanitasi($_POST['nama']);
$prodi = sanitasi($_POST['prodi']);
$angkatan = sanitasi($_POST['angkatan']);
$skema = sanitasi($_POST['skema']);

$sql = "INSERT OR REPLACE INTO mhs
        (nim, nama, prodi_kode, angkatan, skema) 
        VALUES('$nim', '$nama', '$prodi', '$angkatan', '$skema')";

$db->query($sql);

header("location: index.php?mod=pengaturan&hal=mhs&prodi=$prodi&angkatan=$angkatan");