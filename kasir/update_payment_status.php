<?php
session_start();
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['orderId']) || !isset($_POST['uangDibayar'])) {
        die("Data tidak lengkap.");
    }

    $orderId = intval($_POST['orderId']);
    $uangDibayar = intval($_POST['uangDibayar']);

    var_dump($uangDibayar);

    // Ambil total harga dari database
    $stmt = $conn->prepare("SELECT total_price FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        die("Pesanan tidak ditemukan.");
    }

    $totalHarga = $order['total_price'];

    if ($uangDibayar < $totalHarga) {
        die("Uang yang dibayarkan kurang!");
    }

    // Update status pesanan menjadi 'paid'
    $stmt = $conn->prepare("UPDATE orders SET payment = ?,  status = 'paid' WHERE order_id = ?");
    $stmt->bind_param("ii", $uangDibayar,$orderId);
    $stmt->execute();

    header("Location: kasir_order.php?id=$orderId&status=success");
    exit();
}
?>
