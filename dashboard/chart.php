<?php
$page_title = "Chart";
include '../layout/header.php';
?>


<script src="../js/jspdf.umd.min.js"></script>
<!-- <script src="../js/export.js"></script> -->
<script src="../js/chartdashboard.js"></script>
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
                        <!-- <button id="exportPDFbarcharttahunan" class="btn btn-sm btn-danger">Export PDF</button> -->
                    </div>
                    <canvas id="BarCharttahunan" style="width:100%; height:400px;"></canvas>
                </div>
            </section>

        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end container -->


<?php
include '../layout/footer.php';
?>