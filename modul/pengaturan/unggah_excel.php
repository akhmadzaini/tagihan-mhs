<?php 
defined("RESMI") or die('Akses ditolak'); 

require 'vendor/autoload.php';

if(isset($_FILES["fileToUpload"])){

	$sql = "SELECT kode FROM prodi";
	$res = $db->query($sql);
	$kode_prodi_resmi = array();
	while($r = $res->fetchArray(SQLITE3_ASSOC)){
		$kode_prodi_resmi[] = $r['kode'];
	}

	$target_file = __DIR__ . "/tmp.xlsx";
	if(isset($_POST)) {
	    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
	    // Membaca file excel
	    $pembaca = new PHPExcel_Reader_Excel2007();
		$pembaca->setReadDataOnly(true);
		$objExcel = $pembaca->load($target_file);

		$n = 3;
		$nsukses = 0;
		$sql = array();
		while(TRUE){
			$nim 		= sanitasi($objExcel->getActiveSheet()->getCell('C' . $n)->getValue());
			$nama 		= sanitasi($objExcel->getActiveSheet()->getCell('D' . $n)->getValue());
			$kode_prodi = sanitasi($objExcel->getActiveSheet()->getCell('E' . $n)->getValue());
			$angkatan 	= sanitasi($objExcel->getActiveSheet()->getCell('F' . $n)->getValue());
			$skema	 	= sanitasi($objExcel->getActiveSheet()->getCell('G' . $n)->getValue());

			if($nim == NULL && $nama == NULL && $kode_prodi == NULL && 
			$angkatan == NULL && $skema == NULL){break;} // jika baris sudah null semua hentikan loop

			if(in_array($kode_prodi, $kode_prodi_resmi)){
				if($nim != NULL || $nama != NULL ||	$angkatan != NULL || $skema != NULL){
					$sql[] = "INSERT OR REPLACE INTO mhs
					        (nim, nama, prodi_kode, angkatan, skema) 
					        VALUES('$nim', '$nama', '$kode_prodi', '$angkatan', '$skema')";
					//$db->exec($sql);
					$nsukses++;
				}
			}
			$n++;
		}
		if(!empty($sql)){
			$sql =implode(';', $sql);
			$db->exec($sql);
		}
	}

}

#echo "Proses import excel telah selesai, $nsukses data berhasil diimport";
header("location: index.php?mod=pengaturan&hal=mhs&msg=sukses_import");