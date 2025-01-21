<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kasir') {
    header("Location: ../login.php");
    exit();
}

include '../db.php';

// Inisialisasi variabel
$order = null;
$change = 0;
$paidAmount = 0;  // Tambahkan variabel untuk pembayaran yang telah diterima

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    // Ambil data pesanan yang dipilih berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    
    // Menghitung kembalian jika jumlah uang yang dibayar sudah diinput
    if (isset($_POST['paid_amount']) && is_numeric($_POST['paid_amount'])) {
        $paidAmount = $_POST['paid_amount'];
        if ($paidAmount >= $order['total_price']) {
            $change = $paidAmount - $order['total_price'];

            // Update status pesanan menjadi 'paid' setelah pembayaran selesai
            $updateStmt = $conn->prepare("UPDATE orders SET status = 'paid', payment = ? WHERE order_id = ?");
            $updateStmt->bind_param("di", $paidAmount, $orderId);
            $updateStmt->execute();
        } else {
            $change = 0;  // Jika pembayaran kurang dari total harga, tidak ada kembalian
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script>
        // Fungsi untuk menghitung kembalian secara otomatis
        function calculateChange() {
            var totalPrice = parseFloat(document.getElementById("totalPrice").innerText.replace('Rp', '').replace('.', '').replace(',', '.'));
            var paidAmount = parseFloat(document.getElementById("paidAmount").value);
            if (!isNaN(paidAmount)) {
                var change = paidAmount - totalPrice;
                document.getElementById("changeAmount").innerText = 'Rp' + change.toLocaleString('id-ID');
            }
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Kasir Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="kasir_order.php">Lihat Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Pembayaran Pesanan</h1>

        <?php if ($order): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Pesanan ID: <?= htmlspecialchars($order['order_id']); ?></h5>
                    <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($order['name']); ?></p>
                    <p><strong>Nomor Meja:</strong> <?= htmlspecialchars($order['table_number']); ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
                    <p><strong>Total Harga:</strong> <span id="totalPrice">Rp<?= number_format($order['total_price'], 0, ',', '.'); ?></span></p>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="paidAmount" class="form-label">Jumlah Uang yang Dibayar</label>
                            <input type="number" id="paidAmount" name="paid_amount" class="form-control" placeholder="Masukkan jumlah uang yang dibayar" oninput="calculateChange()" required>
                        </div>

                        <div class="mb-3">
                            <label for="changeAmount" class="form-label">Kembalian</label>
                            <p id="changeAmount">Rp0</p>
                        </div>

                        <button type="submit" class="btn btn-success">Selesaikan Pembayaran</button>
                    </form>

                    <?php if (isset($paidAmount) && $paidAmount >= $order['total_price']): ?>
                        <a href="print_receipt.php?id=<?= $order['order_id']; ?>" class="btn btn-info mt-3" target="_blank">Cetak Struk</a>
                    <?php elseif (isset($paidAmount) && $paidAmount < $order['total_price']): ?>
                        <div class="alert alert-warning mt-3">
                            Pembayaran kurang dari total harga!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Pesanan tidak ditemukan.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
