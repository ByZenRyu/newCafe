<?php
// Ambil data produk yang dipilih dari request POST
$products = isset($_POST['products']) ? $_POST['products'] : [];
$productIds = isset($_POST['product_ids']) ? $_POST['product_ids'] : [];  // Ambil product_ids
$quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

// Jika tidak ada produk yang dipilih
if (empty($products)) {
    echo "Tidak ada produk yang dipilih.";
    exit;
}

// Koneksi ke database untuk mendapatkan detail produk
require_once 'db.php';

$selectedProducts = [];
$total = 0;
foreach ($productIds as $index => $productId) {  // Gunakan productIds dari POST
    // Ambil data produk berdasarkan ID
    $query = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $query->bind_param("i", $productId);
    $query->execute();
    $result = $query->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Cek apakah ada jumlah yang diinputkan untuk produk ini
        $quantity = isset($quantities[$productId]) ? $quantities[$productId] : 1;
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
    <title>Halaman Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Cafe PPLG</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="shop.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                                <!-- Input hidden untuk mengirimkan product_id -->
                                <input type="hidden" name="products" value="<?= $product['product_id']; ?>">
                                <!-- Input hidden untuk mengirimkan jumlah produk -->
                                <input type="hidden" name="quantities" value="<?= $product['quantity']; ?>">
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <p>Total Harga: Rp <?= number_format($total, 0, ',', '.'); ?></p>

            <hr>

            <h3>Data Pembeli</h3>
            <label for="name">Nama:</label><br>
            <input type="text" name="name" required class="form-control"><br><br>

            <label for="table">Nomor Meja:</label>
            <input type="number" name="table" required class="form-control"><br><br>

            <!-- Input untuk total harga -->
            <input type="hidden" name="total" value="<?= $total; ?>">

            <button type="submit" class="btn btn-primary">Proses Pembelian</button>
        </form>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Cafe PPLG</h5>
                    <p>Menikmati hidangan lezat di setiap suapan, di Cafe PPLG kami menyajikan berbagai makanan terbaik untuk Anda.</p>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Makanan</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Minuman</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Promo</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Tentang Kami</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt"></i> Alamat: Jl. PPLG No. 123, Kota Sumedang</li>
                        <li><i class="bi bi-telephone"></i> Telepon: (022) 123-4567</li>
                        <li><i class="bi bi-envelope"></i> Email: info@cafepplg.com</li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-4">
                <h5>Follow Us</h5>
                <a href="#" class="text-white me-4"><i class="bi bi-facebook" style="font-size: 1.5em;"></i></a>
                <a href="#" class="text-white me-4"><i class="bi bi-twitter" style="font-size: 1.5em;"></i></a>
                <a href="#" class="text-white me-4"><i class="bi bi-instagram" style="font-size: 1.5em;"></i></a>
                <a href="#" class="text-white me-4"><i class="bi bi-youtube" style="font-size: 1.5em;"></i></a>
            </div>

            <div class="text-center mt-4">
                <p>&copy; 2025 Cafe PPLG. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
