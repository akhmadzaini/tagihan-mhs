<?php
session_start();
define("RESMI", "OK");

if(!isset($_SESSION['userid'])){
  header("Location: login.php");
}

require_once('fungsi.php');
require_once('koneksi.php');

if(isset($_GET['mod'])){
  $mod = sanitasi($_GET['mod']);
  $hal = sanitasi($_GET['hal']);
}

?>
<!DOCTYPE html>
<html style="height: auto;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Aplikasi Keuangan Mahasiswa</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
  <link rel="stylesheet" href="assets/css/skin-blue.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables/dataTables.bootstrap.css">
  <!-- jQuery 2.2.3 -->
  <script src="assets/js/jquery-2.2.3.min.js"></script>
</head>
<body class="skin-blue sidebar-mini" style="height: auto;">
<div class="wrapper" style="height: auto;">

  <header class="main-header">

    <a href="file:///F:/project/stiekn/www/AdminLTE-2.3.11/index2.html" class="logo">
      <span class="logo-mini"><b>A</b>LT</span>
      <span class="logo-lg"><b>App</b>Keuangan</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
      <a href="file:///F:/project/stiekn/www/AdminLTE-2.3.11/starter.html#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="##" class="dropdown-toggle" data-toggle="dropdown">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?=$_SESSION['nama_pengguna']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="assets/img/anonim.jpg" class="img-circle" alt="User Image">

                <p>
                  <?=$_SESSION['nama_pengguna']?> - Pengelola
                  <small>Menjadi anggota sejak tgl ...</small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="index.php?mod=pengguna&hal=profil" class="btn btn-default btn-flat">Profil</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">MENU APLIKASI</li>
        <!-- Optionally, you can add icons to the links -->
        <li class=""><a href="index.php?mod=pembayaran&hal=index"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>

        <li class="treeview">
          <a href="#"><i class="fa fa-cogs"></i> <span>Pengaturan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="index.php?mod=pengaturan&hal=ta">Tahun akademik</a></li>
            <li><a href="index.php?mod=pengaturan&hal=mhs">Mahasiswa</a></li>
            <li><a href="index.php?mod=pengaturan&hal=rek">Rekening</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#"><i class="fa fa-cubes"></i> <span>Tagihan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="index.php?mod=tagihan&hal=atur">Atur tagihan</a></li>
            <li><a href="index.php?mod=tagihan&hal=individu">Penagihan individu</a></li>
          </ul>
        </li>

        <li class=""><a href="index.php?mod=pembayaran&hal=index"><i class="fa fa-money"></i> <span>Pembayaran</span></a></li>

        <li class="treeview">
          <a href="#"><i class="fa fa-line-chart"></i> <span>Laporan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="index.php?mod=laporan&hal=tagihan">Tagihan</a></li>
            <li><a href="index.php?mod=laporan&hal=pembayaran">Pembayaran Harian</a></li>
            <li><a href="index.php?mod=laporan&hal=piutang">Piutang</a></li>
            <!--<li><a href="index.php?mod=laporan&hal=prodi">Prodi</a></li>
            <li><a href="index.php?mod=laporan&hal=pp">Pendapatan dan Pengeluaran</a></li>-->
          </ul>
        </li>

      </ul>

      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <?php
    if(isset($_GET['mod'])){
      include('modul/' . $mod . '/' . $hal . '.php');
    }else{
      include('modul/pembayaran/index.php');
    }
  ?>


  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <!--<div class="pull-right hidden-xs">
      Anything you want
    </div>-->
    <!-- Default to the left -->
    <strong>Copyright © <script>document.write(new Date().getFullYear())</script> <a href="http://zaini.my.id">Akhmad Zaini</a>.</strong>
  </footer>


  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg" style="position: fixed; height: auto;"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- Bootstrap 3.3.6 -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/app.min.js"></script>

<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/fastclick/fastclick.js"></script>
<script src="assets/plugins/bootbox/bootbox.min.js"></script>
<script src="assets/plugins/jquery.mask.js"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->

<script>
  $(document).ready(function() {
    $('#tabelku').DataTable();
  });
</script>

</body></html>