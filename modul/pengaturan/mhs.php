<?php 
defined("RESMI") or die('Akses ditolak'); 

$prodi = sanitasi(@$_GET['prodi']);
$angkatan = sanitasi(@$_GET['angkatan']);

if(isset($_GET['prodi']) && isset($_GET['angkatan'])){
  $sql = "SELECT a.nim,a.nama, a.prodi_kode, b.nama AS prodi, a.angkatan, a.skema
          FROM mhs a 
          LEFT JOIN prodi b ON a.prodi_kode = b.kode
          WHERE a.prodi_kode = '$prodi' AND a.angkatan = '$angkatan'
          ORDER BY a.nim";
  $res = $db->query($sql);
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 488px;">
  <?php if(@$_GET['msg'] == 'sukses_import'): ?>
    <div class="alert alert-info">
      Proses import telah selesai dilakukan
    </div>
  <?php endif ?>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Pengaturan
      <small>Mahasiswa</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-cogs"></i> Pengaturan</a></li>
      <li class="active">mahasiswa</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Filter Mahasiswa</h3>
            <a href="#" class="btn btn-info btn-flat" data-toggle="modal" data-target="#editorMhs"><i class="fa fa-plus" aria-hidden="true"></i> Mahasiswa Baru</a> 
            <a href="template/excel-mhs.xlsx" class="btn btn-flat btn-info">
              <i class="fa fa-file-excel-o"></i> Contoh format excel
            </a>
            <a href="#" class="btn btn-info btn-flat" data-toggle="modal" data-target="#importExcel"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Unggah file excel</a> 
          </div>
          <div class="box-body">
            <form action="" method="get" id="frm-filter">
              <input type="hidden" value="pengaturan" name="mod">
              <input type="hidden" value="mhs" name="hal">
              <div class="form-group">
                <label for="">Program Studi</label>
                <?=combo_prodi($prodi)?>
              </div>
              <div class="form-group">
                <label for="">Angkatan</label>
                <?=combo_angkatan($angkatan)?>
              </div>
              <input type="submit" value="Lihat data" class="btn btn-flat btn-info btn-xl">
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <?php if(isset($_GET['prodi']) && isset($_GET['angkatan'])): ?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Data Mahasiswa</h3>
          </div>
          <div class="box-body">   
            <table id="tabelku" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>NIM</th>
                  <th>Nama</th>
                  <th>Program Studi</th>
                  <th>Angkatan</th>
                  <th>Skema Pembiayaan</th> 
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php while($r=$res->fetchArray(SQLITE3_ASSOC)): ?>
                  <tr>
                    <td><?=$r['nim']?></td>
                    <td><?=$r['nama']?></td>
                    <td><?=$r['prodi']?></td>
                    <td><?=$r['angkatan']?></td>
                    <td><?=$r['skema']?></td>
                    <td>
                      <div class="btn-group">
                        <a href="" class="btn btn-default btn-flat" data-toggle="modal" data-target="#editorMhs"
                        data-nim="<?=$r['nim']?>"
                        data-nama="<?=$r['nama']?>"
                        data-prodi_kode="<?=$r['prodi_kode']?>"
                        data-skema="<?=$r['skema']?>"
                        data-angkatan="<?=$r['angkatan']?>">
                          <i class="fa fa-pencil-square" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="btn btn-default btn-del" data-nim="<?=$r['nim']?>">
                          <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endwhile?>
              </tbody>
            </table>  
          </div>
        </div>
      </div>
    </div>
  <?php endif ?>

  </section>
  <!-- /.content -->

<form action="post.php?mod=pengaturan&hal=submit_mhs" method="post" role="form" data-toggle="validator">
<div class="modal fade" tabindex="-1" role="dialog" id="editorMhs">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editor Mahasiswa</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="">Nomor Induk Mahasiswa (NIM)</label>
            <input type="text" name="nim" placeholder="Nomor Induk Mahasiswa, contoh : 160402xxxxx" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="">Nama</label>
            <input type="text" name="nama" placeholder="Nama Lengkap Mahasiswa" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="">Program Studi</label>
            <?=combo_prodi()?>
          </div>

          <div class="form-group">
            <label for="">Angkatan</label>
            <input type="text" name="angkatan" placeholder="Angkatan, contoh : 2016" class="form-control" required="">
          </div>

          <div class="form-group">
            <label for="">Skema Pembiayaan</label>
            <input type="text" name="skema" placeholder="Skema/kelompok pembiayaan, contoh : A" class="form-control" required="">
          </div>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn bg-orange btn-flat" value="Simpan">
        <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
</form>

<div class="modal fade" tabindex="-1" role="dialog" id="importExcel">
  <div class="modal-dialog" role="document">
    <form method="post" enctype="multipart/form-data" action="post.php?mod=pengaturan&hal=unggah_excel" id="frm-import">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Unggah File Excel</h4>
      </div>
      <div class="modal-body">
        
        <div class="loading overlay" style="display: none;"><i class="fa fa-refresh fa-spin"></i></div>

        Pilih file excel:
        <input type="file" name="fileToUpload" id="fileToUpload" required=""><br/>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-info btn-flat" value="Unggah file">
        <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Batal</button>
      </div>
    </div>
    </form>
    <!-- END modal-content -->
  </div>
</div>

<script>
  $(document).ready(function(){
    
    var ot = $('#tabelku').dataTable();

    $('#editorMhs').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        $(this).find('input[name="nim"]').val('')
        $(this).find('input[name="nama"]').val('')
        $(this).find('select[name="prodi"]').val('')
        $(this).find('input[name="angkatan"]').val('')
        $(this).find('input[name="skema"]').val('')
        if(button.data('nim') != ''){
          var nim = button.data('nim')
          var nama = button.data('nama')
          var prodi_kode = button.data('prodi_kode')
          var angkatan = button.data('angkatan')
          var skema = button.data('skema')
          $(this).find('input[name="nim"]').val(nim)
          $(this).find('input[name="nama"]').val(nama)
          $(this).find('select[name="prodi"]').val(prodi_kode)
          $(this).find('input[name="angkatan"]').val(angkatan)          
          $(this).find('input[name="skema"]').val(skema)          
        }
    });

    $('.btn-del').click(function(e){
      e.preventDefault()
      var button = $(this)
      bootbox.confirm({
        title: "Konfirmasi penghapusan",
        message: "Data yang terhapus tidak dapat dikembalikan, yakin ingin menghapus data ini?",
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
              mod:'pengaturan',
              hal:'ajax_del_mhs',
              nim:button.data('nim')
            }
            var sel_row = button.parent().parent().parent();
            $.get('post.php', data, function(hasil){
              ot.fnDeleteRow(sel_row);
              alert('Data telah terhapus');
            });
          }
        }
      });      
    });

    /*$('#frm-import').submit(function(e){
      $('.loading').show();
      e.preventDefault();
      $.post('post.php?mod=pengaturan&hal=unggah_excel', $(this).serialize(), function(hasil){
        $('.loading').hide();
        alert(hasil);
      });
    });*/

  });
</script>