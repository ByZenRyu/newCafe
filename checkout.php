<?php
require_once 'db.php';

// Ambil data produk yang dipilih dari request POST
$products = isset($_POST['products']) ? $_POST['products'] : [];
$quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

if (empty($products)) {
    echo "<script>alert('Silakan pilih produk terlebih dahulu!'); window.location='shop.php';</script>";
    exit;
}

$selectedProducts = [];
$total = 0;

foreach ($products as $index => $productId) {
    $query = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $query->bind_param("i", $productId);
    $query->execute();
    $result = $query->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Mengambil kuantitas yang sesuai berdasarkan indeks produk
        $quantity = isset($quantities[$productId]) ? (int)$quantities[$productId] : 1;
        $subtotal = $product['price'] * $quantity;

        $selectedProducts[] = [
            'product_id' => $product['product_id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];

        $total += $subtotal;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Rincian Pesanan</h1>

    <form action="process_checkout.php" method="POST">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selectedProducts as $product) { ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td>Rp <?= number_format($product['price'], 0, ',', '.'); ?></td>
                            <td><?= $product['quantity']; ?></td>
                            <td>Rp <?= number_format($product['subtotal'], 0, ',', '.'); ?></td>

                            <!-- Input hidden agar data dikirim sebagai array -->
                            <input type="hidden" name="products[]" value="<?= $product['product_id']; ?>">
                            <input type="hidden" name="quantities[]" value="<?= $product['quantity']; ?>">
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <p>Total Harga: Rp <?= number_format($total, 0, ',', '.'); ?></p>

        <hr>

        <h3>Data Pembeli</h3>
        <label for="name">Nama:</label>
        <input type="text" name="name" required class="form-control"><br>

        <label for="table">Nomor Meja:</label>
        <input type="number" name="table" required class="form-control"><br>

        <input type="hidden" name="total" value="<?= $total; ?>">

        <button type="submit" class="btn btn-primary">Proses Pembelian</button>
    </form>
</div>

</body>
</html>
