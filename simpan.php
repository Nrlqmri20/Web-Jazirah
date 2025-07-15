<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "database_jazirah"; // ganti dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$kerja = $_POST['rencana_kerja'];
$aksi = $_POST['rencana_aksi'];
$output = $_POST['output'];
$pjk = $_POST['pjk'];
$bulan = $_POST['target_bulan'];
$link = $_POST['bukti_link'];
$progress = $_POST['progress'];

$sql = "INSERT INTO database_jazirah (rencana_kerja, rencana_aksi, output, pjk, target_bulan, bukti_link, progress)
        VALUES ('$kerja', '$aksi', '$output', '$pjk', '$bulan', '$link', '$progress')";

if ($conn->query($sql) === TRUE) {
  echo "Data berhasil disimpan.";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
