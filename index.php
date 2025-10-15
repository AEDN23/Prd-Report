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
    <script src="js/autoScroll.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
                <!-- 📈 CHART PRODUKSI (BULANAN) -->
                <!-- =========================================================================================================================================-->
                <section id="chart-bulanan" class="mb-5">
                    <h6 class="fw-bold text-primary mb-3">📈 CHART PRODUKSI (BULANAN)</h6>

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
                            <input id="tahunUtama" type="number" name="tahun" value="<?= $selectedYear ?>" class="form-control">
                        </div>
                    </form>

                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevLine" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextLine" class="btn btn-sm btn-primary">Next ▶</button>
                            <button id="exportPDF" class="btn btn-sm btn-danger">Export PDF</button>
                        </div>
                        <canvas id="myChart" style="width:100%; height:400px;"></canvas>
                    </div>
                </section>



                <!-- =========================================================================================================================================-->
                <!-- 📊 CHART BAR (BULANAN) -->
                <!-- =========================================================================================================================================-->

                <section id="chart-bar-bulanan" class="mb-5">
                    <hr>
                    <h6 class="fw-bold text-primary mb-3">📊 GRAFIK BAR PRODUKSI</h6>
                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevBar" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextBar" class="btn btn-sm btn-primary">Next ▶</button>
                            <button id="exportPDFbarchart" class="btn btn-sm btn-danger">Export PDF</button>
                        </div>
                        <canvas id="BarChart" style="width:100%; height:400px;"></canvas>
                    </div>
                </section>

                <hr>

                <!-- =========================================================================================================================================-->
                <!-- 📅 CHART TAHUNAN PER LINE -->
                <!-- =========================================================================================================================================-->
                <section id="chart-tahunan">
                    <h6 class="fw-bold text-primary mb-3">📅 CHART PRODUKSI (TAHUNAN)</h6>
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

                    <div class="chart-container">
                        <div class="chart-toolbar">
                            <button id="prevTahunan" class="btn btn-sm btn-secondary">◀ Prev</button>
                            <button id="nextTahunan" class="btn btn-sm btn-primary">Next ▶</button>
                            <button id="exportPDFbarcharttahunan" class="btn btn-sm btn-danger">Export PDF</button>
                        </div>
                        <canvas id="BarCharttahunan" style="width:100%; height:400px;"></canvas>
                    </div>
                </section>
                <section id="informasi">
                    <hr>

                </section>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end container -->

</body>

</html>