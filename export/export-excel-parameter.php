<!-- FUNCTION EXPORT EXCEL DI TABEL REPORT PRODUKSI PARAMETER (export-excel-parameter.php) -->
<?php
ob_clean();
ob_start();
require '../vendor/autoload.php';
include '../backend/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('n'); // ✅ ambil bulan dari parameter
$tahun = $_GET['tahun'] ?? date('Y');

// Konversi nama bulan biar lebih enak dibaca
$namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

// Ambil nama line
$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$line]);
$lineName = $stmt->fetchColumn();

// Ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data actual (average per bulan)
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

// Buat Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', "📈 PARAMETER LINE - {$lineName} ({$namaBulan} {$tahun})");
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header
$row = 3;
$sheet->fromArray(['Parameter Check', 'Target', 'Actual', 'Hasil (%)'], null, "A{$row}");
$sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCE5FF']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Isi data
$row++;
foreach ($fields as $key => [$label, $unit]) {
    $targetVal = isset($target['target_' . $key]) ? floatval($target['target_' . $key]) : 0;
    $actualVal = isset($actual['avg_' . $key]) ? round($actual['avg_' . $key], 2) : 0;
    $hasil = $targetVal > 0 ? round(($actualVal / $targetVal) * 100, 1) : 0;

    $sheet->setCellValue("A{$row}", $label);
    $sheet->setCellValue("B{$row}", $targetVal);
    $sheet->setCellValue("C{$row}", $actualVal);
    $sheet->setCellValue("D{$row}", "{$hasil}%");

    $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);
    $row++;
}

// Lebar kolom otomatis
foreach (['A', 'B', 'C', 'D'] as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Export
$filename = "Parameter-{$lineName}-{$namaBulan}_{$tahun}.xlsx"; 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
ob_end_clean(); 
$writer->save('php://output');
exit;
