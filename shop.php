<?php
// Koneksi ke database
require_once 'db.php';

// Ambil produk yang ada di database
$query = $conn->prepare("SELECT * FROM product WHERE is_active = 1");
$query->execute();
$products = $query->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pemilihan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            text-align: center;
        }
        .product-checkbox {
            width: 20px;
            height: 20px;
        }
        .total-price {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
    <script>
       function calculateTotal() {
    let total = 0;

    // Ambil semua produk yang dipilih (checkbox dicentang)
    let selectedProducts = document.querySelectorAll('input[name="products[]"]:checked');
    
    selectedProducts.forEach(function(product) {
        // Ambil harga produk dari data-price
        let price = parseFloat(product.getAttribute('data-price'));

        // Ambil kuantitas untuk produk tersebut
        let productId = product.value;
        let quantity = parseInt(document.querySelector('input[name="quantities[' + productId + ']"]').value);

        // Hitung subtotal dan tambahkan ke total
        total += price * quantity;
    });

    // Tampilkan total harga dalam format yang lebih mudah dibaca
    document.getElementById('total').textContent = "Total Harga: Rp " + total.toLocaleString();
}

        function filterUnchecked() {
        let checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function (checkbox) {
            if (!checkbox.checked) {
                checkbox.parentElement.querySelector('input[type="hidden"]').remove();
                checkbox.parentElement.querySelector('input[type="number"]').remove();
            }
        });
    }
    </script>
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
                        <a class="nav-link " aria-current="page" href="index.php">Home</a>
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
        <h1 class="text-center">Pilih Produk</h1>
        <form action="checkout.php" method="POST">
    <div class="row">
        <?php while ($product = $products->fetch_assoc()) { ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="img/<?= $product["image"] ?>" class="card-img-top" alt="<?= $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product['name']; ?></h5>
                        <p class="card-text">Rp <?= number_format($product['price'], 0, ',', '.'); ?></p>

                        <!-- Checkbox untuk memilih produk -->
                        <input type="checkbox" name="products[]" value="<?= $product['product_id']; ?>"
                            data-price="<?= $product['price']; ?>" onchange="calculateTotal()">
                        Pilih Produk

                        <!-- Input kuantitas produk -->
                        <input type="number" name="quantities[<?= $product['product_id']; ?>]" 
                            value="1" min="1" class="form-control mt-2" onchange="calculateTotal()">
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <p id="total" class="total-price">Total Harga: Rp 0</p><br>

    <button type="submit" class="btn btn-primary">Lanjut ke Checkout</button>
</form>


    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-4">
        <div class="container">
            <div class="row">
                <!-- Kolom 1: Informasi Cafe -->
                <div class="col-md-4 mb-4">
                    <h5>Cafe PPLG</h5>
                    <p>Menikmati hidangan lezat di setiap suapan, di Cafe PPLG kami menyajikan berbagai makanan terbaik untuk Anda.</p>
                </div>
                
                <!-- Kolom 2: Menu Cepat -->
                <div class="col-md-4 mb-4">
                    <h5>Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Makanan</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Minuman</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Promo</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Tentang Kami</a></li>
                    </ul>
                </div>
                
                <!-- Kolom 3: Kontak -->
                <div class="col-md-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt"></i> Alamat: Jl. PPLG No. 123, Kota Sumedang</li>
                        <li><i class="bi bi-telephone"></i> Telepon: (022) 123-4567</li>
                        <li><i class="bi bi-envelope"></i> Email: info@cafepplg.com</li>
                    </ul>
                </div>
            </div>

            <!-- Social Media -->
            <div class="text-center mt-4">
                <h5>Follow Us</h5>
                <a href="#" class="text-white me-4"><i class="bi bi-facebook" style="font-size: 1.5em;"></i></a>
                <a href="#" class="text-white me-4"><i class="bi bi-twitter" style="font-size: 1.5em;"></i></a>
                <a href="#" class="text-white me-4"><i class="bi bi-instagram" style="font-size: 1.5em;"></i></a>
                <a href="#" class="text-white me-4"><i class="bi bi-youtube" style="font-size: 1.5em;"></i></a>
            </div>

            <!-- Copyright -->
            <div class="text-center mt-4">
                <p>&copy; 2025 Cafe PPLG. All rights reserved.</p>
            </div>
        </div>
    </footer>



    <?php if (isset($_GET['success'])) { ?>
        <script type="text/javascript">
            alert('Pesanan berhasil diproses!');
        </script>
    <?php } ?>

    <!-- Menyertakan Bootstrap Icons untuk ikon media sosial -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
