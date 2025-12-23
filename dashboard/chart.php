<?php
require_once '../backend/config.php';
$page_title = "chart"
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

</body>

</html>

<script>
    // ============================================================================
    // 📈 LINE CHART BULANAN
    // ============================================================================
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
            "#0046FF",
            "#F9E400",
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
            "batch_weight",
            "operation_factor",
            "cycle_time",
            "grade_change_sequence",
            "grade_change_time",
        ];
        const datasetLabels = [
            "Batch Count",
            "Productivity",
            "Production Speed",
            "Feed Raw Material",
            "Batch Weight",
            "Operation Factor",
            "Cycle Time",
            "Grade Change Sequence",
            "Grade Change Time",
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
        // setInterval(() => {
        //     currentDataset = (currentDataset + 1) % datasetKeys.length;
        //     refreshAllBulananCharts();
        // }, 5000); // GANTI DATASET SETIAP 5 DETIK


        // ============================================================================
        // 📅 BAR CHART TAHUNAN PER LINE
        // ============================================================================
        // kita buat fungsi terpusat agar bisa dipanggil ulang saat filter tahun tahunan berubah
        const warnaA = "#007bff"; // biru
        const warnaB = "#F9E400"; // hijau

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

        let currentMetric = 0;

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
        // setInterval(() => {
        //     currentMetric = (currentMetric + 1) % metrics.length;
        //     loadTahunanCharts();
        // }, 5000);

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