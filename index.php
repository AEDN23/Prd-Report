    <?php
    require_once 'backend/config.php';
    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="img/images.jpg" rel="icon">
        <title>HALAMAN CHART</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <script src="js/script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    </head>

    <body id="page-top">
        <div class="container-fluid mt-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white text-center">
                    <div style="text-align:center; font-size:14px; color:#fff; font-weight:bold;">
                        <?php
                        date_default_timezone_set('Asia/Jakarta');
                        echo '<h5> <b>' . date('d - M- Y') . '</b></h5>';
                        ?>
                    </div>
                    <h5 class="m-0 font-weight-bold">📊 DASHBOARD DATA PRODUKSI</h5>
                </div>
                <!-- CHART PRODUKSI (BULANAN) -->

                <section id="1">
                    <div class="card-body">
                        <div class="mb-5">
                            <h6 class="fw-bold text-primary mb-3">📈 CHART PRODUKSI (BULANAN)</h6>
                            <!-- FILTER -->
                            <form id="filterchart" class="row g-2 mb-4">
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
                            <div id="chartLegend" class="mt-3 text-center"> </div>
                            <br>
                            <div><button id="exportPDF" class="btn btn-primary">Export PDF</button></div>
                            <div class="chart-area border rounded p-3 bg-light"
                                style="width:100%; overflow-x:auto; overflow-y:hidden; height:auto; max-height:500PX">
                                <canvas id="myChart" style="width:100%; height:400px; display:block;"> </canvas>
                            </div>
                            <div id="barchart" class="mt-3 text-center"></div>
                            <div><button id="exportPDFbarchart" class="btn btn-primary">Export PDF</button></div>
                            <div class="chart-area border rounded p-3 bg-light"
                                style="width:100%; overflow-x:auto; overflow-y:hidden; height:auto; max-height:500PX">
                                <canvas id="BarChart" style="width:100%; height:400px; display:block;"></canvas>
                            </div>
                            <br>
                        </div>
                </section>


                <section id="2">
                    <div class="d-flex gap-2 align-items-center mb-3">
                        <form id="filterTahunan" class="d-flex gap-2 mb-0">
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

                        <button id="exportPDFbarcharttahunan" class="btn btn-primary btn-sm ms-2" style="min-width: 120px;">
                            Export PDF
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="chart-area border rounded p-3 bg-light"
                            style="width:100%; overflow-x:auto; overflow-y:hidden; height:auto; max-height:500px;">
                            <div id="barcharttahunan" class="mt-3 text-center"></div>
                            <canvas id="BarCharttahunan" style="width:100%; height:400px; display:block;"></canvas>
                        </div>
                    </div>
                </section>


                <section id="3">
                    <div class="card-body">
                        <form id="filterUtama" class="row g-2 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Line Produksi</label>
                                <select id="lineUtama" name="line" class="form-select">
                                    <?php foreach ($lines as $line): ?>
                                        <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($line['nama_line']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                        <!-- DATA PRODUKSI (HARIAN) -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-primary mb-3">📋 DATA PRODUKSI (HARIAN)</h6>
                            <div class="table-container border rounded p-2">
                                <div class="text-center py-3 text-muted">Memuat data...</div>
                            </div>
                        </div>
                </section>
                <!-- DATA TARGET PRODUKSI (TAHUNAN) -->
                <section id="tabel">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-primary mb-0">📘 DATA TARGET PRODUKSI (Rangkuman Tahunan)</h6>
                            <div class="d-flex gap-2 align-items-center">
                                <form id="filterForm" class="d-flex gap-2 mb-0">
                                    <select id="lineSelect" name="line" class="form-control form-control-sm" style="width: 140px;">
                                        <?php foreach ($lines as $line): ?>
                                            <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($line['nama_line']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input id="tahunInput" type="number" name="tahun" class="form-control form-control-sm"
                                        value="<?= $selectedYear ?>" style="width: 100px;">
                                </form>
                                <div class="btn-group">
                                    <a id="btnPDF" href="export/exportpdf.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                                        class="btn btn-danger btn-sm">Export PDF</a>
                                    <a id="btnExcel" href="export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                                        class="btn btn-success btn-sm">Export Excel</a>
                                </div>
                            </div>
                        </div>
                        <div id="tabelContainer" class="table-responsive border rounded p-2">
                            <div class="text-center py-3 text-muted">Silakan pilih line / tahun</div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        </div>
    </body>

    </html>