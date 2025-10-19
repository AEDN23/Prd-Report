<?php
require_once 'backend/config.php';
date_default_timezone_set('Asia/Jakarta');
$tahunSekarang = date('Y');

// Fields yang akan ditampilkan (sama seperti di config.php)
$fields = [
    'batch_count' => ['Batch Count', 'Per Day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'production_speed' => ['Production Speed', 'Kg/Min'],
    'batch_weight' => ['Batch Weight', 'Kg/Batch'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'Min/Batch'],
    'grade_change_sequence' => ['Grade Change Sequence', 'Frequently'],
    'grade_change_time' => ['Grade Change Time', 'Min/Grade'],
    'feed_raw_material' => ['Feed Raw Material', 'Kg/Day']
];

// Helper: ambil data rangkuman untuk satu line
function getAnnualSummary(PDO $pdo, int $lineId, int $tahun)
{
    // ambil target
    $stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ? LIMIT 1");
    $stmt->execute([$lineId, $tahun]);
    $target = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    // ambil rata-rata per bulan untuk setiap metric
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
        GROUP BY bulan
        ORDER BY bulan
    ");
    $stmt->execute([$lineId, $tahun]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $bulanData = [];
    foreach ($rows as $r) {
        $bulanData[(int)$r['bulan']] = $r;
    }

    // hitung average tahunan per field (rata-rata dari bulan-bulan yang ada)
    $averages = [];
    foreach (array_keys($GLOBALS['fields']) as $key) {
        $sum = 0;
        $cnt = 0;
        for ($m = 1; $m <= 12; $m++) {
            $avgKey = 'avg_' . $key;
            if (isset($bulanData[$m][$avgKey]) && $bulanData[$m][$avgKey] !== null) {
                $sum += $bulanData[$m][$avgKey];
                $cnt++;
            }
        }
        $averages[$key] = $cnt ? round($sum / $cnt, 2) : '-';
    }

    return [
        'target' => $target,
        'bulanData' => $bulanData,
        'averages' => $averages
    ];
}

// Contoh: tentukan ID line untuk Line A & Line B.
// Jika kamu punya ID spesifik, ganti 1 dan 2 -> sesuai data table `line_produksi`.
$lineA_id = 1;
$lineB_id = 2;

// ambil summary
$summaryA = getAnnualSummary($pdo, $lineA_id, (int)$tahunSekarang);
$summaryB = getAnnualSummary($pdo, $lineB_id, (int)$tahunSekarang);

// nama bulan singkat
$namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// ================================================================================================ BACKEND DATA HARIAN

// Ambil line ID untuk Line A (ganti sesuai ID line_produksi kamu)
// ================================================================================================ BACKEND DATA HARIAN

// Ambil line ID untuk Line A dan Line B (ganti sesuai ID line_produksi kamu)
$lineA_id = 1;
$lineB_id = 2; // ✅ diperbaiki: sebelumnya salah pakai $line_id

// Ambil bulan dan tahun sekarang
$bulanSekarang = date('n');
$tahunSekarang = date('Y');

// Ambil nama bulan untuk judul
$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

// Daftar kolom
$fields = [
    'batch_count' => ['Batch Count', 'Per Day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'production_speed' => ['Production Speed', 'Kg/Min'],
    'batch_weight' => ['Batch Weight', 'Kg/Batch'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'Min/Batch'],
    'grade_change_sequence' => ['Grade Change Sequence', 'Freq'],
    'grade_change_time' => ['Grade Change Time', 'Min/Grade'],
    'feed_raw_material' => ['Feed Raw Material', 'Kg/Day']
];

// ================================================= LINE A =================================================

// Ambil data target line A
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ?");
$stmt->execute([$lineA_id, $tahunSekarang]);
$targetA = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

// Ambil data harian line A
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
    ORDER BY tanggal
");
$stmt->execute([$lineA_id, $bulanSekarang, $tahunSekarang]);
$dataHarianA = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Susun data per tanggal
$perHariA = [];
foreach ($dataHarianA as $row) {
    $perHariA[(int)$row['hari']] = $row;
}

// Hitung average
$averagesA = [];
foreach (array_keys($fields) as $key) {
    $sum = 0;
    $count = 0;
    foreach ($dataHarianA as $row) {
        if (!empty($row[$key])) {
            $sum += $row[$key];
            $count++;
        }
    }
    $averagesA[$key] = $count ? round($sum / $count, 2) : '-';
}

// ================================================= LINE B =================================================

// Ambil data target line B
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ?");
$stmt->execute([$lineB_id, $tahunSekarang]);
$targetB = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

// Ambil data harian line B
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
    ORDER BY tanggal
");
$stmt->execute([$lineB_id, $bulanSekarang, $tahunSekarang]); // ✅ pakai line B
$dataHarianB = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Susun data per tanggal
$perHariB = [];
foreach ($dataHarianB as $row) {
    $perHariB[(int)$row['hari']] = $row;
}

// Hitung average
$averagesB = [];
foreach (array_keys($fields) as $key) {
    $sum = 0;
    $count = 0;
    foreach ($dataHarianB as $row) {
        if (!empty($row[$key])) {
            $sum += $row[$key];
            $count++;
        }
    }
    $averagesB[$key] = $count ? round($sum / $count, 2) : '-';
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/images.jpg" rel="icon">
    <title>HALAMAN CHART PRODUKSI</title>
    <!-- STYLES -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/ind.css" rel="stylesheet">
    <style>
        /* ===== Sticky Navbar ===== */
        html {
            scroll-behavior: smooth;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #0d6efd !important;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: 0.2s;
        }

        .navbar-nav .nav-link:hover {
            text-decoration: underline;
        }

        .nav-date {
            font-size: 14px;
            color: #fff;
            font-weight: bold;
        }
    </style>


    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="js/autoscroll.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>                SCRIPT UNTUK IMPORT PDF-->
    <script src="js/script.js"></script>
    <script src="js/export.js"></script>
    <script src="js/chart.js"></script>
</head>

<body id="page-top">

    <!-- =========================================================================================================================================-->
    <!-- 🌐 HEADER BAR / NAVBAR -->
    <!-- =========================================================================================================================================-->
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold" href="#">📊 Dashboard Produksi</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon text-white"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#chart-bulanan">📈 Bulanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#chart-bar-bulanan">📊 Bar Bulanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#chart-tahunan">📅 Tahunan</a></li>
                </ul>
                <div class="nav-date">
                    <?= date('d - M - Y'); ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- =========================================================================================================================================-->
    <!-- 📊 ISI HALAMAN -->
    <!-- =========================================================================================================================================-->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-body">

                <!-- =========================================================================================================================================-->
                <!-- 📈 CHART PRODUKSI (BULANAN)    BUKAN KOMEN JIKA INGIN MENAMPIKAN LINE CHART-->
                <!-- =========================================================================================================================================-->
                <!-- <section id="chart-bulanan" class="mb-5">
                    <h6 class="fw-bold text-primary mb-3">📈 CHART PRODUKSI (BULANAN)</h6>
                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevLine" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextLine" class="btn btn-sm btn-primary">Next ▶</button>
                            <button id="exportPDF" class="btn btn-sm btn-danger" hidden>Export PDF</button>
                        </div>
                        <canvas id="myChart" style="width:100%; height:400px;"></canvas>
                    </div>
                </section> -->



                <!-- =========================================================================================================================================-->
                <!-- 📊 CHART BAR (BULANAN) -->
                <!-- =========================================================================================================================================-->
                <section id="chart-bar-bulanan" class="mb-5">
                    <h6 class="fw-bold text-primary mb-3">📊 GRAFIK BAR PRODUKSI</h6>
                    <form id="filterchart" class="row g-3 mb-3">
                        <div class="col-md-2">
                            <select id="bulanUtama" name="bulan" class="form-select" hidden>
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input id="tahunUtama" type="number" name="tahun" value="<?= $selectedYear ?>" class="form-control" hidden>
                        </div>
                    </form>
                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevBar" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextBar" class="btn btn-sm btn-primary">Next ▶</button>
                            <!-- <button id="exportPDFbarchart" class="btn btn-sm btn-danger">Export PDF</button> -->
                        </div>
                        <canvas id="BarChart" style="width:100%; height:400px;"></canvas>
                    </div>
                </section>

                <!-- =========================================================================================================================================-->
                <!-- 📅 CHART TAHUNAN PER LINE -->
                <!-- =========================================================================================================================================-->
                <section id="chart-tahunan">
                    <hr>
                    <h6 class="fw-bold text-primary mb-3">📅 CHART PRODUKSI (TAHUNAN)</h6>
                    <div class="d-flex align-items-center mb-3 gap-2">
                        <form id="filterTahunan" class="d-flex mb-0 gap-2">
                            <select id="lineSelect" name="line" class="form-control form-control-sm" style="width: 160px;" hidden>
                                <?php foreach ($lines as $line): ?>
                                    <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($line['nama_line']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input hidden id="tahunInput" type="number" name="tahun" class="form-control form-control-sm"
                                value="<?= $selectedYear ?>" style="width: 110px;">
                        </form>
                    </div>
                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevTahunan" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextTahunan" class="btn btn-sm btn-primary">Next ▶</button>
                            <!-- <button id="exportPDFbarcharttahunan" class="btn btn-sm btn-danger">Export PDF</button> -->
                        </div>
                        <canvas id="BarCharttahunan" style="width:100%; height:400px;"></canvas>
                    </div>
                </section>

                <!-- =========================================================================================================================================-->
                <!-- 📅 TABEL DATA PRODUKSI TAHUNAN DAN HARIAN LINE A -->
                <!-- =========================================================================================================================================-->
                <section id="DATA-HARIAN-LINEA" class="mb-4">
                    <h2>📋 DATA PRODUKSI HARIAN LINE A (<?= $namaBulan[$bulanSekarang] . " " . $tahunSekarang ?>)</h2>
                    <div class="table-responsive border rounded p-2">
                        <table class="table table-bordered table-sm mb-0 align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Details</th>
                                    <th>Unit</th>
                                    <th>Target</th>
                                    <th>Average</th>
                                    <?php for ($i = 1; $i <= 31; $i++): ?>
                                        <th><?= $i ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fields as $key => [$label, $unit]):
                                    $targetVal = isset($targetA['target_' . $key]) ? floatval($targetA['target_' . $key]) : 0;
                                    $avgVal = $averagesA[$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td class="text-start"><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td style=";"><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>; ;"><?= $avgVal ?></td>
                                        <?php for ($i = 1; $i <= 31; $i++):
                                            $val = isset($perHariA[$i][$key]) ? round($perHariA[$i][$key], 2) : '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>; "><?= $val ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section id="tabel-tahunan-LINEA" class="mb-4">
                    <h2 class="fw-bold">📋 DATA PRODUKSI LINE A TAHUN <?= $tahunSekarang ?></h2>
                    <div class="table-responsive border rounded p-2">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Details</th>
                                    <th>Unit</th>
                                    <th>Target</th>
                                    <th>Average</th>
                                    <?php foreach ($namaBulan as $b): ?><th><?= $b ?></th><?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fields as $key => [$label, $unit]):
                                    $targetVal = isset($summaryA['target']['target_' . $key]) ? floatval($summaryA['target']['target_' . $key]) : 0;
                                    $avgVal = $summaryA['averages'][$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td style=";"><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>; ;"><?= $avgVal ?></td>
                                        <?php for ($m = 1; $m <= 12; $m++):
                                            $avgKey = 'avg_' . $key;
                                            $val = isset($summaryA['bulanData'][$m][$avgKey]) ? round($summaryA['bulanData'][$m][$avgKey], 2) : '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>; ;"><?= $val ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- =========================================================================================================================================-->
                <!-- 📅 TABEL DATA PRODUKSI TAHUNAN DAN HARIAN LINE B -->
                <!-- =========================================================================================================================================-->
                <section id="DATA-HARIAN-LINEB" class="mb-4">
                    <h2>📋 DATA PRODUKSI HARIAN LINE B (<?= $namaBulan[$bulanSekarang] . " " . $tahunSekarang ?>)</h2>
                    <div class="table-responsive border rounded p-2">
                        <table class="table table-bordered table-sm mb-0 align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Details</th>
                                    <th>Unit</th>
                                    <th>Target</th>
                                    <th>Average</th>
                                    <?php for ($i = 1; $i <= 31; $i++): ?><th><?= $i ?></th><?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fields as $key => [$label, $unit]):
                                    $targetVal = isset($targetB['target_' . $key]) ? floatval($targetB['target_' . $key]) : 0;
                                    $avgVal = $averagesB[$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td class="text-start"><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td style=";"><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>; ;"><?= $avgVal ?></td>
                                        <?php for ($i = 1; $i <= 31; $i++):
                                            $val = isset($perHariB[$i][$key]) ? round($perHariB[$i][$key], 2) : '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>; ;"><?= $val ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section id="tabel-tahunan-LINEB" class="mb-4">
                    <h2>📋 DATA PRODUKSI LINE B TAHUN <?= $tahunSekarang ?></h2>
                    <div class="table-responsive border rounded p-2">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Details</th>
                                    <th>Unit</th>
                                    <th>Target</th>
                                    <th>Average</th>
                                    <?php foreach ($namaBulan as $b): ?><th><?= $b ?></th><?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fields as $key => [$label, $unit]):
                                    $targetVal = isset($summaryB['target']['target_' . $key]) ? floatval($summaryB['target']['target_' . $key]) : 0;
                                    $avgVal = $summaryB['averages'][$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td style=";"><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>; ;"><?= $avgVal ?></td>
                                        <?php for ($m = 1; $m <= 12; $m++):
                                            $avgKey = 'avg_' . $key;
                                            $val = isset($summaryB['bulanData'][$m][$avgKey]) ? round($summaryB['bulanData'][$m][$avgKey], 2) : '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>; ;"><?= $val ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>



                <!-- =========================================================================================================================================-->
                <!-- 📅 INFORMASI -->
                <!-- =========================================================================================================================================-->
                <section id="informasi">
                    <hr>
                    <h2>Daftar Informasi</h2>
                    <div class="card-body">

                        <?php

                        try {
                            $stmt = $pdo->query("SELECT * FROM info ORDER BY created_at DESC");
                            $infos = $stmt->fetchAll();

                            if ($infos) {
                                foreach ($infos as $info) {
                                    echo "<h1 class='text-center' style='word-break:break-word; white-space:normal;'>" . htmlspecialchars($info['judul']) . "</h1><div>" . htmlspecialchars(date('d-m-Y', strtotime($info['created_at']))) . "</div>";
                                    echo "<div class='text-center'>"  . '|  ' . htmlspecialchars($info['deskripsi']) . ' |</div>  <br>';
                                    echo "<p class='text-justify' style='word-break:break-word; white-space:normal;'>" . nl2br(htmlspecialchars($info['isi'])) . "</p>";
                                    if (!empty($info['file'])) {
                                        echo "<a href='../uploads/info/" . htmlspecialchars($info['file']) . "' target='_blank'>Lihat File</a><br>";
                                    }
                                    echo "<hr>";
                                }
                            } else {
                                echo "<p><a href='input-info.php'>Tidak ada informasi tersedia. klik untuk tambah informasi<a></p>";
                            }
                        } catch (Exception $e) {
                            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                        }
                        ?>
                        <b>
                            <hr>
                        </b>
                    </div>
                </section>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end container -->

</body>

</html>



<!-- // =========================================================================================================================================================
// SCRIPT UNTUK TABEL PRODUKSI HARIAN
// ========================================================================================================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const lineUtama = document.getElementById("lineUtama");
        const bulanUtama = document.getElementById("bulanUtama");
        const tahunUtama = document.getElementById("tahunUtama");
        const tabelUtama = document.querySelector(".table-container");

        function updateTabelUtama() {
            const line = lineUtama.value;
            const bulan = bulanUtama.value;
            const tahun = tahunUtama.value;

            tabelUtama.innerHTML =
                '<div class="text-center py-3 text-muted">Loading data...</div>';

            fetch(
                    `backend/inputharianajax.php?line=${line}&bulan=${bulan}&tahun=${tahun}`
                )
                .then((res) => res.text())
                .then((html) => (tabelUtama.innerHTML = html))
                .catch((err) => {
                    tabelUtama.innerHTML =
                        '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                    console.error(err);
                });
        }

        // Auto update ketika filter berubah
        [lineUtama, bulanUtama, tahunUtama].forEach((el) => {
            el.addEventListener("change", updateTabelUtama);
        });

        // Load pertama kali
        updateTabelUtama();
    });
</script>