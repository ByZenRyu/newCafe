<?php
include '../db.php';

// Ambil ID order dari parameter URL
$orderId = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil detail orderan dari database berdasarkan order_id
$query = "SELECT order_id, name, table_number, total_price, created_at, payment FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

$order = $result->fetch_assoc();

if (!$order) {
    // Improved error handling
    echo "Order tidak ditemukan.";
    exit();
}

// Hitung kembalian jika ada pembayaran
$payment = isset($order['payment']) ? $order['payment'] : 0;
$change = ($payment > 0) ? $payment - $order['total_price'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            width: 280px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .header {
            text-align: center;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 40px;
            height: auto;
        }

        .header-text {
            text-align: center;
            font-size: 14px;
            width: 100%;
        }

        .receipt-detail {
            margin-bottom: 10px;
        }

        .receipt-detail span {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }

        .total-price {
            font-size: 14px;
            font-weight: bold;
            margin-top: 14px;
        }
        .total-price span {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .highlight {
            color: #f00;
            font-weight: bold;
        }

        /* CSS untuk tampilan print */
        @media print {
            body {
                width: 280px;
                margin: 0;
            }

            .footer {
                margin-top: 20px;
            }

            .signature {
                margin-top: 14px;
            }

            /* Menghilangkan tombol cetak jika ada */
            button {
                display: none;
            }

            .header {
                display: flex;
            }

            .header img {
                display: inline-block;
                width: 30px;
                height: auto;
                margin: 5px;
            }

            .header-text {
                font-size: 12px;
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <img src="logo-left.png" alt="Logo Kiri">
        <div class="header-text">
            <h2>Unit Produksi PPLG</h2>
            <p>Produk Kreatif Kewirausahaan</p>
            <p>Cafetaria PPLG</p>
        </div>
        <img src="logo-right.png" alt="Logo Kanan">
    </div>

    <!-- Receipt Details -->
    <h2>Struk Order</h2>

    <div class="receipt-detail">
        <span>ID Order:</span> <?= htmlspecialchars($order['order_id']); ?>
    </div>
    <div class="receipt-detail">
        <span>Nama:</span> <?= htmlspecialchars($order['name']); ?>
    </div>
    <div class="receipt-detail">
        <span>Nomor Meja:</span> <?= htmlspecialchars($order['table_number']); ?>
    </div>

    <!-- Total Price Section -->
    <div class="total-price">
        <span>Total:</span> Rp<?= number_format($order['total_price'], 0, ',', '.'); ?>
    </div>

    <!-- Payment Section -->
    <div class="receipt-detail">
        <span>Pembayaran:</span> Rp<?= number_format($payment, 0, ',', '.'); ?>
    </div>

    <!-- Change Section -->
    <?php if ($payment > 0 && $payment >= $order['total_price']): ?>
        <div class="receipt-detail">
            <span>Kembalian:</span> Rp<?= number_format($change, 0, ',', '.'); ?>
        </div>
    <?php elseif ($payment > 0 && $payment < $order['total_price']): ?>
        <div class="receipt-detail">
            <span class="highlight">Pembayaran kurang: Rp<?= number_format($order['total_price'] - $payment, 0, ',', '.'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Footer Section -->
    <div class="footer">
        <p>Terima kasih telah berkunjung!</p>
    </div>

    <!-- Signature Section -->
    <div class="signature">
        <p><?= "(............)" ?></p>
    </div>

</body>
</html>
