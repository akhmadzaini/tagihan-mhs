  <?php defined("RESMI") or die('Akses ditolak'); 

  $prodi = sanitasi(@$_GET['prodi']);
  $angkatan = sanitasi(@$_GET['angkatan']);
  $ta = sanitasi(@$_GET['ta']);
  $skema = sanitasi(@$_GET['skema']);

  if(isset($_GET['prodi']) && isset($_GET['angkatan']) && isset($_GET['ta']) && isset($_GET['skema'])){
    $sql = "SELECT b.nama, a.nominal
            FROM tagihan a 
            LEFT JOIN rekening b ON a.rekening_kode = b.kode
            WHERE a.prodi_kode = '$prodi' AND a.angkatan = '$angkatan' AND a.ta_kode = '$ta' AND a.skema='$skema'
            ORDER BY a.rekening_kode";
    $res_tagihan = $db->query($sql);

    $sql = "SELECT a.kode,a.nama, IFNULL(b.nominal, 0) AS nominal 
            FROM rekening a LEFT JOIN tagihan b 
            ON a.kode = b.rekening_kode 
            AND b.prodi_kode = '$prodi'
            AND b.angkatan =  '$angkatan'
            AND b.ta_kode = '$ta'
            AND b.skema = '$skema'
            ORDER BY a.kode";
    $res_rek = $db->query($sql);
  }


  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 488px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tagihan
        <small>Atur</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-cubes"></i> Tagihan</a></li>
        <li class="active">atur</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Filter Data</h3>
            </div>
            <div class="box-body">
              <form action="" method="post" id="frm-filter">
                <input type="hidden" value="tagihan" name="mod">
                <input type="hidden" value="atur" name="hal">
                <div class="form-group">
                  <label for="">Program Studi</label>
                  <?=combo_prodi($prodi)?>
                </div>
                <div class="form-group">
                  <label for="">Angkatan</label>
                  <?=combo_angkatan($angkatan)?>
                </div>
                <div class="form-group">
                  <label for="">Tahun Akademik</label>
                  <?=combo_ta($ta)?>
                </div>
                <div class="form-group">
                  <label for="">Skema Pembiayaan</label>
                  <div id="combo_skema"><em>Belum tersedia</em></div>
                </div>
                <input type="submit" value="Lihat data" class="btn btn-flat btn-info btn-xl">            
              </form>
            </div>

            <div class="loading overlay" style="display: none;">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
            
          </div>
        </div>
      </div>
      <!-- END row -->

      <div class="row">
        <div id="detail_tagihan"></div>
      </div>

    <?php if(isset($_GET['prodi']) && isset($_GET['angkatan']) && isset($_GET['ta']) && isset($_GET['skema'])): ?>
    <div class="row">
      
      <div class="col-xs-6">
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
            <form action="post.php?mod=tagihan&hal=submit_tagihan" class="form-horizontal" method="post">
              <input type="hidden" name="prodi" value="<?=$prodi?>">
              <input type="hidden" name="angkatan" value="<?=$angkatan?>">
              <input type="hidden" name="ta" value="<?=$ta?>">
              <input type="hidden" name="skema" value="<?=$skema?>">
              <?php while($r = $res_rek->fetchArray(SQLITE3_ASSOC)): ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?=$r['nama']?></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control txt-mask" value="<?=$r['nominal']?>" name="rek[<?=$r['kode']?>]">
                  </div>
                </div>                
              <?php endwhile ?>
              <button class="btn btn-info btn-flat btn-atur-tagihan">
                <i class="fa fa-refresh" aria-hidden="true"></i> Perbarui tagihan
              </button>
            </form>
          </div>

          <div class="loading overlay" style="display: none;">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

        </div>
      </div>

      <div class="col-xs-6">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Rincian Tagihan</h3>
          </div>
          <div class="box-body"> 

            <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-info"></i> Perhatikan !</h4>
                <ol>
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

            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Rekening</th>
                  <th>Nominal</th>
                </tr>
              </thead>
              <tbody>
                <?php while($r = $res_tagihan->fetchArray(SQLITE3_ASSOC)): ?>
                  <tr>
                    <td><?=$r['nama']?></td>
                    <td><?=format_rp($r['nominal'])?></td>
                  </tr>
                <?php endwhile ?>
              </tbody>
            </table>
            <button class="btn btn-info btn-flat btn-terap-tagihan">
              <i class="fa fa-rocket" aria-hidden="true"></i> Terapkan tagihan ke mahasiswa
            </button>  

          </div>

          <div class="loading overlay" style="display: none;">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

        </div>
      </div>

  <?php endif ?>

    </section>
    <!-- /.content -->

<script>
  $(document).ready(function(){

    $('.txt_mask').mask('000.000.000.000.000', {reverse: true});

    // jquery lama
    $('.btn-atur-tagihan').click(function(e){
      var form = $(this).parent();
      e.preventDefault();
      bootbox.confirm({
        title: "Konfirmasi pengaturan tagihan",
        message: "Data akan dimasukkan pada tagihan ?",
        buttons: {
            confirm: {
                label: 'Ya',
                className: 'btn-info btn-flat'
            },
            cancel: {
                label: 'Tidak',
                className: 'btn-danger btn-flat'
            },
        },
        callback: function (result) {
          if(result == true){
            form.submit();
          }
        }
      }); 
    });
    
    $('.btn-terap-tagihan').click(function(e){
      $('.loading').show();
      var form = $(this).parent();
      e.preventDefault();
      bootbox.confirm({
        title: "Konfirmasi penerapan tagihan",
        message: "Tindakan ini akan menimpa data sebelumnya jika tagihan sudah pernah diterapkan sebelumnya, anda yakin ingin melanjutkan ?",
        buttons: {
            confirm: {
                label: 'Ya',
                className: 'btn-info btn-flat'
            },
            cancel: {
                label: 'Tidak',
                className: 'btn-danger btn-flat'
            },
        },
        callback: function (result) {
          if(result == true){
            var data = {
              prodi_kode : "<?=$prodi?>",
              angkatan : "<?=$angkatan?>",
              ta_kode : "<?=$ta?>",
              skema : "<?=$skema?>",
            }
            $.post('post.php?mod=tagihan&hal=ajax_terap_tagihan', data, function(hasil){
              bootbox.alert({
                title: "Konfirmasi penerapan tagihan",
                message: hasil,
              });
              $('.loading').hide();
            });
          }else{
            $('.loading').hide(); 
          }
        }
      }); 
    });

    var tombol_ta = $('#frm-filter').find('select[name="angkatan"]');
    $(tombol_ta).change(function(){
      var data = {
        mod: 'umum',
        hal: 'ajax_combo_skema',
        prodi: $('#frm-filter').find('select[name="prodi"]').val(),
        angkatan: $(this).val(),
        skema: '<?=$skema?>',
      }
      $.get('post.php', data, function(hasil){
        $('#combo_skema').html(hasil);
      })
    });

    <?php if(isset($_GET['prodi']) && isset($_GET['angkatan']) && isset($_GET['ta'])): ?>
      $(tombol_ta).change();
    <?php endif?>

  });
</script>