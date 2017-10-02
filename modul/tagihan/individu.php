  <?php defined("RESMI") or die('Akses ditolak'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 488px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tagihan
        <small>Individu</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-cubes"></i> Tagihan</a></li>
        <li class="active">individu</li>
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
              <form action="" method="get" id="frm-filter">
                <input type="hidden" value="tagihan" name="mod">
                <input type="hidden" value="atur" name="hal">
                <div class="form-group">
                  <label for="">Program Studi</label>
                  <?=combo_prodi()?>
                </div>
                <div class="form-group">
                  <label for="">Angkatan</label>
                  <?=combo_angkatan()?>
                </div>
                <div class="form-group">
                  <label for="">Tahun Akademik</label>
                  <?=combo_ta()?>
                </div>
                <input type="submit" value="Lihat data" class="btn btn-flat btn-info btn-xl btn-data-mhs">            
              </form>
            </div>
            
          </div>
        </div>
      </div>

      <div class="row">
        
        <div class="col-xs-6">

          <div id="console"></div>

          <div class="box">
          <div class="box-header">
            <div class="box-title">Daftar Mahasiswa</div>
          </div>
          <!-- END box-header -->
          <div class="box-body">
            <table class="table" id="tabelku">
              <thead>
                <tr>
                  <th>#</th>
                  <th>NIM</th>
                  <th>Nama</th>
                  <th>Skema</th>
                  <th>Total Tagihan</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
          <!-- END box-body -->
          <div class="load-mhs overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
          <!-- END overlay -->
          </div>
          <!--END box-->     
        </div>
        <!--END col-->

        <div class="col-xs-6">
          <div class="box">
            <div class="box-header">
              <div class="box-title">Rincian Tagihan <span id="detail_mhs"></span></div>
            </div>
            <!-- END box header -->
            <div class="box-body">
              <div id="rincian-tagihan"></div>
            </div>
            <!-- END box-body -->
                      <!-- END box-body -->
            <div class="load-rincian overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!-- END overlay -->
          </div>
          <!-- END box -->
        </div>
        <!-- END col -->

      </div>

    </section>
    <!-- /.content -->

<script>
$(document).ready(function(){

  $('.overlay').hide();

  $('.btn-data-mhs').click(function(e){
    e.preventDefault();
    $('.load-mhs').show();
    $('#rincian-tagihan').html('');
    $('#detail_mhs').html('');
    var data = {
      mod: 'tagihan',
      hal: 'ajax_data_mhs',
      prodi_kode : $('#frm-filter').find('select[name="prodi"]').val(),
      angkatan : $('#frm-filter').find('select[name="angkatan"]').val(),
      ta_kode : $('#frm-filter').find('select[name="ta"]').val(),
    }
    $.get('post.php', data, function(hasil){
      var data = $.parseJSON(hasil);
      var table = $('#tabelku').DataTable();
      table.clear();
      $.each(data, function() {
          table.row.add(this);
      });
      table.draw();
      $('.load-mhs').hide();

      // Memperbarui action click rincin
      $('#tabelku').on('click', '.btn-rincian', function(e){
        e.preventDefault();    
        $('.load-rincian').show();
        var nim = $(this).data('nim');
        var nama = $(this).data('nama');
        var data = {
          mod: 'tagihan',
          hal: 'ajax_rincian_tagihan',
          nim: nim,
          ta: $(this).data('ta'),
        }
        $.get('post.php', data, function(hasil){
          $('#detail_mhs').html(': ' + nama +' - ' + nim);
          $('#rincian-tagihan').html(hasil);
          $('.load-rincian').hide();
        });
      });

    });
  });

});
</script>    