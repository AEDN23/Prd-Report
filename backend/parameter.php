<?php
include 'config.php';
// BACKLINK DARI ../produksi-report/INDEX.php
// BAGIAN PARAMETER
$line = $_GET['line'] ?? 1;
$tahun = $_GET['tahun'] ?? date('Y');

// --- Ambil Target ---
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// --- Ambil Data Actual (Average dari input_harian) ---
$stmt = $pdo->prepare("
    SELECT 
        AVG(batch_count) AS avg_batch_count,
        AVG(productivity) AS avg_productivity,
        AVG(operation_factor) AS avg_operation_factor,
        AVG(cycle_time) AS avg_cycle_time,
        AVG(grade_change_time) AS avg_grade_change_time
    FROM input_harian
    WHERE line_id = ? AND YEAR(tanggal) = ?
");
$stmt->execute([$line, $tahun]);
$actual = $stmt->fetch(PDO::FETCH_ASSOC);

// --- Field Mapping ---
$fields = [
    'batch_count' => ['Batch Count', 'per day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'min/Batch'],
    'grade_change_time' => ['Grade Change Time', 'min/grade']
];

// --- Generate Table Rows ---
?>
<table class="table table-bordered  table-sm">
    <thead class="table-primary text-center">
        <tr>
            <th>Parameter Check</th>
            <th>Target</th>
            <th>Actual</th>
            <th>Hasil (%)</th>
        </tr>
    </thead>
    <tbody class="text-center">
        <?php foreach ($fields as $key => [$label, $unit]):
            $targetVal = isset($target['target_' . $key]) ? floatval($target['target_' . $key]) : 0;
            $actualVal = isset($actual['avg_' . $key]) ? round($actual['avg_' . $key], 2) : 0;
            $hasil = $targetVal > 0 ? round(($actualVal / $targetVal) * 100, 1) : 0;
        ?>
            <tr>
                <td><?= $label ?></td>
                <td><?= $targetVal ?></td>
                <td><?= $actualVal ?></td>
                <td><?= $hasil ?>%</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>