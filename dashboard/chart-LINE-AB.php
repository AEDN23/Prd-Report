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
    <?php include '../layout/sidebar.php'; ?>
    <link href="../css/ind.css" rel="stylesheet">
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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>

</html>

<script>
    // ============================================================================
    // 📈 LINE CHART BULANAN (SEMUA LINE DIGABUNG)
    // ============================================================================
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById("myChart").getContext("2d");
        const bulan = document.getElementById("bulanUtama");
        const tahun = document.getElementById("tahunUtama");

        const btnPrev = document.getElementById("prevLine");
        const btnNext = document.getElementById("nextLine");
        const btnExport = document.getElementById("exportPDF");

        let currentDataset = 0;
        const datasetKeys = [
            "batch_count", "productivity", "production_speed", "feed_raw_material",
            "batch_weight", "operation_factor", "cycle_time", "grade_change_sequence", "grade_change_time"
        ];
        const datasetLabels = [
            "Batch Count", "Productivity", "Production Speed", "Feed Raw Material",
            "Batch Weight", "Operation Factor", "Cycle Time", "Grade Change Sequence", "Grade Change Time"
        ];
        let chartInstance;

        function loadChart() {
            const bulanVal = bulan.value;
            const tahunVal = tahun.value;

            console.log("🔄 Loading LINE chart dengan bulan:", bulanVal, "tahun:", tahunVal);

            // Tampilkan loading state
            if (chartInstance) {
                chartInstance.destroy();
            }
            showLoadingState();

            fetch(`../backend/chart-line-ab.php?bulan=${bulanVal}&tahun=${tahunVal}`)
                .then((res) => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then((data) => {
                    console.log("✅ Data LINE dari backend:", data);

                    if (data && data.lines && Object.keys(data.lines).length > 0) {
                        console.log("📊 Lines available:", Object.keys(data.lines));
                        renderChart(data.lines);
                    } else {
                        console.warn("⚠️ Data chart kosong atau tidak sesuai format");
                        renderEmptyChart("Tidak ada data produksi untuk periode ini");
                    }
                })
                .catch((err) => {
                    console.error("❌ Gagal load LINE chart:", err);
                    renderEmptyChart("Gagal memuat data: " + err.message);
                });
        }

        function showLoadingState() {
            chartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels: Array.from({
                        length: 31
                    }, (_, i) => i + 1),
                    datasets: [{
                        label: "Memuat data...",
                        data: Array(31).fill(0),
                        borderColor: "#cccccc",
                        backgroundColor: "#cccccc33",
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: "⏳ Memuat data..."
                        }
                    }
                }
            });
        }

        function renderChart(lines) {
            const key = datasetKeys[currentDataset];
            const label = datasetLabels[currentDataset];
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);

            const warna = [
                "#0046FF", // Biru - Line A
                "#F9E400", // Kuning - Line B  
                "#FF90BB", // Pink - Line C
                "#450693", // Ungu
                "#6f42c1", // Ungu muda
            ];

            const datasets = [];
            let colorIndex = 0;
            let totalDataPoints = 0;

            Object.entries(lines).forEach(([lineName, dataLine]) => {
                console.log(`🔍 Processing ${lineName}:`, dataLine);

                if (dataLine && dataLine.length > 0) {
                    const dataMap = {};
                    dataLine.forEach((row) => {
                        const hari = parseInt(row.hari);
                        if (!isNaN(hari) && row[key] !== null && row[key] !== undefined) {
                            dataMap[hari] = parseFloat(row[key]) || 0;
                            totalDataPoints++;
                        }
                    });

                    console.log(`📈 ${lineName} - Data points for ${key}:`, dataMap);

                    const hasData = Object.values(dataMap).some(val => val > 0);
                    if (hasData) {
                        datasets.push({
                            label: `${lineName} - ${label}`,
                            data: labels.map((hari) => dataMap[hari] || 0),
                            borderColor: warna[colorIndex % warna.length],
                            backgroundColor: warna[colorIndex % warna.length] + "33", // 🔥 ADD BACKGROUND COLOR
                            pointBackgroundColor: warna[colorIndex % warna.length], // 🔥 ADD POINT FILL COLOR
                            pointBorderColor: "#ffffff", // 🔥 ADD POINT BORDER
                            pointBorderWidth: 2, // 🔥 ADD POINT BORDER WIDTH
                            fill: true, // 🔥 ENABLE AREA FILL
                            tension: 0.3,
                            pointRadius: 6, // 🔥 INCREASE POINT SIZE
                            pointHoverRadius: 8, // 🔥 HOVER EFFECT
                            borderWidth: 3, // 🔥 THICKER LINE
                        });
                        colorIndex++;
                    } else {
                        console.log(`➖ ${lineName} tidak memiliki data untuk ${key}`);
                    }
                } else {
                    console.log(`❌ ${lineName} tidak memiliki data sama sekali`);
                }
            });

            // ... rest of the code remains the same
            console.log(`📊 Total datasets: ${datasets.length}, Total data points: ${totalDataPoints}`);

            // Jika tidak ada data sama sekali
            if (datasets.length === 0) {
                console.log("📭 Tidak ada data yang bisa ditampilkan");
                renderEmptyChart(`Tidak ada data ${label} untuk periode ini`);
                return;
            }

            if (chartInstance) chartInstance.destroy();

            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulan.value, 10) - 1] || bulan.value;

            chartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels,
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: `📊 ${label} - Perbandingan Line Produksi (${bulanNama} ${tahun.value})`,
                            font: {
                                size: 16
                            },
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: label
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: "Hari (1–31)"
                            }
                        },
                    },
                },
            });
        }

        function renderEmptyChart(message) {
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);
            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels,
                    datasets: [{
                        label: "Tidak ada data",
                        data: labels.map(() => 0),
                        borderColor: "#cccccc",
                        backgroundColor: "#cccccc33",
                        fill: false,
                        pointRadius: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: message || "📊 Tidak ada data produksi"
                        }
                    },
                    scales: {
                        y: {
                            display: false
                        },
                        x: {
                            display: false
                        }
                    }
                }
            });
        }

        btnNext.addEventListener("click", () => {
            currentDataset = (currentDataset + 1) % datasetKeys.length;
            console.log(`🔄 Switching to dataset: ${datasetLabels[currentDataset]}`);
            loadChart();
        });

        btnPrev.addEventListener("click", () => {
            currentDataset = (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
            console.log(`🔄 Switching to dataset: ${datasetLabels[currentDataset]}`);
            loadChart();
        });

        btnExport.addEventListener("click", () => {
            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulan.value, 10) - 1] || bulan.value;
            const title = `Chart_Line_${datasetLabels[currentDataset]}_${bulanNama}_${tahun.value}`;
            exportChartPDF("myChart", title);
        });

        [bulan, tahun].forEach((el) => el.addEventListener("change", loadChart));

        // Load pertama kali
        console.log("🚀 Initializing LINE chart...");
        loadChart();
    });

    // ============================================================================
    // 📊 BAR CHART BULANAN (SEMUA LINE DIGABUNG)
    // ============================================================================
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById("BarChart").getContext("2d");
        const bulan = document.getElementById("bulanUtama");
        const tahun = document.getElementById("tahunUtama");

        const btnPrev = document.getElementById("prevBar");
        const btnNext = document.getElementById("nextBar");
        const btnExport = document.getElementById("exportPDFbarchart");

        let currentDataset = 0;
        const datasetKeys = [
            "batch_count", "productivity", "production_speed", "feed_raw_material",
            "batch_weight", "operation_factor", "cycle_time", "grade_change_sequence", "grade_change_time"
        ];
        const datasetLabels = [
            "Batch Count", "Productivity", "Production Speed", "Feed Raw Material",
            "Batch Weight", "Operation Factor", "Cycle Time", "Grade Change Sequence", "Grade Change Time"
        ];
        let chartInstance;

        function loadChart() {
            const bulanVal = bulan.value;
            const tahunVal = tahun.value;

            console.log("🔄 Loading BAR chart dengan bulan:", bulanVal, "tahun:", tahunVal);

            // Tampilkan loading state
            if (chartInstance) {
                chartInstance.destroy();
            }
            showLoadingState();

            fetch(`../backend/chart-line-ab.php?bulan=${bulanVal}&tahun=${tahunVal}`)
                .then((res) => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then((data) => {
                    console.log("✅ Data BAR dari backend:", data);

                    if (data && data.lines && Object.keys(data.lines).length > 0) {
                        console.log("📊 Lines available:", Object.keys(data.lines));
                        renderChart(data.lines);
                    } else {
                        console.warn("⚠️ Data chart kosong atau tidak sesuai format");
                        renderEmptyChart("Tidak ada data produksi untuk periode ini");
                    }
                })
                .catch((err) => {
                    console.error("❌ Gagal load BAR chart:", err);
                    renderEmptyChart("Gagal memuat data: " + err.message);
                });
        }

        function showLoadingState() {
            chartInstance = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: Array.from({
                        length: 31
                    }, (_, i) => i + 1),
                    datasets: [{
                        label: "Memuat data...",
                        data: Array(31).fill(0),
                        backgroundColor: "#cccccc88",
                        borderColor: "#cccccc",
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: "⏳ Memuat data..."
                        }
                    }
                }
            });
        }

        function renderChart(lines) {
            const key = datasetKeys[currentDataset];
            const label = datasetLabels[currentDataset];
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);

            const warna = [
                "#0046FF88", "#F9E40088", "#FF90BB88", "#45069388", "#6f42c188"
            ];

            const datasets = [];
            let colorIndex = 0;
            let totalDataPoints = 0;

            Object.entries(lines).forEach(([lineName, dataLine]) => {
                console.log(`🔍 Processing BAR ${lineName}:`, dataLine);

                if (dataLine && dataLine.length > 0) {
                    const dataMap = {};
                    dataLine.forEach((row) => {
                        const hari = parseInt(row.hari);
                        if (!isNaN(hari) && row[key] !== null && row[key] !== undefined) {
                            dataMap[hari] = parseFloat(row[key]) || 0;
                            totalDataPoints++;
                        }
                    });

                    console.log(`📊 ${lineName} - BAR Data points for ${key}:`, dataMap);

                    const hasData = Object.values(dataMap).some(val => val > 0);
                    if (hasData) {
                        datasets.push({
                            label: `${lineName} - ${label}`,
                            data: labels.map((hari) => dataMap[hari] || 0),
                            backgroundColor: warna[colorIndex % warna.length],
                            borderColor: warna[colorIndex % warna.length].replace('88', ''),
                            borderWidth: 1.5,
                        });
                        colorIndex++;
                    } else {
                        console.log(`➖ ${lineName} tidak memiliki data BAR untuk ${key}`);
                    }
                } else {
                    console.log(`❌ ${lineName} tidak memiliki data BAR sama sekali`);
                }
            });

            console.log(`📊 BAR Total datasets: ${datasets.length}, Total data points: ${totalDataPoints}`);

            if (datasets.length === 0) {
                console.log("📭 Tidak ada data BAR yang bisa ditampilkan");
                renderEmptyChart(`Tidak ada data ${label} untuk periode ini`);
                return;
            }

            if (chartInstance) chartInstance.destroy();

            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulan.value, 10) - 1] || bulan.value;

            chartInstance = new Chart(ctx, {
                type: "bar",
                data: {
                    labels,
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: `📊 ${label} - Perbandingan Line Produksi (${bulanNama} ${tahun.value})`,
                            font: {
                                size: 16
                            },
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: label
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: "Hari (1–31)"
                            }
                        },
                    },
                },
            });
        }

        function renderEmptyChart(message) {
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);
            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctx, {
                type: "bar",
                data: {
                    labels,
                    datasets: [{
                        label: "Tidak ada data",
                        data: labels.map(() => 0),
                        backgroundColor: "#cccccc88",
                        borderColor: "#cccccc",
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: message || "📊 Tidak ada data produksi"
                        }
                    },
                    scales: {
                        y: {
                            display: false
                        },
                        x: {
                            display: false
                        }
                    }
                }
            });
        }

        btnNext.addEventListener("click", () => {
            currentDataset = (currentDataset + 1) % datasetKeys.length;
            console.log(`🔄 BAR Switching to dataset: ${datasetLabels[currentDataset]}`);
            loadChart();
        });

        btnPrev.addEventListener("click", () => {
            currentDataset = (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
            console.log(`🔄 BAR Switching to dataset: ${datasetLabels[currentDataset]}`);
            loadChart();
        });

        btnExport.addEventListener("click", () => {
            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulan.value, 10) - 1] || bulan.value;
            const title = `Chart_Bar_${datasetLabels[currentDataset]}_${bulanNama}_${tahun.value}`;
            exportChartPDF("BarChart", title);
        });

        [bulan, tahun].forEach((el) => el.addEventListener("change", loadChart));

        // Load pertama kali
        console.log("🚀 Initializing BAR chart...");
        loadChart();
    });

    // ============================================================================
    // 📅 BAR CHART TAHUNAN (SEMUA LINE DIGABUNG)
    // ============================================================================
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById("BarCharttahunan").getContext("2d");
        const tahunInput = document.getElementById("tahunInput");

        const btnPrev = document.getElementById("prevTahunan");
        const btnNext = document.getElementById("nextTahunan");

        let currentMetric = 0;
        let chart;

        const metrics = [{
                key: "productivity",
                label: "Productivity (Ton/Shift)"
            },
            {
                key: "batch_count",
                label: "Batch Count (Per Day)"
            },
            {
                key: "production_speed",
                label: "Production Speed (Kg/Min)"
            },
            {
                key: "feed_raw_material",
                label: "Feed Raw Material (Kg/Day)"
            },
            {
                key: "operation_factor",
                label: "Operation Factor (%)"
            },
            {
                key: "batch_weight",
                label: "Batch Weight (Kg/Batch)"
            },
            {
                key: "cycle_time",
                label: "Cycle Time (Min/Batch)"
            },
            {
                key: "grade_change_sequence",
                label: "Grade Change Sequence"
            },
            {
                key: "grade_change_time",
                label: "Grade Change Time (Min/Grade)"
            }
        ];

        const bulanLabels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const warna = ["#0046FF88", "#F9E40088", "#FF90BB88", "#45069388"];

        function loadChartyears() {
            const tahunVal = tahunInput.value;

            // Ambil data untuk semua line
            Promise.all([
                    fetch(`../backend/get_chart_tahunan.php?line=1&tahun=${tahunVal}`).then(res => res.json()),
                    fetch(`../backend/get_chart_tahunan.php?line=2&tahun=${tahunVal}`).then(res => res.json()),
                    fetch(`../backend/get_chart_tahunan.php?line=3&tahun=${tahunVal}`).then(res => res.json())
                ])
                .then(([dataLineA, dataLineB, dataLineC]) => {
                    const allData = {
                        'LINE A': dataLineA,
                        'LINE B': dataLineB,
                        'LINE C': dataLineC
                    };
                    renderChart(allData);
                })
                .catch((err) => console.error("Gagal ambil data chart tahunan:", err));
        }

        function renderChart(allData) {
            const metric = metrics[currentMetric];
            const tahun = tahunInput.value;

            const datasets = [];
            let colorIndex = 0;

            Object.entries(allData).forEach(([lineName, data]) => {
                const dataBulan = data.bulanData || {};

                const produksiData = bulanLabels.map((_, i) => {
                    const bulan = i + 1;
                    const avgKey = `avg_${metric.key}`;
                    return dataBulan[bulan] && dataBulan[bulan][avgKey] !== undefined ?
                        parseFloat(dataBulan[bulan][avgKey]) : null;
                });

                // Hanya tambahkan jika ada data
                const hasData = produksiData.some(val => val !== null && val > 0);
                if (hasData) {
                    datasets.push({
                        label: `${lineName} - ${metric.label}`,
                        data: produksiData,
                        backgroundColor: warna[colorIndex % warna.length],
                        borderColor: warna[colorIndex % warna.length].replace('88', ''),
                        borderWidth: 1.5,
                        borderRadius: 4,
                    });
                    colorIndex++;
                }
            });

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: bulanLabels,
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: `📘 ${metric.label} - Perbandingan Line Produksi (${tahun})`,
                            font: {
                                size: 16
                            },
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: metric.label
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: "Bulan"
                            }
                        },
                    },
                },
            });
        }

        btnNext.addEventListener("click", () => {
            currentMetric = (currentMetric + 1) % metrics.length;
            loadChartyears();
        });

        btnPrev.addEventListener("click", () => {
            currentMetric = (currentMetric - 1 + metrics.length) % metrics.length;
            loadChartyears();
        });

        tahunInput.addEventListener("change", loadChartyears);
        loadChartyears();
    });

    // ============================================================================
    // 🧾 Fungsi Export PDF
    // ============================================================================
    function exportChartPDF(canvasId, title) {
        const {
            jsPDF
        } = window.jspdf;
        const pdf = new jsPDF("l", "mm", "a4");
        const canvas = document.getElementById(canvasId);

        if (!canvas) {
            alert('Canvas tidak ditemukan');
            return;
        }

        const imgData = canvas.toDataURL("image/png");
        const today = new Date();
        const dateString = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;

        pdf.text(title, 15, 15);
        pdf.addImage(imgData, "PNG", 10, 25, 277 - 20, 150);
        pdf.save(`${title}_${dateString}.pdf`);
    }
</script>