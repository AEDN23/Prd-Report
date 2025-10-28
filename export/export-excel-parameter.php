<!-- FUNCTION EXPORT EXCEL DI TABEL REPORT PRODUKSI PARAMETER -->


<?php
require '../vendor/autoload.php';
include '../backend/config.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$selectedLine]);
$lineName = $stmt->fetchColumn();

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

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', "PARAMETER LINE - {$lineName} ({$selectedYear})");
$sheet->mergeCells('A1:B1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$row = 3;
$sheet->fromArray(['Parameter', 'Average'], null, "A{$row}");
$sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCE5FF']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

$row++;
foreach ($fields as $k => $v) {
    $sheet->setCellValue("A{$row}", $v);
    $sheet->setCellValue("B{$row}", $data['avg_' . $k] ? round($data['avg_' . $k], 2) : '-');
    $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);
    $row++;
}

foreach (['A','B'] as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

$filename = "ParameterLine_{$lineName}_{$selectedYear}.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
