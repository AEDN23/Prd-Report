<!-- FUNGSI UNTUK MENAMPILKAN DATA TABEL HARIAN DI HALAMAN DASHBOARD -->
<?php
include 'config.php';

$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// ambil data harian
$stmt = $pdo->prepare("
    SELECT 
        DAY(tanggal) AS hari,
        batch_count,
        productivity,
        production_speed,
        batch_weight,
        operation_factor,
        cycle_time,
        grade_change_sequence,
        grade_change_time,
        feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    ORDER BY tanggal ASC
");
$stmt->execute([$line, $bulan, $tahun]);

$data = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[$r['hari']] = $r;
}

// daftar field dan unit
$fields = [
    'batch_count' => ['Batch Count', 'per day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'production_speed' => ['Production Speed', 'Kg/min'],
    'batch_weight' => ['Batch Weight', 'Kg/Batch'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'min/Batch'],
    'grade_change_sequence' => ['Grade Change Sequence', 'frequenly'],
    'grade_change_time' => ['Grade Change Time', 'min/grade'],
    'feed_raw_material' => ['Feed Raw Material', 'Kg/Day']
];

// hitung average per field
$averages = [];
foreach ($fields as $key => $v) {
    $sum = 0;
    $count = 0;
    foreach ($data as $hari => $val) {
        if (!empty($val[$key])) {
            $sum += $val[$key];
            $count++;
        }
    }
    $averages[$key] = $count ? round($sum / $count, 2) : '-';
}
?>

<table class="table table-bordered table-sm">
    <thead class="table-primary text-center align-middle">
        <tr>
            <th>Details</th>
            <th>Unit</th>
            <th>Target</th>
            <th>Average</th>
            <?php for ($d = 1; $d <= 31; $d++): ?>
                <th><?= $d ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody class="text-center align-middle">
        <?php foreach ($fields as $key => [$label, $unit]): ?>
            <?php $targetVal = isset($target['target_' . $key]) ? floatval($target['target_' . $key]) : 0; ?>
            <tr>
                <td><?= htmlspecialchars($label) ?></td>
                <td><?= htmlspecialchars($unit) ?></td>
                <td style="color: black; font-weight:bold;"><?= $targetVal ?: '-' ?></td>
                <?php
                $targetVal = isset($target['target_' . $key]) ? floatval($target['target_' . $key]) : 0;
                $avgVal = $averages[$key];
                $color = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                ?>
                <td style="color: <?= $color ?>; font-weight:;"><?= $avgVal ?></td>


                <?php for ($d = 1; $d <= 31; $d++): ?>
                    <?php
                    $val = $data[$d][$key] ?? null;
                    $style = '';

                    // kalau ada nilai dan target > 0
                    if ($val !== null && $targetVal > 0) {
                        if ($val < $targetVal) {
                            $style = 'style="color:red;font-weight:bold"';
                        } else {
                            $style = 'style="color:black;"';
                        }
                    }
                    ?>
                    <td <?= $style ?>><?= $val ?? '-' ?></td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>