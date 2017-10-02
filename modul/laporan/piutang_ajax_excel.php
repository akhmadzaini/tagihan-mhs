<?php
defined("RESMI") or die('Akses ditolak');

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');

$prodi = sanitasi($_POST['prodi']);

$sql = "SELECT  b.angkatan AS angkatan, c.nama AS rekening, SUM(a.tagihan) AS tagihan,
            SUM(a.terbayar) AS terbayar, SUM(a.keringanan) AS keringanan
            FROM vw_tagihan a
            LEFT JOIN mhs b ON a.mhs_nim = b.nim
            LEFT JOIN rekening c ON a.rekening_kode = c.kode
            WHERE b.prodi_kode = '$prodi'
            GROUP BY b.angkatan, a.rekening_kode
            ORDER BY b.angkatan, c.nama";

$res = $db->query($sql);
$n = 1;

/** Include PHPExcel */
require_once('vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
require_once('db_helper.php');

// persiapan data header laporan
$prodi  = db_helper::get_nama_prodi($prodi);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Aplikasi Keuangan")
							 ->setLastModifiedBy("Aplikasi Keuangan")
							 ->setTitle("Laporan Piutang")
							 ->setSubject("Laporan piutang excel")
							 ->setDescription("Dokumen ini berisi laporan piutang mahasiswa.")
							 ->setKeywords("laporan piutang mahasiswa")
							 ->setCategory("laporan piutang");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'LAPORAN PIUTANG MAHASISWA')
            ->setCellValue('A3', 'PROGRAM STUDI ' . $prodi)
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'ANGKATAN')
            ->setCellValue('C5', 'URAIAN')
            ->setCellValue('D5', 'TAGIHAN')
            ->setCellValue('E5', 'TERBAYAR')
            ->setCellValue('F5', 'KERINGANAN')
            ->setCellValue('G5', 'PIUTANG');

$n = 6;

while($r = $res->fetchArray(SQLITE3_ASSOC)){
      $sisa = $r['tagihan'] - ($r['terbayar'] + $r['keringanan']);
      if($sisa > 0){
      	$objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A' . $n, $n - 5)
                  ->setCellValue('B' . $n, $r['angkatan'])
                  ->setCellValue('C' . $n, $r['rekening'])
                  ->setCellValue('D' . $n, $r['tagihan'])
                  ->setCellValue('E' . $n, $r['terbayar'])
                  ->setCellValue('F' . $n, $r['keringanan'])
                  ->setCellValue('G' . $n, '=D' . $n . '-(E' . $n .'+F' . $n .')');
      }
      $n++;
}

// Hitung total
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $n, '=SUM(D6:D'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $n, '=SUM(E6:E'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $n, '=SUM(F6:F'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $n, '=SUM(G6:G'. ($n-1) .')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $n, 'TOTAL');

// format font
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:G5")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $n . ":G" . $n)->getFont()->setBold(true);

// auto width
foreach(range('B','G') as $columnID) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($columnID)->setAutoSize(true);
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Piutang Mahasiswa');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('unduhan/laporan_piutang.xlsx');
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;