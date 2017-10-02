<?php
defined("RESMI") or die("Akses ditolak");

class MyDB extends SQLite3 {
  function __construct($nama_db) {
     $this->open($nama_db);
  }
}
$db = new MyDB('keuangan-mhs.db');
($db) or die($db->lastErrorMsg());
   