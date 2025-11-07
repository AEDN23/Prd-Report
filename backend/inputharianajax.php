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

// ambil data harian dengan ID
$stmt = $pdo->prepare("
    SELECT 
        DAY(tanggal) AS hari,
        tanggal as full_tanggal,
        batch_count,
        productivity,
        production_speed,
        batch_weight,
        operation_factor,
        cycle_time,
        grade_change_sequence,
        grade_change_time,
        feed_raw_material,
        id
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    ORDER BY tanggal ASC
");
$stmt->execute([$line, $bulan, $tahun]);

$data = [];
$dataIds = []; // Untuk menyimpan ID data per tanggal
$dataFullTanggal = []; // Untuk menyimpan tanggal lengkap
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[$r['hari']] = $r;
    $dataIds[$r['hari']] = $r['id']; // Simpan ID untuk edit
    $dataFullTanggal[$r['hari']] = $r['full_tanggal']; // Simpan tanggal lengkap
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
            $hasil = ($avgVal !== '-' && $targetVal > 0) ? round(($avgVal / $targetVal) * 100, 1) : '-';
            $color = ($hasil !== '-' && $hasil < 100) ? 'red' : 'black';
            ?>
            <tr>
                <td><?= htmlspecialchars($label) ?></td>
                <td><?= htmlspecialchars($unit) ?></td>
                <td style="color: black; font-weight:bold;"><?= $targetVal ?: '-' ?></td>
                <td style="color: <?= $color ?>;"><?= $avgVal ?></td>
                <td style="color: <?= $color ?>; font-weight:bold;"><?= $hasil !== '-' ? $hasil . '%' : '-' ?></td>

                <?php for ($d = 1; $d <= 31; $d++): ?>
                    <?php
                    $val = $data[$d][$key] ?? null;
                    $style = '';

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

        <tr class="table-warning">
            <td colspan="5"><strong> Edit</strong></td>
            <?php for ($d = 1; $d <= 31; $d++): ?>
                <td>
                    <?php if (isset($dataIds[$d])): ?>
                        <a href="../dashboard/edit-harian.php?id=<?= $dataIds[$d] ?>"
                            class="btn btn-sm btn-outline-primary"
                            title="Edit semua data tanggal <?= date('d/m/Y', strtotime($dataFullTanggal[$d])) ?>"
                            style="padding: 0.1rem 0.4rem; font-size: 0.75rem;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
    </tbody>
</table>