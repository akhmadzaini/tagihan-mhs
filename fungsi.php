<?php

defined("RESMI") or die('Akses ditolak');
function sanitasi($data){
	$data = trim($data);
 	#$data = stripslashes($data);
 	#$data = addslashes($data);
  	$data = SQLite3::escapeString($data);
  	return $data;
}

function combo_prodi($default="", $required='required'){
	global $db;
	$sql = "SELECT kode, nama FROM prodi ORDER BY nama";
	$ret = $db->query($sql);
	$result = '<select name="prodi" class="form-control" '. $required .'>';
	$result .= '<option value="">--Pilih Program Studi--</option>';
	while($r = $ret->fetchArray(SQLITE3_ASSOC)){
		$selected = ($default == $r['kode']) ? 'selected' : '';
		$result .= '<option value="'. $r['kode'] .'" '. $selected .'>'. $r['nama'] .'</option>';
	}
	$result .= '</select>';
	return $result;
}

function combo_angkatan($default="", $required='required'){
	global $db;
	$sql = "SELECT DISTINCT angkatan FROM mhs ORDER BY angkatan DESC";
	$ret = $db->query($sql);
	$result = '<select name="angkatan" class="form-control" '. $required .'>';
	$result .= '<option value="">--Pilih Angkatan--</option>';
	while($r = $ret->fetchArray(SQLITE3_ASSOC)){
		$selected = ($default == $r['angkatan']) ? 'selected' : '';
		$result .= '<option value="'. $r['angkatan'] .'" '. $selected .'>'. $r['angkatan'] .'</option>';
	}
	$result .= '</select>';
	return $result;	
}

function status_mhs($status){
	$r = ($status == 1) ? "Aktif" : "Nonaktif";
	return $r;
}

function combo_ta($default="", $required="required"){
	global $db;
	$sql = "SELECT kode, tahun, semester FROM ta ORDER BY kode DESC";
	$ret = $db->query($sql);
	$result = '<select name="ta" class="form-control" '. $required .'>';
	$result .= '<option value="">--Pilih Tahun Akademik--</option>';
	while($r = $ret->fetchArray(SQLITE3_ASSOC)){
		$selected = ($default == $r['kode']) ? 'selected' : '';
		$result .= '<option value="'. $r['kode'] .'" '. $selected .'>'. $r['tahun'] . '-' . $r['semester'] . '</option>';
	}
	$result .= '</select>';
	return $result;
}

function combo_rekening($default="", $required="required"){
	global $db;
	$sql = "SELECT kode, nama FROM rekening ORDER BY nama";
	$ret = $db->query($sql);
	$result = '<select name="rekening" class="form-control" '. $required .'>';
	$result .= '<option value="">--Pilih Jenins Rekening--</option>';
	while($r = $ret->fetchArray(SQLITE3_ASSOC)){
		$selected = ($default == $r['kode']) ? 'selected' : '';
		$result .= '<option value="'. $r['kode'] .'" '. $selected .'>'. $r['nama'] . '</option>';
	}
	$result .= '</select>';
	return $result;
}

function format_rp($angka){
	$jadi = "Rp. " . number_format($angka,0,',','.');
	return $jadi . ",-";
}

function tgl_sekarang(){
	return $date = date('d/m/Y', time());
}

function tgl_database($tgl){
	$var = $tgl;
	return implode("-", array_reverse(explode("/", $var)));
}

function tgl_aplikasi($tgl){
	$var = $tgl;
	return implode("/", array_reverse(explode("-", $var)));
}

function tgl_teks($tgl){
	$data = explode('/', $tgl);
	$bulan = array(
		'01' => 'Januari',
		'02' => 'Februari',
		'03' => 'Maret',
		'04' => 'April',
		'05' => 'Mei',
		'06' => 'Juni',
		'07' => 'Juli',
		'08' => 'Agustus',
		'09' => 'September',
		'10' => 'Oktober',
		'11' => 'Nopember',
		'12' => 'Desember'
		);
	return $data[0] . ' ' . $bulan[$data[1]] . ', ' . $data[2];
}

function label_potongan($var){
	$hasil = ($var == '1') ? 'Ya' : 'Bukan';
	return $hasil;
}