<?php
defined("RESMI") or die('Akses ditolak');

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
?>
<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Rincian Data</h3>
    </div>
    <div class="box-body">

		<table class="table tbl-layar">
			<thead>
				<tr>
					<th>Tgl</th>
					<th>Nama</th>
					<th>Kelas</th>
					<th>Prodi</th>
					<th>Angkatan</th>
					<th>Uraian</th>
					<th>Nominal</th>
				</tr>
			</thead>
			<tbody>
				<?php while($r = $res->fetchArray(SQLITE3_ASSOC)): ?>
					<tr>
						<td><?=tgl_aplikasi($r['tgl'])?></td>
						<td><?=$r['nama']?></td>
						<td><?=$r['skema']?></td>
						<td><?=$r['prodi']?></td>
						<td><?=$r['angkatan']?></td>
						<td><?=$r['uraian']?></td>
						<td><?=$r['nominal']?></td>
					</tr>
				<?php endwhile ?>
			</tbody>
		</table>

	</div>
  </div>
</div>

<script>
	$(document).ready(function(){
		$('.tbl-layar').DataTable();
	});
</script>