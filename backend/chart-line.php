<?php
require_once 'config.php';

$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');

$data = ['lines' => []];

// Ambil semua line produksi dari DB
$lines = $pdo->query("SELECT id, nama_line FROM line_produksi ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

foreach ($lines as $line) {
    $lineId = $line['id'];
    $namaLine = $line['nama_line'];

    // Ambil data produksi per hari untuk tiap line
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
    $data['lines'][$namaLine] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($data);
