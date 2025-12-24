<?php
require_once '../backend/config.php';
$page_title = "Chart Shift"
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
            <form id="filter-chart-bulanan-pershift" class="row g-3 mb-3">
                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select id="bulanFilterPershift" name="bulan" class="form-select">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input id="tahunFilterBulananPershift" type="number" name="tahun" value="<?= date('Y') ?>" class="form-control">
                </div>
            </form>
            <!-- FILTER CHART BULANAN -->



            <!-- ========================== LINE A BULANAN =========================== -->
            <section id="chart-bar-bulanan-LINEA-pershift" style="height: 100vh;" class="mb-5">
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
            <section id="chart-bar-bulanan-LINEB-pershift" class="mb-5" style="height: 100vh">
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
    // SCRIPT UNTUK CHART BAR BULANAN PERSHIFT - AUTO REFRESH FIXED

    // ============================================================================
    // 📊 GLOBAL VARIABLES DAN FUNCTIONS
    // ============================================================================
    let currentDatasetA = 0;
    let currentDatasetB = 0;

    const datasetKeys = [
        "batch_count", "productivity", "production_speed", "feed_raw_material",
        "batch_weight", "operation_factor", "cycle_time", "grade_change_sequence", "grade_change_time"
    ];

    const datasetLabels = [
        "Batch Count", "Productivity", "Production Speed", "Feed Raw Material",
        "Batch Weight", "Operation Factor", "Cycle Time", "Grade Change Sequence", "Grade Change Time"
    ];

    // ============================================================================
    // 📊 CHART BAR BULANAN PER SHIFT - LINE A
    // ============================================================================
    function initializeChartA() {
        const ctxA = document.getElementById("BarChartA").getContext("2d");
        const bulanFilter = document.getElementById("bulanFilterPershift");
        const tahunFilter = document.getElementById("tahunFilterBulananPershift");

        const btnPrevA = document.getElementById("prevBarA");
        const btnNextA = document.getElementById("nextBarA");
        const btnExportA = document.querySelector("#chart-bar-bulanan-LINEA-pershift .exportChartPDF");

        let chartInstanceA;

        function loadChartA() {
            const bulanVal = bulanFilter.value;
            const tahunVal = tahunFilter.value;
            const lineId = 1; // LINE A

            console.log("🔄 Loading BAR chart LINE A dengan bulan:", bulanVal, "tahun:", tahunVal);

            // Tampilkan loading state
            if (chartInstanceA) {
                chartInstanceA.destroy();
            }
            showLoadingStateA();

            fetch(`../backend/chart-shift.php?bulan=${bulanVal}&tahun=${tahunVal}&line_id=${lineId}`)
                .then((res) => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then((data) => {
                    console.log("✅ Data SHIFT LINE A dari backend:", data);

                    if (data && data.shifts && Object.keys(data.shifts).length > 0) {
                        console.log("📊 Shifts available:", Object.keys(data.shifts));
                        renderChartA(data.shifts);
                    } else {
                        console.warn("⚠️ Data chart kosong atau tidak sesuai format");
                        renderEmptyChartA("Tidak ada data produksi untuk periode ini");
                    }
                })
                .catch((err) => {
                    console.error("❌ Gagal load SHIFT chart LINE A:", err);
                    renderEmptyChartA("Gagal memuat data: " + err.message);
                });
        }

        function showLoadingStateA() {
            chartInstanceA = new Chart(ctxA, {
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
                            text: "⏳ Memuat data LINE A..."
                        }
                    }
                }
            });
        }

        function renderChartA(shifts) {
            const key = datasetKeys[currentDatasetA];
            const label = datasetLabels[currentDatasetA];
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);

            const warna = [
                "#0046FF88", // Shift 1 - Biru
                "#F9E40088", // Shift 2 - Kuning
                "#FF90BB88", // Shift 3 - Pink
                "#f1055f88", // semua shift - Pink
            ];

            const datasets = [];
            let totalDataPoints = 0;

            // Loop melalui semua shift (1, 2, 3)
            Object.entries(shifts).forEach(([shiftNum, shiftData]) => {
                console.log(`🔍 Processing Shift ${shiftNum}:`, shiftData);

                if (shiftData && Object.keys(shiftData).length > 0) {
                    const dataMap = {};

                    // Map data per hari untuk shift ini
                    Object.entries(shiftData).forEach(([hari, row]) => {
                        const hariInt = parseInt(hari);
                        if (!isNaN(hariInt) && row[key] !== null && row[key] !== undefined) {
                            dataMap[hariInt] = parseFloat(row[key]) || 0;
                            totalDataPoints++;
                        }
                    });

                    console.log(`📊 Shift ${shiftNum} - Data points for ${key}:`, dataMap);

                    const hasData = Object.values(dataMap).some(val => val > 0);
                    if (hasData) {
                        datasets.push({
                            label: `Shift ${shiftNum} - ${label}`,
                            data: labels.map((hari) => dataMap[hari] || 0),
                            backgroundColor: warna[(shiftNum - 1) % warna.length],
                            borderColor: warna[(shiftNum - 1) % warna.length].replace('88', ''),
                            borderWidth: 1.5,
                            borderRadius: 4,
                        });
                    } else {
                        console.log(`➖ Shift ${shiftNum} tidak memiliki data untuk ${key}`);
                    }
                } else {
                    console.log(`❌ Shift ${shiftNum} tidak memiliki data sama sekali`);
                }
            });

            console.log(`📊 LINE A - Total datasets: ${datasets.length}, Total data points: ${totalDataPoints}`);

            if (datasets.length === 0) {
                console.log("📭 Tidak ada data yang bisa ditampilkan untuk LINE A");
                renderEmptyChartA(`Tidak ada data ${label} untuk periode ini`);
                return;
            }

            if (chartInstanceA) chartInstanceA.destroy();

            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulanFilter.value, 10) - 1] || bulanFilter.value;

            chartInstanceA = new Chart(ctxA, {
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
                            text: `📊 LINE A - ${label} - Perbandingan Shift (${bulanNama} ${tahunFilter.value})`,
                            font: {
                                size: 16
                            },
                        },
                        legend: {
                            position: 'top'
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
                            },
                            ticks: {
                                maxTicksLimit: 31
                            }
                        },
                    },
                    interaction: {
                        mode: 'index'
                    }
                },
            });
        }

        function renderEmptyChartA(message) {
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);
            if (chartInstanceA) chartInstanceA.destroy();

            chartInstanceA = new Chart(ctxA, {
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
                            text: message || "📊 LINE A - Tidak ada data produksi"
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

        // Event listeners untuk LINE A
        btnNextA.addEventListener("click", () => {
            currentDatasetA = (currentDatasetA + 1) % datasetKeys.length;
            console.log(`🔄 LINE A Switching to dataset: ${datasetLabels[currentDatasetA]}`);
            loadChartA();
        });

        btnPrevA.addEventListener("click", () => {
            currentDatasetA = (currentDatasetA - 1 + datasetKeys.length) % datasetKeys.length;
            console.log(`🔄 LINE A Switching to dataset: ${datasetLabels[currentDatasetA]}`);
            loadChartA();
        });

        btnExportA.addEventListener("click", () => {
            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulanFilter.value, 10) - 1] || bulanFilter.value;
            const title = `LINE_A_Chart_Bar_${datasetLabels[currentDatasetA]}_${bulanNama}_${tahunFilter.value}`;
            exportChartPDF("BarChartA", title);
        });

        // Return loadChartA function agar bisa diakses dari luar
        return loadChartA;
    }

    // ============================================================================
    // 📊 CHART BAR BULANAN PER SHIFT - LINE B
    // ============================================================================
    function initializeChartB() {
        const ctxB = document.getElementById("BarChartB").getContext("2d");
        const bulanFilter = document.getElementById("bulanFilterPershift");
        const tahunFilter = document.getElementById("tahunFilterBulananPershift");

        const btnPrevB = document.getElementById("prevBarB");
        const btnNextB = document.getElementById("nextBarB");
        const btnExportB = document.querySelector("#chart-bar-bulanan-LINEB-pershift .exportChartPDF");

        let chartInstanceB;

        function loadChartB() {
            const bulanVal = bulanFilter.value;
            const tahunVal = tahunFilter.value;
            const lineId = 2; // LINE B

            console.log("🔄 Loading BAR chart LINE B dengan bulan:", bulanVal, "tahun:", tahunVal);

            // Tampilkan loading state
            if (chartInstanceB) {
                chartInstanceB.destroy();
            }
            showLoadingStateB();

            fetch(`../backend/chart-shift.php?bulan=${bulanVal}&tahun=${tahunVal}&line_id=${lineId}`)
                .then((res) => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then((data) => {
                    console.log("✅ Data SHIFT LINE B dari backend:", data);

                    if (data && data.shifts && Object.keys(data.shifts).length > 0) {
                        console.log("📊 Shifts available:", Object.keys(data.shifts));
                        renderChartB(data.shifts);
                    } else {
                        console.warn("⚠️ Data chart kosong atau tidak sesuai format");
                        renderEmptyChartB("Tidak ada data produksi untuk periode ini");
                    }
                })
                .catch((err) => {
                    console.error("❌ Gagal load SHIFT chart LINE B:", err);
                    renderEmptyChartB("Gagal memuat data: " + err.message);
                });
        }

        function showLoadingStateB() {
            chartInstanceB = new Chart(ctxB, {
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
                            text: "⏳ Memuat data LINE B..."
                        }
                    }
                }
            });
        }

        function renderChartB(shifts) {
            const key = datasetKeys[currentDatasetB];
            const label = datasetLabels[currentDatasetB];
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);

            const warna = [
                "#0046FF88", // Shift 1 - Biru
                "#F9E40088", // Shift 2 - Kuning
                "#FF90BB88", // Shift 3 - Pink
                "#f5025f88", // S4- SEMUA SHIFT
            ];

            const datasets = [];
            let totalDataPoints = 0;

            // Loop melalui semua shift (1, 2, 3)
            Object.entries(shifts).forEach(([shiftNum, shiftData]) => {
                console.log(`🔍 Processing Shift ${shiftNum}:`, shiftData);

                if (shiftData && Object.keys(shiftData).length > 0) {
                    const dataMap = {};

                    // Map data per hari untuk shift ini
                    Object.entries(shiftData).forEach(([hari, row]) => {
                        const hariInt = parseInt(hari);
                        if (!isNaN(hariInt) && row[key] !== null && row[key] !== undefined) {
                            dataMap[hariInt] = parseFloat(row[key]) || 0;
                            totalDataPoints++;
                        }
                    });

                    console.log(`📊 Shift ${shiftNum} - Data points for ${key}:`, dataMap);

                    const hasData = Object.values(dataMap).some(val => val > 0);
                    if (hasData) {
                        datasets.push({
                            label: `Shift ${shiftNum} - ${label}`,
                            data: labels.map((hari) => dataMap[hari] || 0),
                            backgroundColor: warna[(shiftNum - 1) % warna.length],
                            borderColor: warna[(shiftNum - 1) % warna.length].replace('88', ''),
                            borderWidth: 1.5,
                            borderRadius: 4,
                        });
                    } else {
                        console.log(`➖ Shift ${shiftNum} tidak memiliki data untuk ${key}`);
                    }
                } else {
                    console.log(`❌ Shift ${shiftNum} tidak memiliki data sama sekali`);
                }
            });

            console.log(`📊 LINE B - Total datasets: ${datasets.length}, Total data points: ${totalDataPoints}`);

            if (datasets.length === 0) {
                console.log("📭 Tidak ada data yang bisa ditampilkan untuk LINE B");
                renderEmptyChartB(`Tidak ada data ${label} untuk periode ini`);
                return;
            }

            if (chartInstanceB) chartInstanceB.destroy();

            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulanFilter.value, 10) - 1] || bulanFilter.value;

            chartInstanceB = new Chart(ctxB, {
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
                            text: `📊 LINE B - ${label} - Perbandingan Shift (${bulanNama} ${tahunFilter.value})`,
                            font: {
                                size: 16
                            },
                        },
                        legend: {
                            position: 'top'
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
                            },
                            ticks: {
                                maxTicksLimit: 31
                            }
                        },
                    },
                    interaction: {
                        mode: 'index'
                    }
                },
            });
        }

        function renderEmptyChartB(message) {
            const labels = Array.from({
                length: 31
            }, (_, i) => i + 1);
            if (chartInstanceB) chartInstanceB.destroy();

            chartInstanceB = new Chart(ctxB, {
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
                            text: message || "📊 LINE B - Tidak ada data produksi"
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

        // Event listeners untuk LINE B
        btnNextB.addEventListener("click", () => {
            currentDatasetB = (currentDatasetB + 1) % datasetKeys.length;
            console.log(`🔄 LINE B Switching to dataset: ${datasetLabels[currentDatasetB]}`);
            loadChartB();
        });

        btnPrevB.addEventListener("click", () => {
            currentDatasetB = (currentDatasetB - 1 + datasetKeys.length) % datasetKeys.length;
            console.log(`🔄 LINE B Switching to dataset: ${datasetLabels[currentDatasetB]}`);
            loadChartB();
        });

        btnExportB.addEventListener("click", () => {
            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ];
            const bulanNama = namaBulan[parseInt(bulanFilter.value, 10) - 1] || bulanFilter.value;
            const title = `LINE_B_Chart_Bar_${datasetLabels[currentDatasetB]}_${bulanNama}_${tahunFilter.value}`;
            exportChartPDF("BarChartB", title);
        });

        // Return loadChartB function agar bisa diakses dari luar
        return loadChartB;
    }

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

    // ============================================================================
    // 🔄 MAIN INITIALIZATION - AUTO REFRESH FIXED
    // ============================================================================
    document.addEventListener("DOMContentLoaded", function() {
        console.log("🚀 Initializing Chart Shift System...");

        // Initialize charts dan dapatkan reference ke load functions
        const loadChartA = initializeChartA();
        const loadChartB = initializeChartB();

        // Get filter elements
        const bulanFilter = document.getElementById('bulanFilterPershift');
        const tahunFilter = document.getElementById('tahunFilterBulananPershift');

        // Event listener untuk perubahan filter - AUTO REFRESH
        if (bulanFilter) {
            bulanFilter.addEventListener('change', function() {
                console.log('🔄 Bulan changed to:', this.value);
                // Auto refresh kedua chart
                if (typeof loadChartA === 'function') loadChartA();
                if (typeof loadChartB === 'function') loadChartB();
            });
        }

        if (tahunFilter) {
            tahunFilter.addEventListener('change', function() {
                console.log('🔄 Tahun changed to:', this.value);
                // Auto refresh kedua chart
                if (typeof loadChartA === 'function') loadChartA();
                if (typeof loadChartB === 'function') loadChartB();
            });

            // Juga trigger pada input (untuk kasus manual typing)
            tahunFilter.addEventListener('input', function() {
                console.log('⌨️ Tahun input:', this.value);
                // Debounce untuk menghindari terlalu banyak request
                clearTimeout(window.tahunInputTimeout);
                window.tahunInputTimeout = setTimeout(() => {
                    if (typeof loadChartA === 'function') loadChartA();
                    if (typeof loadChartB === 'function') loadChartB();
                }, 800);
            });
        }

        // Load charts pertama kali
        if (typeof loadChartA === 'function') loadChartA();
        if (typeof loadChartB === 'function') loadChartB();

        console.log("✅ Chart Shift System Initialized Successfully");
    });
</script>