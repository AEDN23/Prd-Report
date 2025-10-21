<?php
require_once 'backend/config.php';
date_default_timezone_set('Asia/Jakarta');

$tahunSekarang  = date('Y');
$bulanSekarang  = date('n');

// =====================================================================
// 💡 FIELD DATA PRODUKSI
// =====================================================================
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

// =====================================================================
// 🧮 FUNGSI AMBIL DATA TAHUNAN
// =====================================================================
function getAnnualSummary(PDO $pdo, int $lineId, int $tahun)
{
    global $fields;

    // Ambil target line
    $stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ? LIMIT 1");
    $stmt->execute([$lineId, $tahun]);
    $target = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    // Ambil rata-rata bulanan
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

    // Hitung rata-rata tahunan
    $averages = [];
    foreach (array_keys($fields) as $key) {
        $sum = 0;
        $cnt = 0;
        for ($m = 1; $m <= 12; $m++) {
            $avgKey = 'avg_' . $key;
            if (!empty($bulanData[$m][$avgKey])) {
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

// =====================================================================
// 🧾 VARIABEL DASAR
// =====================================================================
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

$lineA_id = 1;
$lineB_id = 2;

// =====================================================================
// 📊 DATA TAHUNAN (LINE A & B)
// =====================================================================
$summaryA = getAnnualSummary($pdo, $lineA_id, $tahunSekarang);
$summaryB = getAnnualSummary($pdo, $lineB_id, $tahunSekarang);

// =====================================================================
// 📅 DATA HARIAN (LINE A & B)
// =====================================================================
function getDailyData($pdo, $lineId, $bulan, $tahun, $fields)
{
    // Ambil target
    $stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ?");
    $stmt->execute([$lineId, $tahun]);
    $target = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    // Ambil data harian
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            batch_count, productivity, production_speed, batch_weight,
            operation_factor, cycle_time, grade_change_sequence,
            grade_change_time, feed_raw_material
        FROM input_harian
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        ORDER BY tanggal
    ");
    $stmt->execute([$lineId, $bulan, $tahun]);
    $dataHarian = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Susun data per tanggal
    $perHari = [];
    foreach ($dataHarian as $row) {
        $perHari[(int)$row['hari']] = $row;
    }

    // Hitung average
    $averages = [];
    foreach (array_keys($fields) as $key) {
        $sum = 0;
        $count = 0;
        foreach ($dataHarian as $row) {
            if (!empty($row[$key])) {
                $sum += $row[$key];
                $count++;
            }
        }
        $averages[$key] = $count ? round($sum / $count, 2) : '-';
    }

    return [$target, $perHari, $averages];
}

[$targetA, $perHariA, $averagesA] = getDailyData($pdo, $lineA_id, $bulanSekarang, $tahunSekarang, $fields);
[$targetB, $perHariB, $averagesB] = getDailyData($pdo, $lineB_id, $bulanSekarang, $tahunSekarang, $fields);

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> -->
    <script src="js/autoscroll.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!--SCRIPT UNTUK IMPORT PDF-->
    <script src="js/script.js"></script>
    <script src="js/export.js"></script>
    <script src="js/chart.js"></script>
</head>

<body id="page-top">

    <!-- =========================================================================================================================================-->
    <!-- 🌐 HEADER BAR / NAVBAR -->
    <!-- =========================================================================================================================================-->
    <nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #0d6efd;">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand text-white fw-bold" href="#">📊 Dashboard Produksi</a>


            <!-- Navbar Content -->
            <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
                <!-- Left Side (Dropdowns) -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- Dropdown 1: Chart Produksi -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="dropdownChart"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            📈 Chart Produksi
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownChart">
                            <li><a class="dropdown-item" href="#cchart-bar-bulanan-LINEA">Line A Harian</a></li>
                            <li><a class="dropdown-item" href="#chart-bar-bulanan-LINEB">Line B Harian</a></li>
                            <li><a class="dropdown-item" href="#chart-tahunan-LINEA">Chart tahunan LINE-A</a></li>
                            <li><a class="dropdown-item" href="#chart-tahunan-LINEB">Chart tahunan LINE-B</a></li>
                        </ul>
                    </li>

                    <!-- Dropdown 2: Laporan -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="dropdownReport"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            📅 Laporan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownReport">
                            <li><a class="dropdown-item" href="#tabel-tahunan-LINEA">Tabel Harian Line A</a></li>
                            <li><a class="dropdown-item" href="#tabel-tahunan-LINEB">Tabel Harian Line B</a></li>
                        </ul>
                    </li>

                    <!-- Dropdown 3: Informasi -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="dropdownInfo"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ℹ️ Informasi
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownInfo">
                            <li><a class="dropdown-item" href="#informasi">Lihat Informasi</a></li>
                            <li><a class="dropdown-item" href="input-info.php">Tambah Informasi</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Contoh Menu Tambahan</a></li>
                        </ul>
                    </li>

                </ul>

                <!-- Right Side (Tanggal) -->
                <div class="text-white fw-semibold">
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
                <!-- 📈 CHART PRODUKSI (BULANAN)    BUKA KOMEN JIKA INGIN MENAMPIKAN LINE CHART-->
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
                <!-- ========================== LINE A =========================== -->
                <section id="chart-bar-bulanan-LINEA" class="mb-5" style="height: 100vh">
                    <h6 class="fw-bold text-primary mb-3">📊 GRAFIK BAR PRODUKSI LINE A</h6>
                    <div class="chart-container">
                        <div class="chart-toolbar mb-2">
                            <button id="prevBarA" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextBarA" class="btn btn-sm btn-primary">Next ▶</button>
                        </div>
                        <canvas id="BarChartA" style="width:100%; height:85vh;"></canvas>
                    </div>
                </section>

                <!-- ========================== LINE B =========================== -->
                <section id="chart-bar-bulanan-LINEB" class="mb-5" style="height: 100vh">
                    <h6 class="fw-bold text-primary mb-3">📊 GRAFIK BAR PRODUKSI LINE B</h6>
                    <div class="chart-container">
                        <div class="chart-toolbar mb-2">
                            <button id="prevBarB" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextBarB" class="btn btn-sm btn-primary">Next ▶</button>
                        </div>
                        <canvas id="BarChartB" style="width:100%; height:85vh;"></canvas>
                    </div>
                </section>

                <!-- =========================================================================================================================================-->
                <!-- 📅 CHART TAHUNAN PER LINE A  DAN B-->
                <!-- =========================================================================================================================================-->

                <!-- ========================== LINE A TAHUNAN =========================== -->

                <section id="chart-tahunan-LINEA">
                    <hr>
                    <h6 class="fw-bold text-primary mb-3">📅 CHART PRODUKSI LINE-A (TAHUNAN)</h6>
                    <div class="d-flex align-items-center mb-3 gap-2">
                        <form id="filterTahunan" class="d-flex mb-0 gap-2" hidden>
                            <select id="lineSelect" name="line" hidden class="form-control form-control-sm" style="width: 160px;">
                                <?php foreach ($lines as $line): ?>
                                    <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($line['nama_line']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input id="tahunInput" type="number" name="tahun" class="form-control form-control-sm"
                                value="<?= $selectedYear ?>" style="width: 110px;">
                        </form>
                    </div>
                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevTahunan" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextTahunan" class="btn btn-sm btn-primary">Next ▶</button>
                            <!-- <button id="exportPDFbarcharttahunan" class="btn btn-sm btn-danger">Export PDF</button> -->
                        </div>
                        <canvas id="BarCharttahunan" style="width:100%; height:700px;"></canvas>
                    </div>
                </section>

                <!-- ========================== LINE B TAHUNAN =========================== -->
                <section id="chart-tahunan-LINEB">
                    <hr>
                    <h6 class="fw-bold text-primary mb-3">📅 CHART PRODUKSI LINE-B (TAHUNAN)</h6>
                    <div class="d-flex align-items-center mb-3 gap-2">
                        <form id="filterTahunan" class="d-flex mb-0 gap-2" hidden>
                            <select id="lineSelect" name="line" hidden class="form-control form-control-sm" style="width: 160px;">
                                <?php foreach ($lines as $line): ?>
                                    <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($line['nama_line']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input id="tahunInput" type="number" name="tahun" class="form-control form-control-sm"
                                value="<?= $selectedYear ?>" style="width: 110px;">
                        </form>
                    </div>
                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevTahunan" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextTahunan" class="btn btn-sm btn-primary">Next ▶</button>
                            <!-- <button id="exportPDFbarcharttahunan" class="btn btn-sm btn-danger">Export PDF</button> -->
                        </div>
                        <canvas id="BarCharttahunanLINEB" style="width:100%; height:700px;"></canvas>
                    </div>
                </section>

                <!-- ===================================================================== -->
                <!-- 📋 TABEL DATA PRODUKSI HARIAN LINE A -->
                <!-- ===================================================================== -->
                <section id="DATA-HARIAN-LINEA" class="mb-4" style="height: 100vh;">
                    <h2>📋 DATA PRODUKSI HARIAN LINE A (<?= $namaBulan[$bulanSekarang] . " " . $tahunSekarang ?>)</h2>
                    <div class="table-responsive border rounded p-2">
                        <table style="height: 80vh;" class="table table-bordered table-sm mb-0 align-middle text-center">
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
                                    $targetVal = $targetA['target_' . $key] ?? 0;
                                    $avgVal = $averagesA[$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td class="text-start"><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>"><?= $avgVal ?></td>
                                        <?php for ($i = 1; $i <= 31; $i++):
                                            $val = $perHariA[$i][$key] ?? '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>"><?= is_numeric($val) ? round($val, 2) : '-' ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ===================================================================== -->
                <!-- 📘 TABEL DATA TAHUNAN LINE A -->
                <!-- ===================================================================== -->
                <section id="tabel-tahunan-LINEA" style="height: 100vh;" class="mb-4">
                    <h2 class="fw-bold">📋 DATA PRODUKSI LINE A TAHUN <?= $tahunSekarang ?></h2>
                    <div class="table-responsive border rounded p-2">
                        <table class="table table-bordered table-sm mb-0 text-center" style="height:85vh">
                            <thead class="table-light">
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
                                    $targetVal = $summaryA['target']['target_' . $key] ?? 0;
                                    $avgVal = $summaryA['averages'][$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>"><?= $avgVal ?></td>
                                        <?php for ($m = 1; $m <= 12; $m++):
                                            $avgKey = 'avg_' . $key;
                                            $val = $summaryA['bulanData'][$m][$avgKey] ?? '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>"><?= is_numeric($val) ? round($val, 2) : '-' ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ===================================================================== -->
                <!-- 📋 DATA PRODUKSI LINE B -->
                <!-- ===================================================================== -->
                <section id="DATA-HARIAN-LINEB" style="height: 100vh;" class="mb-4">
                    <h2>📋 DATA PRODUKSI HARIAN LINE B (<?= $namaBulan[$bulanSekarang] . " " . $tahunSekarang ?>)</h2>
                    <div class="table-responsive border rounded p-2">
                        <table class="table table-bordered table-sm mb-0 align-middle text-center" style="height: 85vh;">
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
                                    $targetVal = $targetB['target_' . $key] ?? 0;
                                    $avgVal = $averagesB[$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td class="text-start"><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>"><?= $avgVal ?></td>
                                        <?php for ($i = 1; $i <= 31; $i++):
                                            $val = $perHariB[$i][$key] ?? '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>"><?= is_numeric($val) ? round($val, 2) : '-' ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section id="tabel-tahunan-LINEB" style="height: 100vh;" class="mb-4">
                    <h2 class="fw-bold">📋 DATA PRODUKSI LINE B TAHUN <?= $tahunSekarang ?></h2>
                    <div class="table-responsive border rounded p-2">
                        <table style="height: 85vh;" class="table table-bordered table-sm mb-0 text-center">
                            <thead class="table-light">
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
                                    $targetVal = $summaryB['target']['target_' . $key] ?? 0;
                                    $avgVal = $summaryB['averages'][$key];
                                    $avgColor = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($label) ?></td>
                                        <td><?= htmlspecialchars($unit) ?></td>
                                        <td><?= $targetVal ?: '-' ?></td>
                                        <td style="color:<?= $avgColor ?>"><?= $avgVal ?></td>
                                        <?php for ($m = 1; $m <= 12; $m++):
                                            $avgKey = 'avg_' . $key;
                                            $val = $summaryB['bulanData'][$m][$avgKey] ?? '-';
                                            $color = ($val !== '-' && $targetVal > 0 && $val < $targetVal) ? 'red' : 'black';
                                        ?>
                                            <td style="color:<?= $color ?>"><?= is_numeric($val) ? round($val, 2) : '-' ?></td>
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