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
</head>

<?php
include '../layout/sidebar.php';
?>


<!-- <script src="../js/chart.js"></script> -->
<link href="../css/ind.css" rel="stylesheet">


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
                    <!-- ganti id jadi bulanFilter supaya tidak bentrok -->
                    <select id="bulanFilter" name="bulan" class="form-select">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <!-- ganti id jadi tahunFilterBulanan supaya unik -->
                    <input id="tahunFilterBulanan" type="number" name="tahun" value="<?= date('Y') ?>" class="form-control">
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
                    <!-- gunakan id tahunFilterTahunan supaya unik -->
                    <input id="tahunFilterTahunan" type="number" name="tahun" value="<?= date('Y') ?>" class="form-control">
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

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../vendor/chart.js/Chart.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

</body>

</html>