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
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link href="../css/ind.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<<<<<<< HEAD
    <<<<<<< HEAD
        <script src="../js/chartdashboard.js">
        </script>
        =======
        <script src="../js/script.js"></script>
        <script src="../js/export.js"></script>
        <!-- chartdashboard.js tidak lagi diperlukan eksternal; script lengkap disertakan di bawah -->
        >>>>>>> 399e89c (revisi filter chart)
=======
    <script src="../js/script.js"></script>
    <script src="../js/export.js"></script>
    <!-- chartdashboard.js tidak lagi diperlukan eksternal; script lengkap disertakan di bawah -->
>>>>>>> 399e89c (revisi filter chart)
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
</body>

</html>

<script>
    // ============================================================================
    // 📈 LINE CHART BULANAN
    // ============================================================================
    // Catatan: semua komentar aslinya tetap dipertahankan di bawah ini
    document.addEventListener("DOMContentLoaded", () => {
        // default values diambil dari input filter (jika ada) agar sinkron
        function getBulananFilters() {
            const bulanEl = document.getElementById('bulanFilter');
            const tahunEl = document.getElementById('tahunFilterBulanan');
            const bulan = bulanEl ? parseInt(bulanEl.value) : (new Date().getMonth() + 1);
            const tahun = tahunEl ? parseInt(tahunEl.value) : new Date().getFullYear();
            return {
                bulan,
                tahun
            };
        }

        function getTahunanFilters() {
            const tahunEl = document.getElementById('tahunFilterTahunan');
            const tahun = tahunEl ? parseInt(tahunEl.value) : new Date().getFullYear();
            return {
                tahun
            };
        }

        // 🎨 Warna dan dataset
        const warna = [
            "#1B1E23",
            "#FF90BB",
            "#FF0000",
            "#6f42c1",
            "#450693",
            "#E9FF97",
            "#fd7e14",
            "#6610f2",
            "#17a2b8",
            "#adb5bd",
        ];

        const datasetKeys = [
            "batch_count",
            "productivity",
            "production_speed",
            "feed_raw_material",
        ];
        const datasetLabels = [
            "Batch Count",
            "Productivity",
            "Production Speed",
            "Feed Raw Material",
        ];

        let currentDataset = 0; // 🔁 sinkronisasi global antar line

        // ========================================================================
        // 🧠 FUNGSI BUAT CHART (bulanan)
        // ========================================================================
        function createBarChart({
            canvasId,
            prevBtnId,
            nextBtnId,
            lineId
        }) {
            const ctx = document.getElementById(canvasId).getContext("2d");
            const btnPrev = document.getElementById(prevBtnId);
            const btnNext = document.getElementById(nextBtnId);
            let chartInstance = null;

            // 🔄 Render ulang chart (mengambil filter saat ini)
            function loadChart() {
                const {
                    bulan,
                    tahun
                } = getBulananFilters();
                fetch(`../backend/chart-line.php?bulan=${bulan}&tahun=${tahun}&line=${lineId}`)
                    .then((res) => res.json())
                    .then((data) => {
                        // data format: { lines: [...], target: {...} }
                        // ketika tidak ada data, tetap render kosong (agar chart tetap muncul)
                        const rows = data.lines || [];
                        const target = data.target || {};
                        renderChart(rows, target, bulan, tahun);
                    })
                    .catch((err) => console.error("Gagal load chart line " + lineId, err));
            }

            // 🎨 Render chart dengan garis target
            function renderChart(rows, targetData, bulan, tahun) {
                const key = datasetKeys[currentDataset];
                const label = datasetLabels[currentDataset];
                const labels = Array.from({
                    length: 31
                }, (_, i) => i + 1);

                const dataMap = {};
                rows.forEach((r) => {
                    // pastikan hari valid integer
                    const h = parseInt(r.hari);
                    if (!isNaN(h)) dataMap[h] = parseFloat(r[key]) || 0;
                });

                const values = labels.map((hari) => (dataMap[hari] !== undefined ? dataMap[hari] : 0));
                const targetKey = `target_${key}`;
                const targetVal = (targetData && targetData[targetKey] !== undefined) ? parseFloat(targetData[targetKey]) : 0;

                const datasetBars = {
                    label: `${label}`,
                    data: values,
                    backgroundColor: warna[(lineId - 1) % warna.length] + "88",
                    borderColor: warna[(lineId - 1) % warna.length],
                    borderWidth: 1.5,
                };

                const datasetTargetLine = {
                    label: `🎯 Target ${label}`,
                    data: Array(31).fill(targetVal),
                    type: "line",
                    borderColor: "#ff0000",
                    borderWidth: 2,
                    borderDash: [6, 4],
                    pointRadius: 0,
                    fill: false,
                };

                if (chartInstance) chartInstance.destroy();

                chartInstance = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels,
                        datasets: [datasetBars, datasetTargetLine],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                // Menggabungkan label dan target ke dalam title
                                text: [`📊 ${label} - Line ${lineId === 1 ? "A" : "B"} (${bulan}/${tahun})`, `Target: ${targetVal}`],
                                font: {
                                    size: 16
                                },
                            },
                            legend: {
                                position: "top",
                                labels: {
                                    boxWidth: 40
                                },
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: "Hari (1–31)"
                                },
                                ticks: {
                                    callback: function(val, index) {
                                        const day = labels[index];
                                        const resultValue = dataMap[day];
                                        // tampilkan hari dan value di tick label baris pertama
                                        return [day, resultValue !== undefined ? (Math.round(resultValue * 10) / 10) : ''];
                                    }
                                }
                            },
                        },
                    },
                });
            }

            // Tombol manual
            btnNext.addEventListener("click", () => {
                currentDataset = (currentDataset + 1) % datasetKeys.length;
                refreshAllBulananCharts();
            });

            btnPrev.addEventListener("click", () => {
                currentDataset = (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
                refreshAllBulananCharts();
            });

            return {
                loadChart
            };
        }

        // ========================================================================
        // 🅰️ & 🅱️ BUAT DUA CHART BULANAN SEKALIGUS
        // ========================================================================
        const chartA = createBarChart({
            canvasId: "BarChartA",
            prevBtnId: "prevBarA",
            nextBtnId: "nextBarA",
            lineId: 1,
        });
        const chartB = createBarChart({
            canvasId: "BarChartB",
            prevBtnId: "prevBarB",
            nextBtnId: "nextBarB",
            lineId: 2,
        });

        // Kalau filter bulan/tahun bulanan berubah, reload chart bulanan
        document.getElementById('bulanFilter')?.addEventListener('change', () => {
            refreshAllBulananCharts();
        });
        document.getElementById('tahunFilterBulanan')?.addEventListener('change', () => {
            refreshAllBulananCharts();
        });

        function refreshAllBulananCharts() {
            chartA.loadChart();
            chartB.loadChart();
        }

        // ========================================================================
        // 🔄 AUTO REFRESH SINKRON SETIAP 5 DETIK (ganti dataset otomatis)
        // ========================================================================
        setInterval(() => {
            currentDataset = (currentDataset + 1) % datasetKeys.length;
            refreshAllBulananCharts();
        }, 5000); // GANTI DATASET SETIAP 5 DETIK


        // ============================================================================
        // 📅 BAR CHART TAHUNAN PER LINE
        // ============================================================================
        // kita buat fungsi terpusat agar bisa dipanggil ulang saat filter tahun tahunan berubah
        const warnaA = "#007bff"; // biru
        const warnaB = "#28a745"; // hijau

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
        ];

        const bulanLabels = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember",
        ];

        let currentMetric = 0; // 🔁 sinkron antar line

        function renderTahunanChart({
            ctx,
            data,
            metric,
            warna,
            lineName,
            tahun
        }) {
            const dataBulan = data.bulanData || {};
            const targetData = data.target || {};

            const produksiData = bulanLabels.map((_, i) => {
                const bulan = i + 1;
                const avgKey = `avg_${metric.key}`;
                return dataBulan[bulan] && dataBulan[bulan][avgKey] !== undefined ?
                    parseFloat(dataBulan[bulan][avgKey]) :
                    0;
            });

            const targetKey = `target_${metric.key}`;
            const targetValue = targetData && targetData[targetKey] !== undefined ?
                parseFloat(targetData[targetKey]) :
                0;

            if (ctx.chartInstance) ctx.chartInstance.destroy();

            ctx.chartInstance = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: bulanLabels,
                    datasets: [{
                            label: `${metric.label}`,
                            data: produksiData,
                            backgroundColor: warna + "88",
                            borderColor: warna,
                            borderWidth: 1.5,
                            borderRadius: 4,
                        },
                        {
                            label: `🎯 Target ${metric.label}`,
                            data: Array(12).fill(targetValue),
                            type: "line",
                            borderColor: "#ff0000",
                            borderWidth: 2,
                            borderDash: [6, 4],
                            pointRadius: 0,
                            fill: false,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [`📘 ${lineName} — ${metric.label} (${tahun})`, `Target: ${targetValue}`],
                            font: {
                                size: 16
                            },
                        },
                        legend: {
                            position: "top"
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        x: {
                            title: {
                                display: true,
                                text: "Bulan"
                            },
                            ticks: {
                                callback: function(val, index) {
                                    const monthName = bulanLabels[index];
                                    const resultValue = produksiData[index];
                                    return [monthName, resultValue !== undefined ? (Math.round(resultValue * 10) / 10) : ''];
                                }
                            }
                        },
                    },
                },
            });
        }

        // fungsi load tahunan per line; ambil tahun dari filter tahun tahunan
        function loadTahunanCharts() {
            const {
                tahun
            } = getTahunanFilters();

            // Line A
            const ctxA = document.getElementById("BarCharttahunan").getContext("2d");
            fetch(`../backend/get_chart_tahunan.php?line=1&tahun=${tahun}`)
                .then((res) => res.json())
                .then((data) => {
                    renderTahunanChart({
                        ctx: ctxA,
                        data,
                        metric: metrics[currentMetric],
                        warna: warnaA,
                        lineName: "Line A",
                        tahun
                    });
                })
                .catch((err) => console.error("❌ Gagal load chart Line A (tahunan):", err));

            // Line B
            const ctxB = document.getElementById("BarCharttahunanLINEB").getContext("2d");
            fetch(`../backend/get_chart_tahunan.php?line=2&tahun=${tahun}`)
                .then((res) => res.json())
                .then((data) => {
                    renderTahunanChart({
                        ctx: ctxB,
                        data,
                        metric: metrics[currentMetric],
                        warna: warnaB,
                        lineName: "Line B",
                        tahun
                    });
                })
                .catch((err) => console.error("❌ Gagal load chart Line B (tahunan):", err));
        }

        // tombol next/prev untuk tahunan
        document.getElementById('nextTahunan')?.addEventListener('click', () => {
            currentMetric = (currentMetric + 1) % metrics.length;
            loadTahunanCharts();
        });
        document.getElementById('prevTahunan')?.addEventListener('click', () => {
            currentMetric = (currentMetric - 1 + metrics.length) % metrics.length;
            loadTahunanCharts();
        });
        document.getElementById('nextTahunanB')?.addEventListener('click', () => {
            currentMetric = (currentMetric + 1) % metrics.length;
            loadTahunanCharts();
        });
        document.getElementById('prevTahunanB')?.addEventListener('click', () => {
            currentMetric = (currentMetric - 1 + metrics.length) % metrics.length;
            loadTahunanCharts();
        });

        // jika filter tahun tahunan berubah, reload
        document.getElementById('tahunFilterTahunan')?.addEventListener('change', loadTahunanCharts);

        // auto rotate metric tiap 5 detik (tahunan)
        setInterval(() => {
            currentMetric = (currentMetric + 1) % metrics.length;
            loadTahunanCharts();
        }, 5000);

        // load pertama kali
        refreshAllBulananCharts();
        loadTahunanCharts();


        // ============================================================================
        // 🧾 Fungsi Export PDF (umum, dipakai oleh tombol export di tiap section)
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
            // Dapatkan tanggal saat ini
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const dateString = `${year}-${month}-${day}`;
            pdf.text(title, 15, 15);
            // ukuran & posisi gambar disesuaikan agar muat
            // width 277mm pada landscape A4 (pdf internal unit mm) minus margin 20 -> sekitar 257
            pdf.addImage(imgData, "PNG", 10, 25, 277 - 20, 160);
            pdf.save(`${title}_${dateString}.pdf`);
        }

        // ============================================================================
        // 📥 AKTIFKAN TOMBOL EXPORT PDF DI SEMUA CHART
        // ============================================================================
        document.querySelectorAll(".exportChartPDF").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                // cari section terdekat -> ambil canvas di dalamnya
                const section = e.target.closest("section");
                const canvas = section ? section.querySelector("canvas") : null;
                const title = section ? (section.querySelector("h6")?.innerText || "Chart") : "Chart";
                if (canvas) {
                    exportChartPDF(canvas.id, title);
                } else {
                    alert("Canvas chart tidak ditemukan di section ini!");
                }
            });
        });

    }); // end DOMContentLoaded utama
</script>