<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kasir') {
    header("Location: ../login.php");
    exit();
}

include '../db.php';

// Pastikan ada order_id yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Order ID tidak ditemukan.");
}

$orderId = intval($_GET['id']);

// Ambil detail pesanan utama
$stmtOrder = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmtOrder->bind_param("i", $orderId);
$stmtOrder->execute();
$orderResult = $stmtOrder->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Ambil semua item dalam order_id ini
$stmtItems = $conn->prepare(
    "SELECT oi.order_item_id, oi.order_id, oi.product_id, oi.quantity, 
            p.name, p.price
     FROM order_items oi
     JOIN product p ON oi.product_id = p.product_id
     WHERE oi.order_id = ?"
);
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$itemsResult = $stmtItems->get_result();
$orderItems = $itemsResult->fetch_all(MYSQLI_ASSOC);

$totalHarga = 0;
foreach ($orderItems as $item) {
    $totalHarga += $item['quantity'] * $item['price'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Detail Pesanan #<?= htmlspecialchars($orderId) ?></h1>
        <div class="card p-4 mb-3">
            <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($order['name']) ?></p>
            <p><strong>Nomor Meja:</strong> <?= htmlspecialchars($order['table_number']) ?></p>
            <p><strong>Status:</strong> 
                <span class="badge <?= $order['status'] == 'paid' ? 'bg-success' : 'bg-warning' ?>">
                    <?= htmlspecialchars($order['status']) ?>
                </span>
            </p>
        </div>

        <h3 class="mb-3">Daftar Pesanan</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td><?= htmlspecialchars($item['quantity']); ?></td>
                        <td>Rp<?= number_format($item['price'], 0, ',', '.'); ?></td>
                        <td>Rp<?= number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3" class="text-end">Total Harga</th>
                    <th>Rp<?= number_format($totalHarga, 0, ',', '.'); ?></th>
                </tr>
            </tbody>
        </table>

        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">Kembali</a>
            
            <form action="update_payment_status.php" method="POST">
                <input type="hidden" name="orderId" value="<?= $orderId ?>">
                <input type="number" name="uangDibayar" id="uangDibayar" class="form-control mt-3" placeholder="Masukkan jumlah uang" oninput="hitungKembalian()" required>
                <p class="mt-2"><strong>Kembalian:</strong> Rp<span id="kembalian">0</span></p>
                
                <?php if ($order['status'] !== 'paid'): ?>
                    <button type="submit" name="bayar" class="btn btn-success">Bayar Sekarang</button>
                <?php endif; ?>
                
            </form>

            
            <form action="process_print.php" method="POST" target="blank">
                <input type="hidden" name="orderId" value="<?= $orderId ?>">
                <input type="hidden" name="uangDibayar" id="uangDibayarInput">
                <?php if ($order['status'] === 'paid'): ?>
                    <button type="submit" name="cetak" class="btn btn-primary">Cetak Struk</button>
                <?php endif; ?>
            </form>
        </div>

    </div>

    <script>
        function hitungKembalian() {
            let totalHarga = <?= $totalHarga ?>;
            let uangDibayar = document.getElementById("uangDibayar").value;
            let kembalian = uangDibayar - totalHarga;
            document.getElementById("kembalian").innerText = kembalian > 0 ? kembalian.toLocaleString('id-ID') : 0;
            document.getElementById("uangDibayarInput").value = uangDibayar; // Set the value for form submission
        }
    </script>
</body>
</html>
