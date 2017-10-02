<?php 
defined("RESMI") or die('Akses ditolak');

$nim = sanitasi($_POST['nim']);

$sql = "SELECT a.id, a.mhs_nim, a.ta_kode, a.rekening_kode, a.tgl, a.potongan, 
        a.nominal, c.tahun, c.semester, d.nama AS rekening
        FROM mhs_pembayaran a
        LEFT JOIN ta c ON a.ta_kode = c.kode
        LEFT JOIN rekening d ON a.rekening_kode = d.kode
        WHERE a.mhs_nim = '$nim'
        ORDER BY a.ta_kode, a.rekening_kode, a.tgl DESC";

$res = $db->query($sql);
$n = 1;
?>
<input type="hidden" name="nim" value="<?=$nim?>">
<table class="table">
    <!-- /.input group -->
  </div>
  <thead>
    <thead>
      <tr>
        <th>#</th>
        <th>Tahun Akademik</th>
        <th>Rekening</th>
        <th>Tgl. Bayar</th>
        <th>Jumlah dibayar</th>
        <th>Keringanan</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php while($r = $res->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
          <td><?=$n++?></td>
          <td><?=$r['tahun']?> - <?=$r['semester']?></td>
          <td><?=$r['rekening']?></td>
          <td><?=tgl_aplikasi($r['tgl'])?></td>
          <td><?=format_rp($r['nominal'])?></td>
          <td><?=label_potongan($r['potongan'])?></td>
          <td>
            <button class="btn btn-flat btn-default btn-hapus" data-id="<?=$r['id']?>"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      <?php endwhile ?>
    </tbody>
  </thead>
</table>

<script>
  /*$(document).ready(function(){
    $('.btn-hapus').click(function(){
      alert('hapus');
    });
  });*/
</script>