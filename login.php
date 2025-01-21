<?php
require_once ("db.php");

if (isset($_POST['submit'])) {  // Memeriksa apakah tombol submit telah ditekan
    $username = $_POST['username'];
    $password = $_POST['password'];
    $error = login($username, $password);  // Panggil fungsi login

    if ($error) {
        echo "<p class='text-red-500'>$error</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Menyertakan Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-2">
            <div class="flex items-center justify-between">
                <a href="#" class="text-lg font-semibold text-gray-800">Cafetaria PPLG</a>
            </div>
        </div>
    </nav>

    <!-- Form Login -->
    <div class="max-w-sm mx-auto mt-16 p-8 bg-white rounded-lg shadow-md">
        <h2 class="text-center text-2xl font-bold mb-6">Login</h2>

        <form action="" method="POST">
            <!-- Username Field -->
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="username" id="username" required>
            </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="password" id="password" required>
            </div>

            <!-- Login Button -->
            <div>
                <button type="submit" name="submit" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
            </div>

            <!-- Display error message -->
            <?php if (isset($error)) { ?>
                <div class="mt-3 text-red-500">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
        </form>
    </div>

</body>
</html>
