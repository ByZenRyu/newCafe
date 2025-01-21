<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Koneksi ke database
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Debugging: Tampilkan data form
    echo "Nama Produk: $name<br>";
    echo "Harga: $price<br>";
    echo "Deskripsi: $description<br>";

    // Menangani upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        // Debugging: Tampilkan info file gambar
        echo "<br>Info File Gambar:<br>";
        echo "Nama File: $imageName<br>";
        echo "Ukuran File: $imageSize bytes<br>";
        echo "Tipe File: $imageType<br>";
        echo "Temporary File: $imageTmp<br>";

        // Menentukan direktori untuk menyimpan gambar
        $targetDir = "../img/";
        $targetFile = $targetDir . basename($imageName);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validasi jenis file gambar
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Hanya file gambar yang diperbolehkan (JPG, JPEG, PNG, GIF).";
            exit();
        }

        // Validasi ukuran gambar (maksimum 2MB)
        if ($imageSize > 2 * 1024 * 1024) {
            echo "Ukuran file terlalu besar. Maksimum 2MB.";
            exit();
        }

        // Pindahkan file gambar ke folder uploads
        if (move_uploaded_file($imageTmp, $targetFile)) {
            $imagePath = $targetFile; // Menyimpan path gambar
            echo "Gambar berhasil di-upload ke $targetFile<br>"; // Debugging: Informasi file berhasil di-upload
        } else {
            echo "Terjadi kesalahan saat meng-upload gambar.<br>";
            echo "Error: " . $_FILES['image']['error']; // Debugging: Menampilkan error kode
            exit();
        }
    } else {
        // Jika tidak ada gambar di-upload, menggunakan URL default
        $imagePath = $_POST['image']; // Gambar bisa menggunakan URL
        echo "Menggunakan gambar dari URL: $imagePath<br>"; // Debugging: Gambar dari URL
    }

    // Query untuk menambah produk
    $query = "INSERT INTO product (name, price, description, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $price, $description, $imagePath);
    
    if ($stmt->execute()) {
        echo "Produk berhasil ditambahkan!<br>";
        header("Location: index.php");  // Redirect setelah berhasil menambah produk
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Container -->
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h1 class="text-center">Tambah Produk</h1>
            </div>
            <div class="card-body">
                <a href="index.php" class="btn btn-link">Kembali ke Dashboard</a>

                <!-- Form -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga:</label>
                        <input type="number" name="price" id="price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi:</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk:</label>
                        <input type="file" name="image" id="image" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Tambah Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
