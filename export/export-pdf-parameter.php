<?php
include '../backend/config.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('n'); // ✅ ambil bulan
$tahun = $_GET['tahun'] ?? date('Y');

// Nama bulan (biar rapi di judul)
$namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

// Ambil nama line
$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$line]);
$lineName = $stmt->fetchColumn();

// Ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data aktual (average dari input_harian per bulan)
$stmt = $pdo->prepare("
    SELECT 
        AVG(batch_count) AS avg_batch_count,
        AVG(productivity) AS avg_productivity,
        AVG(operation_factor) AS avg_operation_factor,
        AVG(cycle_time) AS avg_cycle_time,
        AVG(grade_change_time) AS avg_grade_change_time
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
");
$stmt->execute([$line, $bulan, $tahun]);
$actual = $stmt->fetch(PDO::FETCH_ASSOC);

// Field mapping
$fields = [
    'batch_count' => ['Batch Count', 'per day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'min/Batch'],
    'grade_change_time' => ['Grade Change Time', 'min/grade']
];

// Generate HTML
$html = "
<h3 style='text-align:center; margin-bottom:10px;'>
📈 PARAMETER LINE - {$lineName} ({$namaBulan} {$tahun})
</h3>
<table border='1' cellspacing='0' cellpadding='6' width='100%' 
    style='border-collapse:collapse; font-size:11px; text-align:center'>
<thead style='background:#cce5ff; font-weight:bold;'>
<tr>
    <th>Parameter Check</th>
    <th>Target</th>
    <th>Actual</th>
    <th>Hasil (%)</th>
</tr>
</thead>
<tbody>";

foreach ($fields as $key => [$label, $unit]) {
    $targetVal = isset($target['target_' . $key]) ? floatval($target['target_' . $key]) : 0;
    $actualVal = isset($actual['avg_' . $key]) ? round($actual['avg_' . $key], 2) : 0;
    $hasil = $targetVal > 0 ? round(($actualVal / $targetVal) * 100, 1) : 0;

    $html .= "
    <tr>
        <td>{$label}</td>
        <td>{$targetVal}</td>
        <td>{$actualVal}</td>
        <td>{$hasil}%</td>
    </tr>";
}

$html .= "</tbody></table>";

// Output PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Parameter-{$lineName}-{$namaBulan}-{$tahun}.pdf", ["Attachment" => true]);
exit;
