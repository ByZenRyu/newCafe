<?php
session_start();
include '../db.php';

// Pastikan order_id dikirimkan
if (!isset($_POST['orderId']) || empty($_POST['orderId'])) {
    die("Data tidak lengkap.");
}

$orderId = intval($_POST['orderId']);

// Query untuk mengambil nilai payment dari database
$query = $conn->prepare("SELECT payment FROM orders WHERE order_id = ?");
$query->bind_param("i", $orderId);
$query->execute();
$ress = $query->get_result();
$pay = $ress->fetch_assoc();

if (!$pay) {
    die("Pembayaran tidak ditemukan.");
}

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

$kembalian = $pay['payment'] - $totalHarga;  // Gunakan nilai payment dari database
$tanggalPembayaran = date("d-m-Y H:i:s");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f9f9f9;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .subheader {
            font-size: 16px;
            font-weight: normal;
            margin-top: 5px;
            color: #555;
        }

        .cafeteria {
            font-size: 14px;
            margin-top: 5px;
            color: #777;
        }

        .date {
            font-size: 12px;
            margin-top: 10px;
            color: #888;
        }

        .details {
            margin-top: 15px;
        }

        .details p {
            margin: 2px 0;
            font-size: 14px;
            color: #333;
        }

        .table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 5px;
            text-align: left;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f4f4f4;
        }

        .total {
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #ddd;
            margin: 10px 0;
        }

        .space {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">UNIT PRODUKSI PPLG</div>
    <div class="subheader">Produk Kreatif Kewirausahaan</div>
    <div class="cafeteria">CAFETARIA PPLG</div>
    <div class="date"><?= $tanggalPembayaran ?></div>
    <div class="line"></div>
    
    <div class="details">
        <p class="bold">Nama Pemesan: <?= htmlspecialchars($order['name']) ?></p>
        <p class="bold">Nomor Meja: <?= htmlspecialchars($order['table_number']) ?></p>
        <p class="bold">Status: <?= htmlspecialchars($order['status']) ?></p>
    </div>
    
    <div class="line"></div>
    <h4>Daftar Pesanan</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td><?= htmlspecialchars($item['quantity']); ?></td>
                    <td>Rp <?= number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="line"></div>
    <div class="total">
        <p>Total Harga: Rp <?= number_format($totalHarga, 0, ',', '.'); ?></p>
        <p>Uang Dibayar: Rp <?= number_format($pay['payment'], 0, ',', '.'); ?></p>
        <p>Kembalian: Rp <?= number_format($kembalian, 0, ',', '.'); ?></p>
    </div>
    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
        <p>Silakan kunjungi kami lagi.</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
