    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lineUtama = document.getElementById('lineUtama');
            const bulanUtama = document.getElementById('bulanUtama');
            const tahunUtama = document.getElementById('tahunUtama');
            const tabelUtama = document.querySelector('.table-container');

            function updateTabelUtama() {
                const line = lineUtama.value;
                const bulan = bulanUtama.value;
                const tahun = tahunUtama.value;

                tabelUtama.innerHTML = '<div class="text-center py-3 text-muted">Loading data...</div>';

                fetch(`backend/inputharianajax.php?line=${line}&bulan=${bulan}&tahun=${tahun}`)
                    .then(res => res.text())
                    .then(html => tabelUtama.innerHTML = html)
                    .catch(err => {
                        tabelUtama.innerHTML = '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                        console.error(err);
                    });
            }

            // Auto update ketika filter berubah
            [lineUtama, bulanUtama, tahunUtama].forEach(el => {
                el.addEventListener('change', updateTabelUtama);
            });

            // Load pertama kali
            updateTabelUtama();
        });
    </script>

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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
                    <h5 class="m-0 font-weight-bold">📊 DASHBOARD DATA PRODUKSI</h5>
                </div>

                <!-- CHART PRODUKSI (BULANAN) -->
                <div class="card-body">
                    <div class="mb-5">
                        <h6 class="fw-bold text-primary mb-3">📈 CHART PRODUKSI (BULANAN)</h6>
                        <button id="refreshChart" class="btn btn-sm btn-primary">FILTER BULAN CHARTNYA</button>
                        <br>
                        <div class="chart-area border rounded p-3 bg-light">
                            <canvas id="myChart"></canvas>
                            <div id="chartLegend" class="mt-2"></div>
                        </div>
                    </div>
                </div>
                <!-- CHART PRODUKSI END -->

                <div class="card-body">

                    <!-- FILTER -->
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


                    <!-- DATA PRODUKSI (HARIAN) -->
                    <div class="mb-5">
                        <h6 class="fw-bold text-primary mb-3">📋 DATA PRODUKSI (HARIAN)</h6>
                        <div class="table-container border rounded p-2">
                            <div class="text-center py-3 text-muted">Memuat data...</div>
                        </div>
                    </div>


                    <!-- DATA TARGET PRODUKSI (TAHUNAN) -->
                    <div>
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

                </div>
            </div>
        </div>

        <!-- FUNGSI UNTUK MENAMPILKAN DATA TARGET START -->
        <!-- /.container-fluid -->


        <br>
        <br>
        <!-- Scroll to Top Button-->


        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/chart-index.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    </body>

    </html>
    <!-- footer end -->


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lineSelect = document.getElementById('lineSelect');
            const tahunInput = document.getElementById('tahunInput');
            const tabelContainer = document.getElementById('tabelContainer');
            const btnPDF = document.getElementById('btnPDF');
            const btnExcel = document.getElementById('btnExcel');

            function updateTabel() {
                const line = lineSelect.value;
                const tahun = tahunInput.value;

                // Update link export
                btnPDF.href = `export/exportpdf.php?line=${line}&tahun=${tahun}`;
                btnExcel.href = `export/export_excel.php?line=${line}&tahun=${tahun}`;

                // Tampilkan loading
                tabelContainer.innerHTML = '<div class="text-center py-3 text-muted">Loading data...</div>';

                // Ambil data via AJAX
                fetch(`backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
                    .then(res => res.text())
                    .then(html => {
                        tabelContainer.innerHTML = html;
                    })
                    .catch(err => {
                        tabelContainer.innerHTML = '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                        console.error(err);
                    });
            }

            // Trigger otomatis saat user ubah filter
            lineSelect.addEventListener('change', updateTabel);
            tahunInput.addEventListener('change', updateTabel);

            // Load pertama kali
            updateTabel();
        });
    </script>



    <!-- chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('myChart').getContext('2d');
            const bulan = document.getElementById('bulanUtama');
            const tahun = document.getElementById('tahunUtama');

            let currentDataset = 0; // indeks dataset aktif
            const datasetKeys = ['batch_count', 'productivity', 'production_speed', 'feed_raw_material'];
            const datasetLabels = ['Batch Count', 'Productivity', 'Production Speed', 'Feed Raw Material'];
            let chartInstance;

            const btnNext = document.createElement('button');
            const btnPrev = document.createElement('button');
            btnNext.className = 'btn btn-sm btn-primary ms-2';
            btnPrev.className = 'btn btn-sm btn-secondary me-2';
            btnPrev.textContent = '◀ Prev';
            btnNext.textContent = 'Next ▶';
            document.getElementById('chartLegend').append(btnPrev, btnNext);

            function loadChart() {
                const bulanVal = bulan.value;
                const tahunVal = tahun.value;

                fetch(`backend/chart-line.php?bulan=${bulanVal}&tahun=${tahunVal}`)
                    .then(res => res.json())
                    .then(data => {
                        renderChart(data);
                    })
                    .catch(err => console.error(err));
            }

            function renderChart(data) {
                const key = datasetKeys[currentDataset];
                const label = datasetLabels[currentDataset];

                const labels = data.lineA.map(row => row.hari);
                const dataA = data.lineA.map(row => parseFloat(row[key] || 0));
                const dataB = data.lineB.map(row => parseFloat(row[key] || 0));

                if (chartInstance) chartInstance.destroy();

                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: `Line A - ${label}`,
                                data: dataA,
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0,123,255,0.1)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3
                            },
                            {
                                label: `Line B - ${label}`,
                                data: dataB,
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40,167,69,0.1)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: `📊 Perbandingan ${label} (Line A vs Line B)`,
                                font: {
                                    size: 16
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Hari'
                                }
                            }
                        }
                    }
                });
            }

            // Tombol navigasi chart
            btnNext.addEventListener('click', () => {
                currentDataset = (currentDataset + 1) % datasetKeys.length;
                loadChart();
            });

            btnPrev.addEventListener('click', () => {
                currentDataset = (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
                loadChart();
            });

            // Auto ganti chart tiap 10 detik
            setInterval(() => {
                currentDataset = (currentDataset + 1) % datasetKeys.length;
                loadChart();
            }, 10000);

            // Load pertama kali
            loadChart();
        });
    </script>

    <!-- chart end -->