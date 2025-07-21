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

// Pastikan method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

try {
    // Validasi input
    $required_fields = ['rencanaKerja', 'rencanaAksi', 'output', 'pjk', 'target_bulan', 'progress'];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            echo json_encode(['success' => false, 'message' => 'Field ' . $field . ' wajib diisi']);
            exit();
        }
    }

    // Validasi progress
    $progress = (int) $_POST['progress'];
    if ($progress < 0 || $progress > 100) {
        echo json_encode(['success' => false, 'message' => 'Progress harus antara 0-100']);
        exit();
    }

    // Validasi target bulan
    $target_bulan = $_POST['target_bulan'] . '-01'; // Tambah tanggal 1
    if (!strtotime($target_bulan)) {
        echo json_encode(['success' => false, 'message' => 'Format target bulan tidak valid']);
        exit();
    }

    // Tentukan status berdasarkan progress
    $status = 'pending';
    if ($progress > 0 && $progress < 100) {
        $status = 'in_progress';
    } elseif ($progress == 100) {
        $status = 'completed';
    }

    // Insert data
    $stmt = $pdo->prepare("
        INSERT INTO monitoring_data 
        (rencana_kerja, rencana_aksi, output, pjk, target_bulan, bukti_link, progress, status, created_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $result = $stmt->execute([
        clean_input($_POST['rencanaKerja']),
        clean_input($_POST['rencanaAksi']),
        clean_input($_POST['output']),
        clean_input($_POST['pjk']),
        $target_bulan,
        clean_input($_POST['bukti_link']),
        $progress,
        $status,
        $_SESSION['user_id']
    ]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'id' => $pdo->lastInsertId()
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan data']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}