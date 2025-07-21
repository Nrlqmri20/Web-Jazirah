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
    // Hitung total kegiatan
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM monitoring_data");
    $total = $stmt->fetch()['total'];
    
    // Hitung kegiatan yang sedang berjalan
    $stmt = $pdo->query("SELECT COUNT(*) as ongoing FROM monitoring_data WHERE status = 'in_progress'");
    $ongoing = $stmt->fetch()['ongoing'];
    
    // Hitung kegiatan yang selesai
    $stmt = $pdo->query("SELECT COUNT(*) as completed FROM monitoring_data WHERE status = 'completed'");
    $completed = $stmt->fetch()['completed'];
    
    // Hitung rata-rata progress
    $stmt = $pdo->query("SELECT AVG(progress) as avg_progress FROM monitoring_data");
    $avgProgress = $stmt->fetch()['avg_progress'];
    $avgProgress = $avgProgress ? round($avgProgress, 1) : 0;
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'total' => (int)$total,
            'ongoing' => (int)$ongoing,
            'completed' => (int)$completed,
            'avgProgress' => $avgProgress
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}