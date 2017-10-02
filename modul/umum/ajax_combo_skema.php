<?php 
defined("RESMI") or die('Akses ditolak'); 

$prodi = sanitasi(@$_GET['prodi']);
$angkatan = sanitasi(@$_GET['angkatan']);


$sql = "SELECT DISTINCT skema FROM mhs 
		WHERE prodi_kode = '$prodi' AND angkatan='$angkatan' 
		ORDER BY skema";
$res = $db->query($sql);
?>
<select name="skema" id="" class="form-control">
	<option value="">-- Pilih Skema Pembiayaan --</option>
	<?php while($r=$res->fetchArray(SQLITE3_ASSOC)): ?>
		<option value="<?=$r['skema']?>"><?=$r['skema']?></option>
	<?php endwhile ?>
</select>