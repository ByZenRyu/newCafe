<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'koki') {
    header("Location: ../login.php");
    exit();
}

require_once(__DIR__ . "/../db.php");


// Menangani error koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} 

// Query untuk mengambil data pesanan
$query = "
    SELECT 
        orders.order_id, 
        orders.name AS customer_name, 
        orders.table_number, 
        GROUP_CONCAT(order_items.quantity ORDER BY product.name) AS quantities,
        GROUP_CONCAT(product.name ORDER BY product.name) AS product_names,
        SUM(order_items.quantity * product.price) AS total_price,
        orders.created_at, 
        orders.order_status
    FROM orders 
    JOIN order_items ON orders.order_id = order_items.order_id
    JOIN product ON order_items.product_id = product.product_id
    WHERE orders.status = 'pending'
    GROUP BY orders.order_id
    ORDER BY orders.created_at DESC
";




$result = $conn->query($query);
if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Koki</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Daftar Pesanan - Koki</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Daftar Pesanan</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Status pesanan berhasil diperbarui!</div>
        <?php endif; ?>

        <?php if ($result->num_rows === 0): ?>
            <p class="text-center">Tidak ada pesanan saat ini.</p>
        <?php else: ?>
            <table id="pesananTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor Meja</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Waktu Pemesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['order_id']); ?></td>
                            <td><?= htmlspecialchars($row['customer_name']); ?></td>
                            <td><?= htmlspecialchars($row['table_number']); ?></td>
                            <td><?= htmlspecialchars($row['product_names']); ?></td>
                            <td><?= htmlspecialchars($row['quantities']); ?></td>
                            <td>Rp<?= number_format($row['total_price'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge bg-<?= $row['order_status'] == 'Sedang antri' ? 'warning' : ($row['order_status'] == 'Sedang Dimasak' ? 'primary' : 'success') ?>">
                                    <?= htmlspecialchars($row['order_status']); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php if ($row['order_status'] == 'Sedang antri'): ?>
                                        <a href="update_order_status.php?id=<?= $row['order_id']; ?>&status=Sedang Dimasak" class="btn btn-primary btn-sm">Sedang Dimasak</a>
                                        <a href="update_order_status.php?id=<?= $row['order_id']; ?>&status=Selesai" class="btn btn-success btn-sm">Selesai</a>
                                    <?php elseif ($row['order_status'] == 'Sedang Dimasak'): ?>
                                        <a href="update_order_status.php?id=<?= $row['order_id']; ?>&status=Selesai" class="btn btn-success btn-sm">Selesai</a>
                                        <a href="update_order_status.php?id=<?= $row['order_id']; ?>&status=Sedang antri" class="btn btn-warning btn-sm">Tandai Sedang Antri</a>
                                    <?php elseif ($row['order_status'] == 'Selesai'): ?>
                                        <a href="update_order_status.php?id=<?= $row['order_id']; ?>&status=Sedang antri" class="btn btn-warning btn-sm">Tandai Sedang Antri</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pesananTable').DataTable();
        });
    </script>
</body>
</html>
