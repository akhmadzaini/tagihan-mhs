  <?php defined("RESMI") or die('Akses ditolak'); ?>
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
      
      <div class="row"><div id="console"></div></div>

      <div class="row">
        <div id="detail_tagihan"></div>
      </div>
      <!-- END row -->

    </section>
    <!-- /.content -->

<script>
  $(document).ready(function(){
    
    // ketika combo angkatan berganti nilai
    var combo_angkatan = $('#frm-filter').find('select[name="angkatan"]');
    $(combo_angkatan).change(function(){
      var data = {
        mod: 'umum',
        hal: 'ajax_combo_skema',
        prodi: $('#frm-filter').find('select[name="prodi"]').val(),
        angkatan: $(this).val(),
      }
      $.get('post.php', data, function(hasil){
        $('#combo_skema').html(hasil);
      })
    });    


    $('#frm-filter').submit(function(e){
      e.preventDefault();
      data = $(this).serialize();
      $.post('post.php?mod=tagihan&hal=atur_ajax_detail_tagihan', data, function(hasil){
        $('#detail_tagihan').html(hasil);
      });
    });

  });
</script>