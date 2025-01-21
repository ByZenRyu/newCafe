<?php
include '../db.php';

if (isset($_POST['orderId'], $_POST['paymentStatus'], $_POST['paymentAmount'], $_POST['changeAmount'])) {
    $orderId = $_POST['orderId'];
    $paymentStatus = $_POST['paymentStatus'];
    $paymentAmount = $_POST['paymentAmount'];
    $changeAmount = $_POST['changeAmount'];

    // Update status pembayaran dan data pembayaran
    $updateQuery = "UPDATE orders SET status = ?, payment = ?, change = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sdii", $paymentStatus, $paymentAmount, $changeAmount, $orderId);
    $stmt->execute();

    // Cek apakah query berhasil
    if ($stmt->affected_rows > 0) {
        echo "Pembayaran berhasil diperbarui.";
    } else {
        echo "Gagal memperbarui pembayaran.";
    }
}
?>
