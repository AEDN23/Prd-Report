<?php
require_once 'config.php';

$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');
$lineId = $_GET['line'] ?? null;

$data = ['lines' => []];

if ($lineId) {
    // Jika spesifik line diminta
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            batch_count,
            productivity,
            production_speed,
            feed_raw_material
        FROM input_harian
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        ORDER BY hari
    ");
    $stmt->execute([$lineId, $bulan, $tahun]);
    $data['lines'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Default: ambil semua line
    $lines = $pdo->query("SELECT id, nama_line FROM line_produksi ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($lines as $line) {
        $stmt = $pdo->prepare("
            SELECT 
                DAY(tanggal) AS hari,
                batch_count,
                productivity,
                production_speed,
                feed_raw_material
            FROM input_harian
            WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
            ORDER BY hari
        ");
        $stmt->execute([$line['id'], $bulan, $tahun]);
        $data['lines'][$line['nama_line']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

header('Content-Type: application/json');
echo json_encode($data);
