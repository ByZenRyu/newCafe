<?php
session_start();

// Cek apakah admin yang login
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Koneksi ke database
include '../db.php';

// Ambil id produk dan status baru
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? (int)$_GET['status'] : 0;

// Cek apakah produk dan status valid
if ($product_id > 0 && ($status == 0 || $status == 1)) {
    // Query untuk mengupdate status produk
    $query = "UPDATE product SET is_active = ? WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $status, $product_id);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman daftar produk setelah berhasil
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal mengupdate status produk.";
    }
} else {
    echo "ID produk atau status tidak valid.";
}
?>
