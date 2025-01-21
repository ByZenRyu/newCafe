<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Koneksi ke database
include '../db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Ambil data produk yang akan diedit
    $query = "SELECT * FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Produk tidak ditemukan!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    // Update produk
    $query = "UPDATE product SET name = ?, price = ?, description = ?, image = ? WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $name, $price, $description, $image, $product_id);
    
    if ($stmt->execute()) {
        header("Location: index.php");  // Redirect setelah berhasil mengupdate produk
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
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Container -->
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h1 class="text-center">Edit Produk</h1>
            </div>
            <div class="card-body">
                <a href="index.php" class="btn btn-link">Kembali ke Dashboard</a>

                <!-- Form -->
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk:</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga:</label>
                        <input type="number" name="price" id="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi:</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required><?php echo $product['description']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar (URL):</label>
                        <input type="text" name="image" id="image" class="form-control" value="<?php echo $product['image']; ?>" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
