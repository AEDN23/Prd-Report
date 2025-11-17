<?php
include 'config.php';

$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT 
        DAY(tanggal) AS hari,
        tanggal as full_tanggal,
        SUM(batch_count) as batch_count,
        SUM(productivity) as productivity,
        SUM(production_speed) as production_speed,
        SUM(batch_weight) as batch_weight,
        SUM(operation_factor) as operation_factor,
        SUM(cycle_time) as cycle_time,
        SUM(grade_change_sequence) as grade_change_sequence,
        SUM(grade_change_time) as grade_change_time,
        SUM(feed_raw_material) as feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    GROUP BY tanggal
    ORDER BY tanggal ASC
");
$stmt->execute([$line, $bulan, $tahun]);

$data = [];
$dataFullTanggal = [];

// Untuk edit, kita perlu ambil semua ID per tanggal (semua shift)
$stmtIds = $pdo->prepare("
    SELECT DAY(tanggal) AS hari, GROUP_CONCAT(id) as ids
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    GROUP BY tanggal
    ORDER BY tanggal ASC
");
$stmtIds->execute([$line, $bulan, $tahun]);
$dataIds = [];
while ($r = $stmtIds->fetch(PDO::FETCH_ASSOC)) {
    $dataIds[$r['hari']] = $r['ids'];
}

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[$r['hari']] = $r;
    $dataFullTanggal[$r['hari']] = $r['full_tanggal'];
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

// hitung average per field dengan format 2 digit
$averages = [];
foreach ($fields as $key => $v) {
    $sum = 0;
    $count = 0;
    foreach ($data as $hari => $val) {
        if (!empty($val[$key]) && $val[$key] != 0) {
            $sum += $val[$key];
            $count++;
        }
    }
    $averages[$key] = $count ? number_format($sum / $count, 2) : '-';
}
?>

<table class="table table-bordered table-sm">
    <thead class="table-primary text-center align-middle">
        <tr>
            <th>Details</th>
            <th>Unit</th>
            <th>Target</th>
            <th>Average</th>
            <th>Hasil (%)</th>
            <?php for ($d = 1; $d <= 31; $d++): ?>
                <th><?= $d ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody class="text-center align-middle">
        <?php foreach ($fields as $key => [$label, $unit]): ?>
            <?php
            $targetVal = isset($target['target_' . $key]) ? floatval($target['target_' . $key]) : 0;
            $avgVal = $averages[$key];

            // Format hasil persentase
            if ($avgVal !== '-' && $targetVal > 0) {
                $hasilValue = round(($avgVal / $targetVal) * 100, 1);
                $hasil = number_format($hasilValue, 1) . '%';
                $color = $hasilValue < 100 ? 'red' : 'black';
            } else {
                $hasil = '-';
                $color = 'black';
            }
            ?>
            <tr>
                <td><?= htmlspecialchars($label) ?></td>
                <td><?= htmlspecialchars($unit) ?></td>
                <td style="color: black; font-weight:bold;">
                    <?= $targetVal ? number_format($targetVal, 2) : '-' ?>
                </td>
                <td style="color: <?= $color ?>;">
                    <?= $avgVal ?>
                </td>
                <td style="color: <?= $color ?>; font-weight:bold;">
                    <?= $hasil ?>
                </td>

                <?php for ($d = 1; $d <= 31; $d++): ?>
                    <?php
                    $val = $data[$d][$key] ?? null;
                    $style = '';
                    $displayVal = '-';

                    if ($val !== null && $val != 0) {
                        // Format semua nilai menjadi 2 digit
                        $displayVal = number_format($val, 2);

                        if ($targetVal > 0) {
                            if ($val < $targetVal) {
                                $style = 'style="color:red;font-weight:bold"';
                            } else {
                                $style = 'style="color:black;"';
                            }
                        }
                    }
                    ?>
                    <td <?= $style ?>><?= $displayVal ?></td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; ?>

        <tr class="table-warning">
            <td colspan="5"><strong> Edit Data per Shift</strong></td>
            <?php for ($d = 1; $d <= 31; $d++): ?>
                <td>
                    <?php if (isset($dataIds[$d])): ?>
                        <?php
                        $ids = explode(',', $dataIds[$d]);
                        if (count($ids) > 0):
                        ?>
                            <div class="btn-group-vertical btn-group-sm">
                                <?php foreach ($ids as $index => $id): ?>
                                    <a href="../dashboard/edit-harian.php?id=<?= $id ?>"
                                        class="btn btn-outline-primary btn-sm mb-1"
                                        title="Edit data shift"
                                        style="padding: 0.1rem 0.4rem; font-size: 0.7rem;">
                                        <i class="fas fa-edit"></i> Shift <?= $index + 1 ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
    </tbody>
</table>