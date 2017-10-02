<?php
defined("RESMI") or die('Akses ditolak');

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
while($r = $res->fetchArray(SQLITE3_ASSOC)): 
	$sisa = $r['nominal'] - ( $r['terbayar'] + $r['potongan']);
	$body_tabel .= "
	<tr>
		<td>" . $no++ . "</td>
		<td>$r[nama]</td>
		<td>$r[nim]</td>
		<td>$r[skema]</td>
		<td>$r[angkatan]</td>
		<td>" .format_rp($r['nominal']) ."</td>
		<td>" .format_rp($r['terbayar']) ."</td>
		<td>" .format_rp($r['potongan']) ."</td>
		<td>" .format_rp($sisa) ."</td>
	</tr>
	";
	$total_tagihan += $r['nominal'];
	$total_terbayar += $r['terbayar'];
	$total_keringanan += $r['potongan'];
endwhile ?>

<div class="row">

	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
	    <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i></span>

	    <div class="info-box-content">
	      <span class="info-box-text">Total Tagihan</span>
	      <span class="info-box-number"><?=format_rp($total_tagihan)?></span>
	    </div>
	    <!-- /.info-box-content -->
	  </div>
	  <!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
	    <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>

	    <div class="info-box-content">
	      <span class="info-box-text">Terbayar</span>
	      <span class="info-box-number"><?=format_rp($total_terbayar)?></span>
	    </div>
	    <!-- /.info-box-content -->
	  </div>
	  <!-- /.info-box -->
	</div>
	<!-- /.col -->

	<!-- fix for small devices only -->
	<div class="clearfix visible-sm-block"></div>

	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
	    <span class="info-box-icon bg-green"><i class="fa fa-cut"></i></span>

	    <div class="info-box-content">
	      <span class="info-box-text">Keringanan</span>
	      <span class="info-box-number"><?=format_rp($total_keringanan)?></span>
	    </div>
	    <!-- /.info-box-content -->
	  </div>
	  <!-- /.info-box -->
	</div>
	<!-- /.col -->
	
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
		    <span class="info-box-icon bg-yellow"><i class="fa fa-bank"></i></span>

		    <div class="info-box-content">
		      <span class="info-box-text">Piutang</span>
		      <span class="info-box-number"><?=format_rp($total_tagihan - ($total_keringanan + $total_terbayar))?></span>
		    </div>
		    <!-- /.info-box-content -->
		</div>
	</div>

</div>

<div class="row">
	<div class="col-xs-12">
	  <div class="box">
	    <div class="box-header">
	      <h3 class="box-title">Rincian Data</h3>
	    </div>
	    <div class="box-body">

			<table class="table tbl-tagihan">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>NIM</th>
						<th>Kelas</th>
						<th>Angkatan</th>
						<th>Tagihan</th>
						<th>Terbayar</th>
						<th>Keringanan</th>
						<th>Sisa</th>
					</tr>
				</thead>
				<tbody>
					<?=$body_tabel?>
				</tbody>
			</table>
			
		</div>
	  </div>
	</div>
	<!--End of col-->
</div>
<!--End of row-->


<script>
	$(document).ready(function(){
		$('.tbl-tagihan').DataTable();
	});
</script>