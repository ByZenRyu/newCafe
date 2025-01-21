<?php
// Koneksi ke database
require_once 'db.php';

// Ambil hanya 6 produk yang ada di database
$query = $conn->prepare("SELECT * FROM product WHERE is_active=1 LIMIT 6");
$query->execute();
$products = $query->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Cafe PPLG</title>
    <!-- Menyertakan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styling untuk gambar produk */
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

        .total-price {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 40px;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand {
            font-size: 1.5em;
            color: white;
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        /* Hero Section */
        .hero-section {
            background-image: url('img/bg.jpg');
            background-size: cover;
            background-position: center;
            height: 300px; /* Menyesuaikan tinggi hero section */
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .hero-section h1 {
            font-size: 2.5em; /* Menyesuaikan ukuran font */
        }
    </style>
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="text-center">
            <h1>Selamat Datang di Cafe PPLG</h1>
            <p>Nikmati hidangan lezat kami dan berbagai pilihan minuman segar!</p>
        </div>
    </section>

    <!-- Daftar Produk -->
    <div class="container mt-5">
        <h2 class="text-center">Menu Kami</h2>
        <div class="row mt-4">
            <?php while ($product = $products->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="img/<?= $product["image"] ?>" class="card-img-top" alt="<?= $product['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product['name']; ?></h5>
                            <p class="card-text">Rp <?= number_format($product['price'], 0, ',', '.'); ?></p>
                            <a href="shop.php" class="btn btn-primary">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
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

    <!-- Menyertakan Bootstrap JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
