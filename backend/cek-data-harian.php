<?php
include 'config.php';

$tanggal = $_GET['tanggal'] ?? '';
$line_id = $_GET['line_id'] ?? '';
$shift_id = $_GET['shift_id'] ?? '';

if (empty($tanggal) || empty($line_id) || empty($shift_id)) {
    echo json_encode(['exists' => false]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            ih.tanggal,
            lp.kode_line,
            lp.nama_line,
            ms.kode_shift,
            ms.nama_shift
        FROM input_harian ih
        JOIN line_produksi lp ON ih.line_id = lp.id
        JOIN master_shift ms ON ih.shift_id = ms.id
        WHERE ih.tanggal = ? AND ih.line_id = ? AND ih.shift_id = ?
    ");

    $stmt->execute([$tanggal, $line_id, $shift_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        echo json_encode([
            'exists' => true,
            'tanggal' => $existing['tanggal'],
            'line' => $existing['kode_line'] . ' - ' . $existing['nama_line'],
            'shift' => $existing['kode_shift'] . ' - ' . $existing['nama_shift']
        ]);
    } else {
        echo json_encode(['exists' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['exists' => false, 'error' => $e->getMessage()]);
}
