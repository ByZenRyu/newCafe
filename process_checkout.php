<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db.php';

    $name = $_POST['name'];
    $table = $_POST['table'];
    $total = isset($_POST['total']) ? $_POST['total'] : 0;
    $products = isset($_POST['products']) ? $_POST['products'] : []; 
    $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

    if (empty($products)) {
        echo "<script>alert('Silakan pilih produk terlebih dahulu!'); window.location='shop.php';</script>";
        exit;
    }

    // **1. Simpan order utama**
    $query = $conn->prepare("INSERT INTO orders (name, table_number, total_price) VALUES (?, ?, ?)");
    $query->bind_param("ssi", $name, $table, $total);
    $query->execute();
    $order_id = $conn->insert_id;

    // **2. Simpan semua produk ke order_items**
    $query = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");

    foreach ($products as $index => $product_id) {
        $qty = isset($quantities[$index]) ? (int)$quantities[$index] : 1;
        $query->bind_param("iii", $order_id, $product_id, $qty);
        $query->execute();
    }

    echo "<script>window.location = 'shop.php?success=true';</script>";
    exit;
}
?>
