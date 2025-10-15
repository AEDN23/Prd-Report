<?php
require_once 'backend/config.php';
date_default_timezone_set('Asia/Jakarta');
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



    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/export.js"></script>
    <script src="js/chart.js"></script>
</head>

<body id="page-top">

    <!-- =============================== -->
    <!-- 🌐 HEADER BAR / NAVBAR -->
    <!-- =============================== -->
    <nav class="navbar navbar-expand-lg  bg-primary shadow-sm mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold text-white">📊 DASHBOARD DATA PRODUKSI</h5>
            <div class="text-white fw-bold" style="font-size: 14px;">
                <?= date('d - M - Y'); ?>
            </div>
        </div>
    </nav>

    <!-- =============================== -->
    <!-- 📊 ISI HALAMAN -->
    <!-- =============================== -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-body">

                <!-- =============================== -->
                <!-- 📈 CHART PRODUKSI (BULANAN) -->
                <!-- =============================== -->
                <section id="chart-bulanan" class="mb-5">
                    <h6 class="fw-bold text-primary mb-3">📈 CHART PRODUKSI (BULANAN)</h6>

                    <!-- FILTER -->
                    <form id="filterchart" class="row g-3 mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Bulan</label>
                            <select id="bulanUtama" name="bulan" class="form-select">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <input id="tahunUtama" type="number" name="tahun"
                                value="<?= $selectedYear ?>" class="form-control">
                        </div>
                    </form>

                    <div id="chartLegend" class="mt-3 text-center"></div>

                    <!-- CHART PRODUKSI BULANAN -->
                    <div class="chart-area border rounded p-3 bg-light mt-3"
                        style="width:100%; overflow-x:auto; height:auto; max-height:500px;">
                        <canvas id="myChart" style="width:100%; height:400px; display:block;"></canvas>
                    </div>
                </section>

                <hr>

                <!-- =============================== -->
                <!-- 📊 CHART BAR (BULANAN) -->
                <!-- =============================== -->
                <section id="chart-bar-bulanan" class="mb-5">
                    <h6 class="fw-bold text-primary mb-3">📊 GRAFIK BAR PRODUKSI</h6>
                    <div id="barchart" class="text-center mb-3"></div>
                    <div class="chart-area border rounded p-3 bg-light"
                        style="width:100%; overflow-x:auto; height:auto; max-height:500px;">
                        <canvas id="BarChart" style="width:100%; height:400px; display:block;"></canvas>
                    </div>
                </section>

                <hr>

                <!-- =============================== -->
                <!-- 📅 CHART TAHUNAN PER LINE -->
                <!-- =============================== -->
                <section id="chart-tahunan">
                    <div class="d-flex align-items-center mb-3 gap-2">
                        <form id="filterTahunan" class="d-flex mb-0 gap-2">
                            <select id="lineSelect" name="line" class="form-control form-control-sm" style="width: 160px;">
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

                    <div class="chart-area border rounded p-3 bg-light"
                        style="width:100%; height:auto; max-height:500px;">
                        <div id="barcharttahunan" class="text-center mb-2"></div>
                        <canvas id="BarCharttahunan" style="width:100%; height:400px; display:block;"></canvas>
                    </div>
                </section>

            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end container -->

</body>

</html>