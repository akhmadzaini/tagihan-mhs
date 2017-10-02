<?php 
defined("RESMI") or die('Akses ditolak'); 

$prodi_kode = sanitasi($_GET['prodi_kode']);
$angkatan = sanitasi($_GET['angkatan']);
$ta_kode = sanitasi($_GET['ta_kode']);

$sql = "SELECT a.nim, a.nama, a.skema, IFNULL(b.nominal, 0) AS nominal
        FROM mhs a 
        LEFT JOIN vw_mhs_tagihan_agregate_nominal b ON a.nim = b.mhs_nim AND b.ta_kode = '$ta_kode'
        WHERE a.prodi_kode = '$prodi_kode'
        AND a.angkatan = '$angkatan'";
$res = $db->query($sql);
$data = array();
$n = 1;
while($r = $res->fetchArray(SQLITE3_ASSOC)){
	$nominal = "<a href=\"#\" class=\"btn-rincian\" data-nim=\"$r[nim]\" data-ta=\"$ta_kode\" data-nama=\"$r[nama]\">". format_rp($r['nominal']) ."</a>"; 
	$data[] = array($n++, $r['nim'], $r['nama'], $r['skema'], $nominal);
}

echo json_encode($data);
