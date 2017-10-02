  <?php defined("RESMI") or die('Akses ditolak'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 488px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Laporan
        <small>Pembayaran</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-line-chart"></i> Laporan</a></li>
        <li class="active">pembayaran</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <div class="row">

        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Filter Data</h3>
            </div>
            <div class="box-body">
              <form action="" method="get" id="frm-filter">
                <input type="hidden" value="tagihan" name="mod">
                <input type="hidden" value="atur" name="hal">
                <div class="form-group">
                  <label for="">Program Studi</label>
                  <?=combo_prodi('', '')?>
                </div>
                <div class="form-group">
                  <label for="">Angkatan</label>
                  <?=combo_angkatan('', '')?>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                    <label>
                      <input type="checkbox" name="no_keringanan" checked=""> Tanpa Keringanan
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="">Tanggal mulai</label>
                    <input type="text" class="form-control tgl" name="tgl_mulai">
                </div>
                <div class="form-group">
                  <label for="">Tanggal akhir</label>
                    <input type="text" class="form-control tgl" name="tgl_akhir">
                </div>
                <input type="submit" value="Cetak di layar" class="btn btn-flat btn-info btn-xl">            
                <button class="btn btn-flat btn-info btn-xl btn-excel">Cetak di excel</button>            
              </form>
            </div>

            <div class="loading-filter overlay" style="display: none;">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
            
          </div>
        </div>
      </div>

      <div class="row laporan_layar">
        
      </div>


    </section>
    <!-- /.content -->


<script>
  $(document).ready(function(){

    $('.tgl').mask('00/00/0000');
    $('.tgl').val('<?php echo tgl_sekarang() ?>');
    $('.uang').mask('000.000.000.000.000', {reverse: true});

    $('#frm-filter').submit(function(e){
      e.preventDefault();
      $('.loading-filter').show();
      var data = $('#frm-filter').serialize();
      $.post('post.php?mod=laporan&hal=pembayaran_ajax_layar', data, function(hasil){
        $('.laporan_layar').html(hasil);
        $('.loading-filter').hide();
      });
    });

    $('.btn-excel').click(function(e){
      e.preventDefault();
      $('.loading-filter').show();
      var data = $('#frm-filter').serialize();
      $.post('post.php?mod=laporan&hal=pembayaran_ajax_excel', data, function(hasil){
        window.location='unduhan/laporan_pembayaran.xlsx';
        $('.loading-filter').hide();
      });
    });

  })
</script>