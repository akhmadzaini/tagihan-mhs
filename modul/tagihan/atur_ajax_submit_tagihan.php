<?php 
defined("RESMI") or die('Akses ditolak'); 

// batas timeout 15 menit
set_time_limit(900);

$prodi_kode = sanitasi($_POST['prodi']);
$ta_kode = sanitasi($_POST['ta']);
$angkatan = sanitasi($_POST['angkatan']);
$skema = sanitasi($_POST['skema']);

$sql = "DELETE FROM tagihan 
		WHERE prodi_kode = '$prodi_kode' AND ta_kode = '$ta_kode' AND angkatan = '$angkatan' AND skema = '$skema'";
$db->exec($sql);

$sql = array();
foreach ($_POST['rek'] as $key => $value) {
	if($value > 0){		
		$rekening_kode = $key;
		$nominal = sanitasi($value);
		$sql[] = "INSERT INTO tagihan (prodi_kode, ta_kode, angkatan, rekening_kode, skema, nominal) 
				VALUES ('$prodi_kode', '$ta_kode', '$angkatan', '$rekening_kode', '$skema', $nominal)";
	}
}
if(!empty($sql)){
	$sql = implode(';', $sql);
	$db->exec($sql);
}

// Baca nim yang terdaftar dalam skema terpilih
$sql = "SELECT nim FROM mhs WHERE prodi_kode = '$prodi_kode' AND angkatan = '$angkatan' AND skema = '$skema'";
$res = $db->query($sql);
$sekarang = tgl_database(tgl_sekarang());

$nim = array();
$sql2 = array();
while($r = $res->fetchArray(SQLITE3_ASSOC)){
	// daftar nim yang dihapus tagihannya
	$nim[] = "'$r[nim]'";

	// Masukkan tagihan baru
	foreach ($_POST['rek'] as $key => $value) {
		$nominal = sanitasi($value);
		if($nominal > 0){
			$sql2[] = "INSERT INTO mhs_tagihan 
					(mhs_nim, ta_kode, rekening_kode, nominal, tgl) 
			 		VALUES ('$r[nim]', '$ta_kode', '$key', $nominal, '$sekarang')";
		}
	}
}


if(!empty($nim)){
	// Hapus tagihan mahasiswa yang lama
	$nim = implode(',', $nim);
	$sql = "DELETE FROM mhs_tagihan WHERE mhs_nim IN ($nim) AND ta_kode = '$ta_kode'";
	$db->exec($sql);
	
	// masukkan tagihan baru
	if(!empty($sql2)){
		$sql2 = implode(';', $sql2);
		$db->exec($sql2);
	}
}

echo 'data telah tersimpan ';