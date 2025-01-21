<?php
// Pastikan form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $table = $_POST['table'];
    $total = isset($_POST['total']) ? $_POST['total'] : 0; 
    $id = $_POST['products'];
    $quantity = $_POST['quantities'];



    // Koneksi ke database
    require_once 'db.php';

    // Simpan data pesanan ke database
    $query = $conn->prepare("INSERT INTO orders (name, table_number, total_price, product_id, quantity) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("ssiii", $name, $table, $total, $id, $quantity);
    $query->execute();

    // Redirect ke halaman checkout dengan pesan sukses
    echo "<script type='text/javascript'>window.location = 'shop.php?success=true';</script>";
    exit;
}
?>
