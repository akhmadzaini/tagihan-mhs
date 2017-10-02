  <?php defined("RESMI") or die('Akses ditolak'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 488px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pembayaran
        <small>tagihan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-money"></i> Pembayaran</a></li>
        <li class="active">tagihan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">        
          <div class="box">
            <div class="box-header">
              <div class="box-title">Pencarian data mahasiswa</div>
            </div>
            <!-- END box-header -->
            <div class="box-body">
              <form action="" id="frm-cari">
                <div class="input-group">
                  <input type="text" name="cari" value="" placeholder="Masukkan nim atau nama mahasiswa yang ingin membayar" class="form-control" required="">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat btn-submit-cari">
                      <i class="fa fa-fw fa-search"></i> Cari
                    </button>
                  </span>
                </div>

              </form>
            </div>
            <!-- END box-body -->
          </div>
          <!-- END box -->
        </div>
      </div>
      <!-- END row -->
      <div id="console"></div>
      <div class="row">

        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <div class="box-title">Data mahasiswa</div>
            </div>
            <!-- END box-header -->
            <div class="box-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Total Tanggungan</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="data-mhs">                  
                </tbody>
              </table>
            </div>
            <!-- END box-body -->
            <div class="loading-mhs overlay" style="display: none;">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
          </div>
          <!-- END box -->
        </div>
        <!-- END col -->
      
      </div>
      <!-- END row -->

    </section>
    <!-- /.content -->

<div class="modal fade" id="modal-pembayaran">
  <div class="modal-dialog modal-lg">
    <form id="frm-bayar">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Pembayaran Tagihan (<span id="nama-pembayaran"></span>)</h4>
      </div>
      <div class="modal-body">
        <i class="fa fa-refresh fa-spin loading" style="display: none;"></i>
        <div id="konten-pembayaran"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-flat btn-info btn-bayar">Bayarkan tagihan</button>
      </div>
    </div>
    </form>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modal-riwayat-pembayaran">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Riwayat Pembayaran (<span id="nama-riwayat-pembayaran"></span>)</h4>
      </div>
      <div class="modal-body">
        <i class="fa fa-refresh fa-spin loading" style="display: none;"></i>
        <div id="konten-riwayat-pembayaran"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Tutup</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script>

$(document).ready(function(){

  $('#frm-cari').submit(function(e){
    e.preventDefault();
    $('.btn-submit-cari').click();    
  });

  $('.btn-submit-cari').click(function(){
    $('.loading-mhs').show();
    var data = $('#frm-cari').serialize();
    $.post('post.php?mod=pembayaran&hal=index_ajax_data_mhs', data, function(hasil){
      $('#data-mhs').html(hasil);
      $('.loading-mhs').hide();
    });
  });

  $('#modal-pembayaran').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    if(button.data('nim') !== undefined){      
      $('#konten-pembayaran').html('');
      $('.loading').show();
      var nim = button.data('nim');
      var nama = button.data('nama');
      $('#nama-pembayaran').html(nama + ' - ' + nim);
      $.post('post.php?mod=pembayaran&hal=index_ajax_pembayaran', {nim: button.data('nim')}, function(hasil){
        $('#konten-pembayaran').html(hasil);
        $('.loading').hide();
      });
    }
  });

  $('.btn-bayar').click(function(){
    $('.loading').show();
    $('.uang').unmask();
    var data = $('#frm-bayar').serialize();
    $.post('post.php?mod=pembayaran&hal=index_ajax_submit_bayar', data, function(hasil){
      //$('#console').html(hasil);
      $('.uang').mask('000.000.000.000.000', {reverse: true});
      $('.loading').hide();
      $('#frm-cari').submit();
      bootbox.alert({
        title: "Konfirmasi pembayaran",
        message: "Data telah tersimpan"
      });
    });
  });

  $('#modal-riwayat-pembayaran').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    if(button.data('nim') !== undefined){      
      $('#konten-riwayat-pembayaran').html('');
      $('.loading').show();
      var nim = button.data('nim');
      var nama = button.data('nama');
      $('#nama-riwayat-pembayaran').html(nama + ' - ' + nim);
      $.post('post.php?mod=pembayaran&hal=index_ajax_riwayat_pembayaran', {nim: button.data('nim')}, function(hasil){
        $('#konten-riwayat-pembayaran').html(hasil);
        $('.loading').hide();

        // update js untuk tombol hapus
        $('.btn-hapus').click(function(){

          var tombol = $(this);          
          var id = $(this).data('id');
          bootbox.confirm({
            title: 'Konfirmasi penghapusan',
            message: 'Tindakan ini tidak dapat dikembalikan lagi, anda yakin ingin menghapus pembayaran ?',
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
                $('.loading').show();
                $.post('post.php?mod=pembayaran&hal=index_ajax_delete_bayar', {id: id}, function(hasil){
                  //$('#console').html(hasil);
                  bootbox.alert({
                    title: 'Konfirmasi penghapusan',
                    message: 'Data telah terhapus',
                  });
                  $('.loading').hide();
                  $('#frm-cari').submit();
                  tombol.closest('tr').remove();
                });
              }
            }
          });

        });  

      });
    }
  });

});

</script>