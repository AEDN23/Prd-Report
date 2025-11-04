<!-- FILE UNTUK LIAT CHART LINE A, B, C DALAM SATU CHART, JS NYA ADA DI FILE chartlineab -->

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


                <script src="../js/chart-LINEAB.js"></script>
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


                            <!-- =========================================================================================================================================-->
                            <!-- 📅 CHART TAHUNAN PER LINE -->
                            <section id="chart-tahunan">
                                <hr>
                                <h6 class="fw-bold text-primary mb-3">📅 CHART PRODUKSI (TAHUNAN)</h6>
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
                                    <canvas id="BarCharttahunan" style="width:100%; height:400px;"></canvas>
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
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>