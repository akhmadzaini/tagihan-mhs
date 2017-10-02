<?php 
defined("RESMI") or die('Akses ditolak'); 

$nim = sanitasi($_GET['nim']);
$ta_kode = sanitasi($_GET['ta']);

$sql = "SELECT a.kode, a.nama AS rekening, IFNULL(b.nominal, 0) AS nominal
		FROM rekening a
		LEFT JOIN mhs_tagihan b ON a.kode = b.rekening_kode 
		AND b.ta_kode='$ta_kode'
		AND b.mhs_nim='$nim'";
$res = $db->query($sql);
$n = 1;
?>
<form action="" id="frm-rincian-tagihan" method="post">
	<input type="hidden" name="nim" value="<?=$nim?>">
	<input type="hidden" name="ta" value="<?=$ta_kode?>">
	<table class="table">
	<thead>
	  <tr>
	    <th>#</th>
	    <th>Rekening</th>
	    <th>Nominal</th>
	  </tr>
	</thead>
	<tbody>
		<?php while($r = $res->fetchArray(SQLITE3_ASSOC)):?>
		<tr>
			<td><?=$n++?></td>
			<td><?=$r['rekening']?></td>
			<td><input type="text" value="<?=$r['nominal']?>" name="rekening[<?=$r['kode']?>]" class="txt_mask"></td>
		</tr>
		<?php endwhile?>
	</tbody>  
	</table>
	<button class="btn btn-flat btn-info btn-simpan-tagihan-mhs" >Simpan</button>
</form>
<script>
	$(document).ready(function(){
		$('.txt_mask').mask('000.000.000.000.000', {reverse: true});

		$('.btn-simpan-tagihan-mhs').click(function(e){

			e.preventDefault();
			$('.load-rincian').show();

			bootbox.confirm({
				title: 'Konfirmasi penyimpanan',
				message: 'Tindakan ini tidak dapat dikembalikan, data tagihan akan disimpan ?',
				buttons: {
				            confirm: {
				                label: 'Ya',
				                className: 'btn-info btn-flat'
				            },
				            cancel: {
				                label: 'Tidak',
				                className: 'btn-danger btn-flat'
				            }
						 },
				callback: function (result) {
					if(result == true){
						$('.txt_mask').unmask();
						var data = $('#frm-rincian-tagihan').serialize();
						$.post('post.php?mod=tagihan&hal=ajax_submit_tagihan_individu', data, function(hasil){
							$('.btn-data-mhs').click();
							$('.load-rincian').hide();
						});
					}else{						
						$('.load-rincian').hide();
					}

				}
			});

			
		});
	});
</script>