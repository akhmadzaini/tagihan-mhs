<?php 
defined("RESMI") or die('Akses ditolak');

$nim = sanitasi($_POST['nim']);

$sql = "SELECT a.mhs_nim, a.ta_kode, a.rekening_kode, 
        a.nominal - IFNULL(b.total, 0) AS tanggungan,
        c.tahun, c.semester, d.nama AS rekening
        FROM mhs_tagihan a
        LEFT JOIN vw_pembayaran b ON a.mhs_nim = b.mhs_nim
        AND a.ta_kode = b.ta_kode
        AND a.rekening_kode = b.rekening_kode
        LEFT JOIN ta c ON a.ta_kode = c.kode
        LEFT JOIN rekening d ON a.rekening_kode = d.kode
        WHERE a.mhs_nim = '$nim'";

$res = $db->query($sql);
$n = 1;
?>
<input type="hidden" name="nim" value="<?=$nim?>">
<div class="form-group">
  <label>Tanggal (dd/mm/yyyy)</label>

  <div class="input-group">
    <input type="text" class="form-control tgl" name="tanggal">
    <div class="input-group-addon">
        <span class="glyphicon glyphicon-th"></span>
    </div>
  </div>

  <!-- /.input group -->
</div>
<table class="table">
  <thead>
    <thead>
      <tr>
        <th>#</th>
        <th>Tahun Akademik</th>
        <th>Rekening</th>
        <th>Tanggungan</th>
        <th>Pembayaran</th>
        <th>Atribut</th>
      </tr>
    </thead>
    <tbody>
      <?php while($r = $res->fetchArray(SQLITE3_ASSOC)): ?>
        <?php $key = $r['ta_kode'] . '-' . $r['rekening_kode'] ?>
        <tr>
          <td><?=$n++?></td>
          <td><?=$r['tahun']?> - <?=$r['semester']?></td>
          <td><?=$r['rekening']?></td>
          <td><?=format_rp($r['tanggungan'])?></td>
          <td>
            <input type="text" class="form-control uang" value="0" name="nominal[<?=$key?>]">
          </td>
          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="atribut[<?=$key?>]" value="1"> Keringanan
              </label>
            </div>
          </td>
        </tr>
      <?php endwhile ?>
    </tbody>
  </thead>
</table>

<script>
  $(document).ready(function(){
    $('.tgl').mask('00/00/0000');
    $('.tgl').val('<?php echo tgl_sekarang() ?>');
    $('.uang').mask('000.000.000.000.000', {reverse: true});
  })
</script>