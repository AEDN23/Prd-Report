<!-- FUNCTION PDF EXCEL DI TABEL REPORT PRODUKSI PARAMETER (export-pdf-parameter.php)-->


<?php
include '../backend/config.php';
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;

$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$selectedLine]);
$lineName = $stmt->fetchColumn();

// Ambil data rata-rata tahunan
$stmt = $pdo->prepare("
    SELECT 
        AVG(batch_count) AS avg_batch_count,
        AVG(productivity) AS avg_productivity,
        AVG(production_speed) AS avg_production_speed,
        AVG(batch_weight) AS avg_batch_weight,
        AVG(operation_factor) AS avg_operation_factor,
        AVG(cycle_time) AS avg_cycle_time,
        AVG(grade_change_sequence) AS avg_grade_change_sequence,
        AVG(grade_change_time) AS avg_grade_change_time,
        AVG(feed_raw_material) AS avg_feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND YEAR(tanggal) = ?
");
$stmt->execute([$selectedLine, $selectedYear]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$fields = [
    'batch_count' => 'Batch Count',
    'productivity' => 'Productivity',
    'production_speed' => 'Production Speed',
    'batch_weight' => 'Batch Weight',
    'operation_factor' => 'Operation Factor',
    'cycle_time' => 'Cycle Time',
    'grade_change_sequence' => 'Grade Change Sequence',
    'grade_change_time' => 'Grade Change Time',
    'feed_raw_material' => 'Feed Raw Material'
];

$html = "<h3 style='text-align:center'>PARAMETER LINE - {$lineName} ({$selectedYear})</h3>
<table border='1' cellspacing='0' cellpadding='4' width='100%' style='font-size:11px; border-collapse:collapse;'>
<tr style='background:#cce5ff;'><th>Parameter</th><th>Average</th></tr>";

foreach ($fields as $k => $v) {
    $val = $data['avg_' . $k] ? round($data['avg_' . $k], 2) : '-';
    $html .= "<tr><td>{$v}</td><td>{$val}</td></tr>";
}
$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("ParameterLine_{$lineName}_{$selectedYear}.pdf", ["Attachment" => true]);
exit;
?>
    