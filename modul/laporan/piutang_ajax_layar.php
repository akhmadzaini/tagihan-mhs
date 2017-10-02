<?php
defined("RESMI") or die('Akses ditolak');

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
$body_table = "";

$total_tagihan = 0;
$total_terbayar = 0;
$total_keringanan = 0;
$total_sisa = 0;
while($r = $res->fetchArray(SQLITE3_ASSOC)):
	$sisa = $r['tagihan'] - ($r['terbayar'] + $r['keringanan']);
	if($sisa > 0){	
		$total_tagihan += $r['tagihan'];
		$total_terbayar += $r['terbayar'];
		$total_keringanan += $r['keringanan'];
		$total_sisa += $sisa;
		$body_table .= " 
		<tr>
			<td>" . $n++ . "</td>
			<td>$r[angkatan]</td>
			<td>$r[rekening]</td>
			<td>" . format_rp($r['tagihan']) ."</td>
			<td>" . format_rp($r['terbayar']) ."</td>
			<td>" . format_rp($r['keringanan']) ."</td>
			<td>" . format_rp($sisa) . "</td>
		</tr>
		";
	}
endwhile

?>

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

			<table class="table tbl-layar">
				<thead>
					<tr>
						<th>No</th>
						<th>Angkatan</th>
						<th>Uraian</th>
						<th>Tagihan</th>
						<th>Terbayar</th>
						<th>Keringanan</th>
						<th>Sisa</th>
					</tr>
				</thead>
				<tbody>
					<?=$body_table?>
				</tbody>
			</table>

		</div>
	  </div>
	</div>
	
</div>



<script>
	$(document).ready(function(){
		$('.tbl-layar').DataTable();
	});
</script>