  <?php 
  defined("RESMI") or die('Akses ditolak'); 
  $sql= "SELECT kode, tahun, Semester
         FROM ta
         ORDER BY kode";
  $ret = $db->query($sql);

  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 488px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pengaturan
        <small>Tahun akademik</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-cogs"></i> Pengaturan</a></li>
        <li class="active">tahun akademik</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <?php if(@$_GET['msg'] == 'hapus_sukses'): ?>
        <div class="row">
          <div class="col-xs-12">
            <div class="alert alert-info">
              Data telah terhapus.
            </div>
          </div>
        </div>
      <?php endif?>
  
      <?php if(@$_GET['msg'] == 'hapus_gagal'): ?>
        <div class="row">
          <div class="col-xs-12">
            <div class="alert alert-danger">
              Data gagal terhapus.
            </div>
          </div>
        </div>
      <?php endif?>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data tahun akademik</h3>
              <a href="#" class="btn btn-info btn-flat btn-xl" data-toggle="modal" data-target="#editorTA"
              data-kode=""
              data-tahun=""
              data-semester="">
                <i class="fa fa-plus" aria-hidden="true"></i> Tahun Akademik Baru
              </a>
            </div>
            <div class="box-body">
              <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                
                <div class="row">
                  <div class="col-sm-12">

                    <table id="tabelku" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Kode</th>
                          <th>Tahun</th>
                          <th>Semester</th>  
                          <th></th> 
                        </tr>
                      </thead>
                      <tbody>
                        <?php while($r = $ret->fetchArray(SQLITE3_ASSOC)): ?>
                          <tr>
                            <td><?=$r['kode']?></td>
                            <td><?=$r['tahun']?></td>
                            <td><?=$r['semester']?></td>
                            <td>
                              <div class="btn-group">
                                <a href="#" class="btn btn-default btn-flat"
                                data-toggle="modal" data-target="#editorTA"
                                data-kode="<?=$r['kode']?>"
                                data-tahun="<?=$r['tahun']?>"
                                data-semester="<?=$r['semester']?>"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></a>
                                <a href="#" class="btn btn-default btn-flat btn-hapus" data-kode="<?=$r['kode']?>">
                                  <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endwhile ?>
                      </tbody>
                    </table>

                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->

<!-- editor tahun akademik -->

<form action="post.php?mod=pengaturan&hal=submit_ta" method="post" role="form" data-toggle="validator">
<div class="modal fade" tabindex="-1" role="dialog" id="editorTA">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editor Tahun Akademik</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="">Kode</label>
            <input type="text" name="kode" placeholder="Kode TA, contoh : 20161" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="">Tahun Akademik</label>
            <input type="text" name="tahun" placeholder="Tahun Akademik" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="">Semester</label>
            <input type="text" name="semester" placeholder="Semester, contoh : GANJIL" class="form-control" required="">
          </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-info btn-flat" value="Simpan">
        <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
</form>

<script>
  $(document).ready(function(){
    $('#editorTA').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var kode = button.data('kode')
        var tahun = button.data('tahun')
        var semester = button.data('semester')
        $(this).find('input[name="kode"]').val(kode)
        $(this).find('input[name="tahun"]').val(tahun)
        $(this).find('input[name="semester"]').val(semester)
    });

    $('.btn-hapus').click(function(){
      var kode = $(this).data('kode');
      bootbox.confirm({
        message: "Data yang terhapus tidak dapat dikembalikan, yakin ingin menghapus data ini?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'bg-orange btn-flat'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger btn-flat'
            }
        },
        callback: function (result) {
          if(result == true){
            document.location.href = "post.php?mod=pengaturan&hal=del_ta&kode=" + kode;
          }
        }
      });
    });

  });
</script>