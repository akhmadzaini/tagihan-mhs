<?php 
defined("RESMI") or die('Akses ditolak'); 

$prodi = sanitasi($_POST['prodi']);
$angkatan = sanitasi($_POST['angkatan']);
$ta = sanitasi($_POST['ta']);
$skema = sanitasi($_POST['skema']);

$sql = "SELECT a.kode,a.nama, IFNULL(b.nominal, 0) AS nominal 
        FROM rekening a LEFT JOIN tagihan b 
        ON a.kode = b.rekening_kode 
        AND b.prodi_kode = '$prodi'
        AND b.angkatan =  '$angkatan'
        AND b.ta_kode = '$ta'
        AND b.skema = '$skema'
        ORDER BY a.kode";
$res_rek = $db->query($sql);

?>
<div class="col-xs-12">
	<div class="box">
	  <div class="box-header">
	    <h3 class="box-title">Rekening Tersedia</h3>
	  </div>
	  <div class="box-body">
	    <div class="alert alert-warning alert-dismissible">
	      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	      <h4><i class="icon fa fa-info"></i> Perhatikan !</h4>
	      <ol>
	        <li>Rekening yang bernilai 0 dianggap tidak ditagihkan pada TA terpilih</li>
	        <li>    
	          Fasilitas ini bersifat massal, disarankan melakukan aktivitas ini <strong>sekali saja pada awal semester</strong>. 
	          Penerapan tagihan lebih dari sekali akan menyebabkan perubahan pada data tagihan mahasiswa
	          yang pernah diterapkan sebelumnya.
	        </li>
	        <li>
	          Gunakan fitur penagihan individu (menu : <strong>Tagihan &gt;&gt; penagihan individu</strong> ), jika ingin mengatur tagihan secara khusus pada mahasiswa tertentu. 
	        </li>
	      </ol>
	    </div>
	    <form action="" class="form-horizontal" method="post" id="frm-tagihan">
	      <input type="hidden" name="prodi" value="<?=$prodi?>">
	      <input type="hidden" name="angkatan" value="<?=$angkatan?>">
	      <input type="hidden" name="ta" value="<?=$ta?>">
	      <input type="hidden" name="skema" value="<?=$skema?>">
	      <table class="table">
	      	<thead>
	      		<tr>
	      			<th>#</th>
	      			<th>Rekening</th>
	      			<th>Nominal</th>
	      		</tr>
	      	</thead>
	      	<tbody>
	      		<?php $n=1?>
	      		<?php while($r = $res_rek->fetchArray(SQLITE3_ASSOC)): ?>
	      			<tr>
	      				<td><?=$n++?></td>
	      				<td><?=$r['nama']?></td>
	      				<td><input type="text" class="form-control txt_mask" value="<?=$r['nominal']?>" name="rek[<?=$r['kode']?>]"></td>
	      			</tr>
	      		<?php endwhile ?>
	      	</tbody>
	      </table>
	            
	      <button class="btn btn-info btn-flat btn-atur-tagihan">
	        <i class="fa fa-rocket" aria-hidden="true"></i> Simpan dan Terapkan tagihan
	      </button>
	    </form>
	  </div>

	  <div class="loading-tagihan overlay" style="display: none;">
	    <i class="fa fa-refresh fa-spin"></i>
	  </div>

	</div>
</div>

<script>
$(document).ready(function(){
	$('.txt_mask').mask('000.000.000.000.000', {reverse: true});

	$('#frm-tagihan').submit(function(e){

		e.preventDefault();
		$('.txt_mask').unmask();
		var data = $(this).serialize();
		bootbox.confirm({
			title: 'Konfirmasi penyimpanan',
			message: 'Tagihan akan diterapkan, tindakan ini berpotensi menumpuki data tagihan yang ada, bila pernah diterapkan sebelumnya! ',
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
				if(result){				
					$('.loading-tagihan').show();
					$.post('post.php?mod=tagihan&hal=atur_ajax_submit_tagihan', data, function(hasil){
						//$('#console').html(hasil);
						bootbox.alert({
							title: 'Konfirmasi penyimpanan',
							message: hasil,
						});
						$('.txt_mask').mask('000.000.000.000.000', {reverse: true});
						$('.loading-tagihan').hide();
					});
				}else{
					$('.txt_mask').mask('000.000.000.000.000', {reverse: true});
				}
			}
		});
		
	});
});
</script>