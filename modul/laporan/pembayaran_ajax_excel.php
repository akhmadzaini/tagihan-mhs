<?php
defined("RESMI") or die('Akses ditolak');

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');

/** Persiapan data */
$prodi = sanitasi($_POST['prodi']);
$angkatan = sanitasi($_POST['angkatan']);
$no_keringanan = sanitasi(@$_POST['no_keringanan']);
$tgl_mulai = tgl_database(sanitasi($_POST['tgl_mulai']));
$tgl_akhir = tgl_database(sanitasi($_POST['tgl_akhir']));

$add_sql = array(" date(a.tgl) >= '$tgl_mulai' ");
$add_sql[] = (" date(a.tgl) <= '$tgl_akhir' ");

if($prodi != ''){
	$add_sql[] = " b.prodi_kode = '$prodi' ";
}
if($angkatan != ''){
	$add_sql[] = " b.angkatan = '$angkatan' ";
}
if($no_keringanan != ''){
	$add_sql[] = " a.potongan = 0 ";
}
$add_sql = implode('AND', $add_sql);

$sql = "SELECT a.tgl, b.nama, b.skema, c.nama AS prodi, 
	b.angkatan, d.nama AS uraian, a.ta_kode, a.nominal
	FROM mhs_pembayaran a
	LEFT JOIN mhs b ON a.mhs_nim = b.nim
	LEFT JOIN prodi c ON b.prodi_kode = c.kode
	LEFT JOIN rekening d ON a.rekening_kode = d.kode
	WHERE $add_sql ORDER BY a.mhs_nim, d.nama";

$res = $db->query($sql);

/** Include PHPExcel */
require_once('vendor/phpoffice/phpexcel/Classes/PHPExcel.php');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Aplikasi Keuangan")
							 ->setLastModifiedBy("Aplikasi Keuangan")
							 ->setTitle("Laporan Pembayaran")
							 ->setSubject("Laporan pembayaran excel")
							 ->setDescription("Dokumen ini berisi laporan pembayaran mahasiswa.")
							 ->setKeywords("laporan pembayaran mahasiswa")
							 ->setCategory("laporan pembayaran");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'LAPORAN KEUANGAN MAHASISWA')
            ->setCellValue('A3', tgl_teks(sanitasi($_POST['tgl_mulai'])) . ' s/d ' . tgl_teks(sanitasi($_POST['tgl_akhir'])))
            ->setCellValue('A5', 'TANGGAL')
            ->setCellValue('B5', 'NAMA')
            ->setCellValue('C5', 'KELAS')
            ->setCellValue('D5', 'PRODI')
            ->setCellValue('E5', 'ANGKATAN')
            ->setCellValue('F5', 'URAIAN')
            ->setCellValue('G5', 'NOMINAL');

$n = 6;

while($r = $res->fetchArray(SQLITE3_ASSOC)){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $n, tgl_aplikasi($r['tgl']))
            ->setCellValue('B' . $n, $r['nama'])
            ->setCellValue('C' . $n, $r['skema'])
            ->setCellValue('D' . $n, $r['prodi'])
            ->setCellValue('E' . $n, $r['angkatan'])
            ->setCellValue('F' . $n, $r['uraian'])
            ->setCellValue('G' . $n, $r['nominal']);
    $n++;
}

// Hitung total
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $n, '=SUM(G6:G'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $n, 'TOTAL');

// format font
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:G5")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $n . ":I" . $n)->getFont()->setBold(true);

// auto width
foreach(range('B','G') as $columnID) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($columnID)->setAutoSize(true);
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Pembayaran Mahasiswa');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('unduhan/laporan_pembayaran.xlsx');
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;