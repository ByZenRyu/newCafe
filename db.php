<?php
session_start(); // Start the session for login handling

$host = "localhost";
$user = "root";
$pass = "bayu";
$dbname = "ren";  // Replace with your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get a product by its ID
function getProduct($product_id) {
    global $conn; // Use the global connection variable

    // Sanitize the input to avoid SQL Injection
    $product_id = (int)$product_id;

    // Use prepared statements for security
    $query = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $query->bind_param("i", $product_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Fetch product data
        return $result->fetch_assoc();
    } else {
        return null; // No product found
    }
}

// Get all products
function allProduct() {
    global $conn;
    $query = "SELECT * FROM product";
    $result = $conn->query($query);
    
    $products = []; // Initialize an array to store products

    while ($row = $result->fetch_assoc()) {
        // Collect product details into an associative array
        $product = [
            'product_id' => (int)$row['product_id'],
            'name' => (string)$row['name'],
            'price' => (float)$row['price'],
            'description' => (string)$row['description'],
            'image' => (string)$row['image']
        ];
        
        // Add the product to the products array
        $products[] = $product;
    }

    // Return the array of products
    return $products;
}

// Login function for admin and chasier
function login($username, $password, ) {
    global $conn;

    // Menghindari SQL Injection dengan prepared statements
    $password = md5($password); // Hash password sebelum dikirim ke query

    // Menggunakan placeholder "?" untuk parameter yang akan di-bind
    $query = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $query->bind_param("ss", $username, $password);  // "sss" artinya ketiga parameter adalah string

    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // User ditemukan, login berhasil
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];  // Menyimpan username di session

        // Pengalihan berdasarkan jenis pengguna
        if ($user['username'] === 'admin') {
            header("Location: admin/index.php");  // Redirect ke dashboard admin
        } elseif ($user['username'] === 'kasir') {
            header("Location: kasir/index.php");  // Redirect ke dashboard kasir
        }
        elseif ($user['username'] === 'koki') {
            header("Location: koki/index.php");  // Redirect ke dashboard kasir
        }
        exit();  // Pastikan eksekusi berhenti setelah redirect
    } else {
        return "Username, password, atau email salah!";  // Pesan kesalahan jika login gagal
    }
}
