<?php
include 'config.php';

$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$selectedLine, $selectedYear]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// AMBIL DATA HARIAN DENGAN RATA-RATA PER SHIFT
$stmt = $pdo->prepare("
    SELECT 
        tanggal,
        -- Hitung rata-rata harian dari semua shift
        SUM(batch_count) AS daily_batch_count,
        SUM(productivity) AS daily_productivity,
        SUM(production_speed) AS daily_production_speed,
        SUM(batch_weight) AS daily_batch_weight,
        SUM(operation_factor) AS daily_operation_factor,
        SUM(cycle_time) AS daily_cycle_time,
        SUM(grade_change_sequence) AS daily_grade_change_sequence,
        SUM(grade_change_time) AS daily_grade_change_time,
        SUM(feed_raw_material) AS daily_feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND YEAR(tanggal) = ?
    GROUP BY tanggal
    ORDER BY tanggal
");
$stmt->execute([$selectedLine, $selectedYear]);
$harianData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DEBUG: Tampilkan data harian untuk troubleshooting
error_log("DEBUG - Total hari dengan data: " . count($harianData));
foreach ($harianData as $index => $day) {
    error_log("Hari " . ($index + 1) . ": " . $day['tanggal'] . " - Productivity: " . $day['daily_productivity']);
}

// KELOMPOKKAN DATA PER BULAN DARI RATA-RATA HARIAN
$bulanData = [];
$totalTahunan = [
    'sum_batch_count' => 0,
    'sum_productivity' => 0,
    'sum_production_speed' => 0,
    'sum_batch_weight' => 0,
    'sum_operation_factor' => 0,
    'sum_cycle_time' => 0,
    'sum_grade_change_sequence' => 0,
    'sum_grade_change_time' => 0,
    'sum_feed_raw_material' => 0,
    'total_hari' => 0
];

foreach ($harianData as $harian) {
    $bulan = date('n', strtotime($harian['tanggal']));

    if (!isset($bulanData[$bulan])) {
        $bulanData[$bulan] = [
            'count' => 0,
            'sum_batch_count' => 0,
            'sum_productivity' => 0,
            'sum_production_speed' => 0,
            'sum_batch_weight' => 0,
            'sum_operation_factor' => 0,
            'sum_cycle_time' => 0,
            'sum_grade_change_sequence' => 0,
            'sum_grade_change_time' => 0,
            'sum_feed_raw_material' => 0
        ];
    }

    // Hitung untuk bulanan
    $bulanData[$bulan]['count']++;
    $bulanData[$bulan]['sum_batch_count'] += $harian['daily_batch_count'];
    $bulanData[$bulan]['sum_productivity'] += $harian['daily_productivity'];
    $bulanData[$bulan]['sum_production_speed'] += $harian['daily_production_speed'];
    $bulanData[$bulan]['sum_batch_weight'] += $harian['daily_batch_weight'];
    $bulanData[$bulan]['sum_operation_factor'] += $harian['daily_operation_factor'];
    $bulanData[$bulan]['sum_cycle_time'] += $harian['daily_cycle_time'];
    $bulanData[$bulan]['sum_grade_change_sequence'] += $harian['daily_grade_change_sequence'];
    $bulanData[$bulan]['sum_grade_change_time'] += $harian['daily_grade_change_time'];
    $bulanData[$bulan]['sum_feed_raw_material'] += $harian['daily_feed_raw_material'];

    // Hitung untuk tahunan (langsung dari data harian)
    $totalTahunan['sum_batch_count'] += $harian['daily_batch_count'];
    $totalTahunan['sum_productivity'] += $harian['daily_productivity'];
    $totalTahunan['sum_production_speed'] += $harian['daily_production_speed'];
    $totalTahunan['sum_batch_weight'] += $harian['daily_batch_weight'];
    $totalTahunan['sum_operation_factor'] += $harian['daily_operation_factor'];
    $totalTahunan['sum_cycle_time'] += $harian['daily_cycle_time'];
    $totalTahunan['sum_grade_change_sequence'] += $harian['daily_grade_change_sequence'];
    $totalTahunan['sum_grade_change_time'] += $harian['daily_grade_change_time'];
    $totalTahunan['sum_feed_raw_material'] += $harian['daily_feed_raw_material'];
    $totalTahunan['total_hari']++;
}

// HITUNG RATA-RATA PER BULAN
$rataBulan = [];
for ($m = 1; $m <= 12; $m++) {
    if (isset($bulanData[$m]) && $bulanData[$m]['count'] > 0) {
        $rataBulan[$m] = [
            'avg_batch_count' => $bulanData[$m]['sum_batch_count'] / $bulanData[$m]['count'],
            'avg_productivity' => $bulanData[$m]['sum_productivity'] / $bulanData[$m]['count'],
            'avg_production_speed' => $bulanData[$m]['sum_production_speed'] / $bulanData[$m]['count'],
            'avg_batch_weight' => $bulanData[$m]['sum_batch_weight'] / $bulanData[$m]['count'],
            'avg_operation_factor' => $bulanData[$m]['sum_operation_factor'] / $bulanData[$m]['count'],
            'avg_cycle_time' => $bulanData[$m]['sum_cycle_time'] / $bulanData[$m]['count'],
            'avg_grade_change_sequence' => $bulanData[$m]['sum_grade_change_sequence'] / $bulanData[$m]['count'],
            'avg_grade_change_time' => $bulanData[$m]['sum_grade_change_time'] / $bulanData[$m]['count'],
            'avg_feed_raw_material' => $bulanData[$m]['sum_feed_raw_material'] / $bulanData[$m]['count']
        ];
    } else {
        $rataBulan[$m] = [
            'avg_batch_count' => null,
            'avg_productivity' => null,
            'avg_production_speed' => null,
            'avg_batch_weight' => null,
            'avg_operation_factor' => null,
            'avg_cycle_time' => null,
            'avg_grade_change_sequence' => null,
            'avg_grade_change_time' => null,
            'avg_feed_raw_material' => null
        ];
    }
}

// HITUNG RATA-RATA TAHUNAN YANG BENAR
$averages = [];
if ($totalTahunan['total_hari'] > 0) {
    $averages = [
        'batch_count' => round($totalTahunan['sum_batch_count'] / $totalTahunan['total_hari'], 2),
        'productivity' => round($totalTahunan['sum_productivity'] / $totalTahunan['total_hari'], 2),
        'production_speed' => round($totalTahunan['sum_production_speed'] / $totalTahunan['total_hari'], 2),
        'batch_weight' => round($totalTahunan['sum_batch_weight'] / $totalTahunan['total_hari'], 2),
        'operation_factor' => round($totalTahunan['sum_operation_factor'] / $totalTahunan['total_hari'], 2),
        'cycle_time' => round($totalTahunan['sum_cycle_time'] / $totalTahunan['total_hari'], 2),
        'grade_change_sequence' => round($totalTahunan['sum_grade_change_sequence'] / $totalTahunan['total_hari'], 2),
        'grade_change_time' => round($totalTahunan['sum_grade_change_time'] / $totalTahunan['total_hari'], 2),
        'feed_raw_material' => round($totalTahunan['sum_feed_raw_material'] / $totalTahunan['total_hari'], 2)
    ];
} else {
    $averages = [
        'batch_count' => '-',
        'productivity' => '-',
        'production_speed' => '-',
        'batch_weight' => '-',
        'operation_factor' => '-',
        'cycle_time' => '-',
        'grade_change_sequence' => '-',
        'grade_change_time' => '-',
        'feed_raw_material' => '-'
    ];
}

// field mapping
$fields = [
    'batch_count' => ['Batch Count', 'Per day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'production_speed' => ['Production Speed', 'Kg/Min'],
    'batch_weight' => ['Batch Weight', 'Kg/Batch'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'Min/Batch'],
    'grade_change_sequence' => ['Grade Change Sequence', 'Frequently'],
    'grade_change_time' => ['Grade Change Speed', 'Min/Grade'],
    'feed_raw_material' => ['Feed Raw Material', 'Kg/Day']
];

$namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
?>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Details</th>
            <th>Unit</th>
            <th>Target</th>
            <th>Average</th>
            <?php foreach ($namaBulan as $b): ?>
                <th><?= $b ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fields as $key => [$label, $unit]): ?>
            <tr>
                <td><?= $label ?></td>
                <td><?= $unit ?></td>
                <td><?= $target['target_' . $key] ?? '-' ?></td>
                <td><strong><?= $averages[$key] ?></strong></td>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <td>
                        <?php
                        if ($rataBulan[$m]['avg_' . $key] !== null) {
                            echo round($rataBulan[$m]['avg_' . $key], 2);
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- INFO DEBUG -->
<div class="mt-3 p-2 bg-light rounded small">
    <strong>Info Perhitungan:</strong><br>
    - Total Hari Produksi: <?= $totalTahunan['total_hari'] ?> hari<br>
    - Rata-rata Tahunan: Total semua hari / <?= $totalTahunan['total_hari'] ?> hari<br>
    - Rata-rata Bulanan: Total hari dalam bulan / jumlah hari produksi bulan tersebut
</div>