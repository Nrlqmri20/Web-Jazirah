<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once '../auth.php';

// Pastikan user sudah login
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    // Jika ada parameter ID, ambil data spesifik
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM monitoring_data WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetchAll();
    } else {
        // Ambil semua data dengan informasi user yang membuat
        $stmt = $pdo->prepare("
            SELECT md.*, u.nama_lengkap as created_by_name 
            FROM monitoring_data md 
            LEFT JOIN users u ON md.created_by = u.id 
            ORDER BY md.created_at DESC
        ");
        $stmt->execute();
        $data = $stmt->fetchAll();
    }

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}