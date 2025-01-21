<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kasir') {
    header("Location: ../login.php");
    exit();
}

include '../db.php';

// Inisialisasi variabel
$totalOrders = 0;
$totalSales = 0;

try {
    // Ambil jumlah total order
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_orders, SUM(total_price) AS total_sales FROM orders WHERE status = 'paid'");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $data = $result->fetch_assoc();
        $totalOrders = $data['total_orders'] ?? 0;
        $totalSales = $data['total_sales'] ?? 0;
    }
} catch (Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Kasir Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
    <h1 class="mb-4">Selamat Datang, Kasir!</h1>

    <!-- Pencarian -->
    <form action="" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Cari nama pembeli..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
    </form>

    <!-- Total Orders dan Total Sales (sama seperti sebelumnya) -->

    <!-- Recent Orders -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Order Terbaru
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Nama Pemesan</th>
                        <th>Nomor Meja</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil parameter pencarian dari URL jika ada
                    $search = isset($_GET['search']) ? $_GET['search'] : '';

                    try {
                        // Modifikasi query untuk mencari berdasarkan nama pembeli
                        if ($search) {
                            $stmtRecent = $conn->prepare("SELECT * FROM orders WHERE name LIKE ? ORDER BY created_at DESC LIMIT 5");
                            $searchTerm = '%' . $search . '%';
                            $stmtRecent->bind_param("s", $searchTerm);
                        } else {
                            $stmtRecent = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
                        }
                        
                        $stmtRecent->execute();
                        $recentResult = $stmtRecent->get_result();
                        
                        while ($row = $recentResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['order_id']); ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><?= htmlspecialchars($row['table_number']); ?></td>
                                <td>Rp<?= number_format($row['total_price'], 0, ',', '.'); ?></td>
                                <td><?= htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <a href="kasir_order.php?id=<?= $row['order_id']; ?>" class="btn btn-info">Bayar</a>
                                </td>
                            </tr>
                        <?php endwhile;
                    } catch (Exception $e) {
                        echo "<tr><td colspan='6'>Error fetching recent orders: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
