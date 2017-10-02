<?php 
defined("RESMI") or die('Akses ditolak');

$cari = sanitasi($_POST['cari']);

$sql = "SELECT a.nim, a.nama, b.nama AS prodi, 
        IFNULL(c.total, 0) - IFNULL(d.total,0) AS tanggungan 
        FROM mhs a 
        LEFT JOIN prodi b ON a.prodi_kode = b.kode 
        LEFT JOIN vw_tagihan_total c ON a.nim = c.mhs_nim
        LEFT JOIN vw_pembayaran_total d ON a.nim = d.mhs_nim
        WHERE a.nim LIKE '%$cari%' OR a.nama LIKE '%$cari%'";
$res = $db->query($sql);
$n = 1;
?>

<?php while($r = $res->fetchArray(SQLITE3_ASSOC)): ?>
  <?php $data = 'data-nim="'. $r['nim'] .'" data-nama="'. $r['nama'] .'"'?>
  <tr>
    <td><?=$n++?></td>
    <td><?=$r['nim']?></td>
    <td><?=$r['nama']?></td>
    <td><?=$r['prodi']?></td>
    <td><?=format_rp($r['tanggungan'])?></td>
    <td>
      <div class="btn-group">
        <a href="#" class="btn btn-flat btn-default" data-toggle="modal" data-target="#modal-pembayaran" <?=$data?> ><i class="fa fa-money"></i></a>
        <a href="#" class="btn btn-flat btn-default" data-toggle="modal" data-target="#modal-riwayat-pembayaran" <?=$data?> ><i class="fa fa-history"></i></a>
      </div>
    </td>
  </tr>
<?php endwhile ?>