
<?php
include 'config.php';

$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$selectedLine, $selectedYear]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// ambil data rata-rata per bulan
$stmt = $pdo->prepare("
    SELECT MONTH(tanggal) AS bulan,
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
    GROUP BY bulan
");
$stmt->execute([$selectedLine, $selectedYear]);
$bulanData = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $bulanData[$r['bulan']] = $r;
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

// hitung average tahunan
$averages = [];
foreach ($fields as $key => $v) {
    $sum = 0;
    $count = 0;
    foreach ($bulanData as $b) {
        if ($b['avg_' . $key] !== null) {
            $sum += $b['avg_' . $key];
            $count++;
        }
    }
    $averages[$key] = $count ? round($sum / $count, 2) : '-';
}
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
                <td><?= $averages[$key] ?></td>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <td><?= isset($bulanData[$m]['avg_' . $key]) ? round($bulanData[$m]['avg_' . $key], 2) : '-' ?></td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>