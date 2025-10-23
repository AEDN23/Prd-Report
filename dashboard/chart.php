<?php
require_once '../backend/config.php';
$page_title = "Halaman chart"
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../img/images.jpg" rel="icon">
    <title><?= $page_title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="../js/script.js"></script>
    <script src="../js/export.js"></script>
    <script src="../js/chartdashboard.js"></script>

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
                <div class="mx-3" style="text-align:center;  color:#fff; font-weight:bold;">Produksi Report</div>
            </a>
            <div style="text-align:center; font-size:14px; color:#fff; font-weight:bold;">
                <?php
                date_default_timezone_set('Asia/Jakarta');
                echo '<b>' . date('d - M- Y') . '</b>';
                ?>
                <br>
                <span id="jam" style="font-weight:bold;">
                    <?php echo date('H:i:s'); ?>
                </span>
            </div>
            <script>
                function updateClock() {
                    var now = new Date();
                    var jam = now.getHours().toString().padStart(2, '0');
                    var menit = now.getMinutes().toString().padStart(2, '0');
                    var detik = now.getSeconds().toString().padStart(2, '0');
                    document.getElementById('jam').textContent = jam + ':' + menit + ':' + detik;
                }
                setInterval(updateClock, 1000);
            </script>
            <br>

            <!-- Divider -->
            <div class="sidebar-heading">
                Master Data
            </div>
            <li <?php if ($page_title === "Halaman Utama") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="index">
                    <i class="bi bi-clipboard2-data"></i>
                    <span>Dashboard</span></a>
            </li>
            <li <?php if ($page_title === "INFORMASI") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="informasi">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Informasi</span></a>
            </li>
            <li <?php if ($page_title === "Halaman chart") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="Chart">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Chart</span></a>
            </li>
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                INPUT
            </div>


            <li <?php if ($page_title === "Input Target Produksi") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="input-target">
                    <i class="bi bi-clipboard"></i></i>
                    <span>INPUT TARGET</span></a>
            </li>

            <li <?php if ($page_title === "Input Harian") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="input-harian">
                    <i class="bi bi-calendar"></i>
                    <span>INPUT HARIAN</span></a>
            </li>
            <li <?php if ($page_title === "Input Info") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="input-info">
                    <i class="bi bi-info"></i>
                    <span>INPUT INFO</span></a>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">

                <br>


                <script src="../js/chart.js"></script>
                <link href="../css/ind.css" rel="stylesheet">
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

                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- =========================================================================================================================================-->
                            <!-- 📊 CHART BAR (BULANAN) -->
                            <!-- =========================================================================================================================================-->

                            <!-- FILTER CHART BULANAN -->
                            <form id="filter-chart-bulanan" class="row g-3 mb-3">
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
                            <!-- FILTER CHART BULANAN -->



                            <!-- ========================== LINE A BULANAN =========================== -->
                            <section id="chart-bar-bulanan-LINEA" style="height: 100vh;" class="mb-5">
                                <h6 class="fw-bold text-primary mb-3">📊 GRAFIK BAR PRODUKSI LINE A</h6>
                                <div class="chart-container">
                                    <div class="chart-toolbar mb-2">
                                        <button id="prevBarA" class="btn btn-sm btn-secondary">◀ Prev</button>
                                        <button id="nextBarA" class="btn btn-sm btn-primary">Next ▶</button>
                                        <button class="btn btn-sm btn-danger exportChartPDF">Export PDF</button>

                                    </div>
                                    <canvas id="BarChartA" style="width:100%; height: 85vh"></canvas>
                                </div>
                            </section>

                            <!-- ========================== LINE B BULANAN =========================== -->
                            <section id="chart-bar-bulanan-LINEB" class="mb-5" style="height: 100vh">
                                <h6 class="fw-bold text-primary mb-3">📊 GRAFIK BAR PRODUKSI LINE B</h6>
                                <div class="chart-container">
                                    <div class="chart-toolbar mb-2">
                                        <button id="prevBarB" class="btn btn-sm btn-secondary">◀ Prev</button>
                                        <button id="nextBarB" class="btn btn-sm btn-primary">Next ▶</button>
                                        <button class="btn btn-sm btn-danger exportChartPDF">Export PDF</button>


                                    </div>
                                    <canvas id="BarChartB" style="width:100%; height:85vh;"></canvas>
                                </div>
                            </section>

                            <!-- =========================================================================================================================================-->
                            <!-- 📅 CHART TAHUNAN PER LINE A  DAN B-->
                            <!-- =========================================================================================================================================-->

                            <!-- FILTER CHART TAHUNAN-->
                            <form id="filter-chart-tahunan" class="row g-3 mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Tahun</label>
                                    <input id="tahunUtama" type="number" name="tahun" value="<?= $selectedYear ?>" class="form-control">
                                </div>
                            </form>
                            <!-- FILTER CHART TAHUNAN -->

                            <!-- ========================== LINE A TAHUNAN =========================== -->
                            <section id="chart-tahunan-LINEA" style="height: 100vh;">
                                <h6 class="fw-bold text-primary mb-3">📅 CHART PRODUKSI LINE-A (TAHUNAN)</h6>
                                <div class="chart-container">
                                    <div class="chart-toolbar">
                                        <button id="prevTahunan" class="btn btn-sm btn-secondary">◀ Prev</button>
                                        <button id="nextTahunan" class="btn btn-sm btn-primary">Next ▶</button>

                                        <button class="btn btn-sm btn-danger exportChartPDF">Export PDF</button>

                                    </div>
                                    <canvas id="BarCharttahunan" style="width:100%; height:85vh;"></canvas>
                                </div>
                            </section>

                            <!-- ========================== LINE B TAHUNAN =========================== -->
                            <section id="chart-tahunan-LINEB" style="height: 100vh;">
                                <hr>
                                <h6 class="fw-bold text-primary mb-3">📅 CHART PRODUKSI LINE-B (TAHUNAN)</h6>
                                <div class="chart-container">
                                    <div class="chart-toolbar">
                                        <button id="prevTahunanB" class="btn btn-sm btn-secondary">◀ Prev</button>
                                        <button id="nextTahunanB" class="btn btn-sm btn-primary">Next ▶</button>
                                        <button class="btn btn-sm btn-danger exportChartPDF">Export PDF</button>

                                    </div>
                                    <canvas id="BarCharttahunanLINEB" style="width:100%; height:85vh;"></canvas>
                                </div>
                            </section>


                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end container -->
            </div>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>



</body>

</html>