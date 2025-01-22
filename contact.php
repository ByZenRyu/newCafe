<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
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
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <div class="text-center mb-4">
        <h2>📩 Hubungi Kami</h2>
        <p>Silakan isi formulir di bawah ini.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow">
                <form action="process_contact.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama:</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan:</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
