<?php
session_start();

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Redirect ke proses hapus
$id = (int)$_GET['id'];
header("Location: ../../proses/proses.php?aksi=hapus_produk&id=" . $id);
