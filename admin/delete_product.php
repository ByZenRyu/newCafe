<?php
session_start();

// Cek apakah pengguna sudah login dan memiliki akses admin
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Koneksi ke database
include '../db.php';

// Periksa apakah parameter 'id' valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Query untuk mendapatkan gambar produk
    $query = "SELECT image FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();

    // Cek apakah produk ditemukan
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($imagePath);
        $stmt->fetch();

        // Debug: Output gambar yang ditemukan
        echo "Debug: imagePath = $imagePath <br>";

        // Cek dan hapus gambar dari server jika ada
        $fullImagePath = realpath($imagePath);  // Menggunakan realpath() untuk memastikan path yang benar
        if (!empty($imagePath) && file_exists($fullImagePath)) {
            if (unlink($fullImagePath)) {
                echo "Gambar produk berhasil dihapus.<br>";
            } else {
                echo "Gagal menghapus gambar produk.<br>";
            }
        } else {
            echo "Gambar tidak ditemukan di path yang ditentukan: $fullImagePath<br>";
        }

        // Query untuk menghapus produk
        $queryDelete = "DELETE FROM product WHERE product_id = ?";
        $stmtDelete = $conn->prepare($queryDelete);
        $stmtDelete->bind_param("i", $product_id);

        if ($stmtDelete->execute()) {
            // Redirect ke halaman utama setelah produk berhasil dihapus
            header("Location: index.php?success=Produk berhasil dihapus.");
            exit();
        } else {
            echo "Error: " . $stmtDelete->error;
        }
    } else {
        echo "Produk tidak ditemukan!";
    }
} else {
    echo "ID Produk tidak valid!";
}
?>
