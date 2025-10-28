<!-- FUNCTION EXPORT EXCEL DI TABEL REPORT PRODUKSI TAHUNAN  (EXPORT_EXCEL.PHP) -->

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

// Ambil filter
$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

// Ambil nama line
$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$selectedLine]);
$lineName = $stmt->fetchColumn();

// Ambil target & data
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$selectedLine, $selectedYear]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT 
        MONTH(tanggal) AS bulan,
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
    GROUP BY bulan ORDER BY bulan
");
$stmt->execute([$selectedLine, $selectedYear]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
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

// Inisialisasi Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul besar
$sheet->setCellValue('A1', "DATA TARGET PRODUKSI - {$lineName} ({$selectedYear})");
$sheet->mergeCells('A1:O1'); // sampai kolom Desember (O)
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header kolom
$row = 3;
$sheet->setCellValue("A$row", 'Details');
$sheet->setCellValue("B$row", 'Target');
$sheet->setCellValue("C$row", 'Average');
for ($i = 0; $i < 12; $i++) {
    $col = chr(68 + $i); // kolom D sampai O
    $sheet->setCellValue("{$col}{$row}", $bulan[$i]);
}

// Style untuk header
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical'   => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'CCE5FF']
    ],
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
    ]
];
$sheet->getStyle("A{$row}:O{$row}")->applyFromArray($headerStyle);

// Isi data
$row++;
foreach ($fields as $key => $label) {
    $sheet->setCellValue("A$row", $label);
    $sheet->setCellValue("B$row", $target['target_' . $key] ?? '-');

    // Hitung average tahunan
    $sum = 0;
    $count = 0;
    foreach ($data as $d) {
        if ($d['avg_' . $key] !== null) {
            $sum += $d['avg_' . $key];
            $count++;
        }
    }
    $avg = $count ? round($sum / $count, 2) : '-';
    $sheet->setCellValue("C$row", $avg);

    // Nilai bulanan
    for ($m = 1; $m <= 12; $m++) {
        $bulanRow = array_filter($data, fn($r) => $r['bulan'] == $m);
        $bulanRow = reset($bulanRow);
        $val = $bulanRow ? round($bulanRow['avg_' . $key], 2) : '-';
        $col = chr(68 + $m - 1);
        $sheet->setCellValue("{$col}{$row}", $val);
    }

    // Terapkan border ke baris data
    $sheet->getStyle("A{$row}:O{$row}")->applyFromArray([
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);
    $row++;
}

// Lebar kolom otomatis
foreach (range('A', 'O') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Export file
$filename = "Rangkuman_{$lineName}_{$selectedYear}.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
ob_end_clean(); // tambahkan ini
$writer->save('php://output');
exit;
