<?php
defined("RESMI") or die('Akses ditolak');

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');

$prodi = sanitasi($_POST['prodi']);
$ta = sanitasi($_POST['ta']);
$rekening = sanitasi($_POST['rekening']);

$sql = "SELECT a.nim, a.nama, a.angkatan, a.skema, 
	b.ta_kode, c.nama AS uraian, b.nominal,
	IFNULL(d.total, 0) AS terbayar,
	IFNULL(e.total, 0) AS potongan
	FROM mhs a
	LEFT JOIN mhs_tagihan b ON a.nim = b.mhs_nim
	LEFT JOIN rekening c ON b.rekening_kode = c.kode
	LEFT JOIN vw_pembayaran_murni d ON b.mhs_nim = d.mhs_nim 
	AND b.ta_kode = d.ta_kode AND b.rekening_kode = d.rekening_kode
	LEFT JOIN vw_pembayaran_potongan e ON b.mhs_nim = e.mhs_nim 
	AND b.ta_kode = e.ta_kode AND b.rekening_kode = e.rekening_kode
	WHERE b.ta_kode = '$ta' AND a.prodi_kode = '$prodi' AND b.rekening_kode = '$rekening'";

$res = $db->query($sql);
$no = 1;
$body_tabel = "";
$total_tagihan = 0;
$total_terbayar = 0;
$total_keringanan = 0;

/** Include PHPExcel */
require_once('vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
require_once('db_helper.php');

// persiapan data header laporan
$rekening  = db_helper::get_nama_rekening($rekening);
$ta  = db_helper::get_nama_ta($ta);
$prodi  = db_helper::get_nama_prodi($prodi);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Aplikasi Keuangan")
							 ->setLastModifiedBy("Aplikasi Keuangan")
							 ->setTitle("Laporan Tagihan")
							 ->setSubject("Laporan tagihan excel")
							 ->setDescription("Dokumen ini berisi laporan tagihan mahasiswa.")
							 ->setKeywords("laporan tagihan mahasiswa")
							 ->setCategory("laporan tagihan");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'LAPORAN TAGIHAN ' . $rekening)
            ->setCellValue('A3', 'TAHUN AKADEMIK ' . $ta)
            ->setCellValue('A4', 'PROGRAM STUDI ' . $prodi)
            ->setCellValue('A7', 'No.')
            ->setCellValue('B7', 'NAMA')
            ->setCellValue('C7', 'NIM')
            ->setCellValue('D7', 'KELAS')
            ->setCellValue('E7', 'ANGKATAN')
            ->setCellValue('F7', 'TAGIHAN')
            ->setCellValue('G7', 'TERBAYAR')
            ->setCellValue('H7', 'KERINGANAN')
            ->setCellValue('I7', 'SISA');

$n = 8;

while($r = $res->fetchArray(SQLITE3_ASSOC)){
	$sisa = $r['nominal'] - ( $r['terbayar'] + $r['potongan']);
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $n, $n - 7)
            ->setCellValue('B' . $n, $r['nama'])
            ->setCellValue('C' . $n, $r['nim'])
            ->setCellValue('D' . $n, $r['skema'])
            ->setCellValue('E' . $n, $r['angkatan'])
            ->setCellValue('F' . $n, $r['nominal'])
            ->setCellValue('G' . $n, $r['terbayar'])
            ->setCellValue('H' . $n, $r['potongan'])
            ->setCellValue('I' . $n, '=F' . $n . '-(G' . $n .'+H' . $n .')');
    $n++;
}

// Hitung total
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $n, '=SUM(F8:F'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $n, '=SUM(G8:G'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $n, '=SUM(H8:H'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $n, '=SUM(I8:I'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $n, 'TOTAL');

// format font
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:A4")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A7:I7")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $n . ":I" . $n)->getFont()->setBold(true);

// auto width
foreach(range('B','I') as $columnID) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($columnID)->setAutoSize(true);
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Tagihan Mahasiswa');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('unduhan/laporan_tagihan.xlsx');
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;