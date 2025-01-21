<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'koki') {
    header("Location: ../login.php");
    exit();
}

require_once(__DIR__ . "/../db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memeriksa parameter id dan status
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("❌ Parameter tidak lengkap!");
}

$order_id = intval($_GET['id']);
$status = $_GET['status'];

// Daftar status yang diizinkan sesuai dengan ENUM
$allowed_status = ['Sedang antri', 'Sedang Dimasak', 'Selesai'];

// Memeriksa apakah status yang diterima valid
if (!in_array($status, $allowed_status)) {
    die("❌ Status tidak valid! Pilihan yang valid: Sedang antri, Sedang Dimasak, Selesai.");
}

// Query untuk memperbarui status pesanan
$query = "UPDATE orders SET order_status = ? WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $order_id);

// Menjalankan query dan menangani kesalahan
if ($stmt->execute()) {
    header("Location: index.php?success=1");
} else {
    die("Gagal memperbarui status pesanan: " . $conn->error);
}
?>
