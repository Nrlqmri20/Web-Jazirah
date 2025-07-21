<?php
// Konfigurasi Database
$host = 'localhost';  // Host database
$dbname = 'monitoring_zi_aceh';  // Nama database
$username = 'root';  // Username database
$password = '';  // Password database (sesuaikan dengan setup Anda)

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Set error mode ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set fetch mode default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Jika koneksi gagal
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi untuk membersihkan input
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk hash password
function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

// Fungsi untuk verifikasi password
function verify_password($password, $hash)
{
    return password_verify($password, $hash);
}
?>