<?php 
defined("RESMI") or die('Akses ditolak');

$id = sanitasi($_POST['id']);

$sql = "DELETE FROM mhs_pembayaran WHERE `id` = '$id' ";

$db->exec($sql);

echo $sql;