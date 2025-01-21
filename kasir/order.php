<?php
session_start();

// Mendapatkan nomor meja dari query string
$tableNumber = $_GET['table'] ?? null;

if (!$tableNumber || !isset($_SESSION['orders'][$tableNumber])) {
    die("Pesanan untuk nomor meja ini tidak ditemukan.");
}

$orders = $_SESSION['orders'][$tableNumber];
$totalAmount = 0;
foreach ($orders as $order) {
    $totalAmount += $order['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Meja <?= $tableNumber ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Pesanan Meja <?= $tableNumber ?></h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['product'] ?></td>
                        <td>Rp <?= number_format($order['price'], 0, ',', '.') ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total:</strong></td>
                    <td><strong>Rp <?= number_format($totalAmount, 0, ',', '.') ?></strong></td>
                </tr>
            </tbody>
        </table>

        <form action="print_receipt.php" method="POST">
            <input type="hidden" name="table_number" value="<?= $tableNumber ?>">
            <button type="submit" class="btn btn-success w-100">Cetak Struk</button>
        </form>

        <a href="index.php" class="btn btn-secondary w-100 mt-3">Kembali ke Input Pesanan</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
