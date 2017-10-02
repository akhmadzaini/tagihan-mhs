<?php

defined("RESMI") or die('Akses ditolak');
class db_helper{
	
	static function get_nama_prodi($kode){
		global $db;
		$sql = "SELECT nama FROM prodi WHERE kode = '$kode'";
		$res = $db->query($sql);
		$r = $res->fetchArray(SQLITE3_ASSOC);
		return $r['nama'];
	}	

	function get_nama_rekening($kode){
		global $db;
		$sql = "SELECT nama FROM rekening WHERE kode = '$kode'";
		$res = $db->query($sql);
		$r = $res->fetchArray(SQLITE3_ASSOC);
		return $r['nama'];
	}
	
	function get_nama_ta($kode){
		global $db;
		$sql = "SELECT tahun, semester FROM ta WHERE kode = '$kode'";
		$res = $db->query($sql);
		$r = $res->fetchArray(SQLITE3_ASSOC);
		return $r['tahun'] . ' - ' . $r['semester'];
	}

}