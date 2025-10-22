// ============================================================================
// 📈 LINE CHART BULANAN
// ============================================================================
// ============================================================================
// 📊 BAR CHART BULANAN PER LINE (A & B TERPISAH)
// ============================================================================
// ============================================================================
// 📊 BAR CHART BULANAN PER LINE (A & B TERPISAH) DENGAN GARIS TARGET
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
  const bulan = new Date().getMonth() + 1;
  const tahun = new Date().getFullYear();

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
  // 🧠 FUNGSI BUAT CHART
  // ========================================================================
  function createBarChart({ canvasId, prevBtnId, nextBtnId, lineId }) {
    const ctx = document.getElementById(canvasId).getContext("2d");
    const btnPrev = document.getElementById(prevBtnId);
    const btnNext = document.getElementById(nextBtnId);
    let chartInstance;

    // 🔄 Render ulang chart
    function loadChart() {
      fetch(
        `backend/chart-line.php?bulan=${bulan}&tahun=${tahun}&line=${lineId}`
      )
        .then((res) => res.json())
        .then((data) => {
          if (data && data.lines && data.lines.length > 0) {
            renderChart(data.lines, data.target || {});
          } else {
            console.warn("Tidak ada data untuk line " + lineId);
          }
        })
        .catch((err) => console.error("Gagal load chart line " + lineId, err));
    }

    // 🎨 Render chart dengan garis target
    function renderChart(rows, targetData) {
      const key = datasetKeys[currentDataset];
      const label = datasetLabels[currentDataset];
      const labels = Array.from({ length: 31 }, (_, i) => i + 1);

      const dataMap = {};
      rows.forEach((r) => {
        dataMap[parseInt(r.hari)] = parseFloat(r[key]) || 0;
      });

      const values = labels.map((hari) => dataMap[hari] || 0);
      const targetKey = `target_${key}`;
      const targetVal =
        targetData && targetData[targetKey] !== undefined
          ? parseFloat(targetData[targetKey])
          : 0;

      const datasetBars = {
        label: `${label}`,
        data: values,
        backgroundColor: warna[lineId % warna.length] + "88",
        borderColor: warna[lineId % warna.length],
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
              text: `📊 ${label} - Line ${
                lineId === 1 ? "A" : "B"
              } (${bulan}/${tahun})`,
              font: { size: 20 },
            },
            legend: {
              position: "top",
              labels: { boxWidth: 40 },
            },
          },
          scales: {
            y: { beginAtZero: true },
            x: { title: { display: true, text: "Hari (1–31)" } },
          },
        },
      });
    }

    // Tombol manual
    btnNext.addEventListener("click", () => {
      currentDataset = (currentDataset + 1) % datasetKeys.length;
      refreshAllCharts();
    });

    btnPrev.addEventListener("click", () => {
      currentDataset =
        (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
      refreshAllCharts();
    });

    // Load awal
    loadChart();

    return { loadChart };
  }

  // ========================================================================
  // 🅰️ & 🅱️ BUAT DUA CHART SEKALIGUS
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

  // ========================================================================
  // 🔄 AUTO REFRESH SINKRON SETIAP 5 DETIK
  // ========================================================================
  function refreshAllCharts() {
    chartA.loadChart();
    chartB.loadChart();
  }

  setInterval(() => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    refreshAllCharts();
  }, 5000); // GANTI DATASET SETIAP 5 DETIK
});

// ============================================================================
// 📅 BAR CHART TAHUNAN PER LINE
// ============================================================================
// ============================================================================
// 📅 BAR CHART TAHUNAN LINE A & LINE B (TERPISAH, OTOMATIS TAHUN SEKARANG)
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
  const tahunSekarang = new Date().getFullYear();

  // Tombol Navigasi (gunakan ID unik untuk tiap chart)
  const btnPrevA = document.getElementById("prevTahunan");
  const btnNextA = document.getElementById("nextTahunan");
  const btnPrevB = document.getElementById("prevTahunanB");
  const btnNextB = document.getElementById("nextTahunanB");

  // Context Canvas
  const ctxA = document.getElementById("BarCharttahunan").getContext("2d");
  const ctxB = document.getElementById("BarCharttahunanLINEB").getContext("2d");

  // Metric yang ditampilkan
  const metrics = [
    { key: "productivity", label: "Productivity (Ton/Shift)" },
    { key: "batch_count", label: "Batch Count (Per Day)" },
    { key: "production_speed", label: "Production Speed (Kg/Min)" },
    { key: "feed_raw_material", label: "Feed Raw Material (Kg/Day)" },
    { key: "operation_factor", label: "Operation Factor (%)" },
  ];

  const bulanLabels = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  let currentMetricA = 0;
  let currentMetricB = 0;
  let chartA, chartB;

  // ==========================================================
  // 🔧 Fungsi untuk Render Chart
  // ==========================================================
  function renderChart(ctx, data, metric, lineName, chartRef) {
    const dataBulan = data.bulanData || {};
    const targetData = data.target || {};
    const tahun = data.tahun || tahunSekarang;

    const produksiData = bulanLabels.map((_, i) => {
      const bulan = i + 1;
      const avgKey = `avg_${metric.key}`;
      return dataBulan[bulan] && dataBulan[bulan][avgKey] !== undefined
        ? parseFloat(dataBulan[bulan][avgKey])
        : 0;
    });

    const targetKey = `target_${metric.key}`;
    const targetValue =
      targetData && targetData[targetKey] !== undefined
        ? parseFloat(targetData[targetKey])
        : 0;

    if (chartRef.chart) chartRef.chart.destroy();

    chartRef.chart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: bulanLabels,
        datasets: [
          {
            label: `Rata-rata ${metric.label}`,
            data: produksiData,
            backgroundColor: "rgba(0,123,255,0.6)",
            borderColor: "#007bff",
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
            text: `📘 ${lineName} — ${metric.label} vs Target (${tahun})`,
            font: { size: 16 },
          },
        },
        scales: {
          y: { beginAtZero: true },
          x: { title: { display: true, text: "Bulan" } },
        },
      },
    });
  }

  // ==========================================================
  // 📊 Fungsi untuk Load Data Line A dan B
  // ==========================================================
  function loadChartA() {
    fetch(`backend/get_chart_tahunan.php?line=1&tahun=${tahunSekarang}`)
      .then((res) => res.json())
      .then((data) =>
        renderChart(ctxA, data, metrics[currentMetricA], "Line A", {
          chart: chartA,
        })
      )
      .catch((err) => console.error("Gagal load chart Line A:", err));
  }

  function loadChartB() {
    fetch(`backend/get_chart_tahunan.php?line=2&tahun=${tahunSekarang}`)
      .then((res) => res.json())
      .then((data) =>
        renderChart(ctxB, data, metrics[currentMetricB], "Line B", {
          chart: chartB,
        })
      )
      .catch((err) => console.error("Gagal load chart Line B:", err));
  }

  // ==========================================================
  // 🎛️ Navigasi Metric (Next/Prev)
  // ==========================================================
  btnNextA.addEventListener("click", () => {
    currentMetricA = (currentMetricA + 1) % metrics.length;
    loadChartA();
  });

  btnPrevA.addEventListener("click", () => {
    currentMetricA = (currentMetricA - 1 + metrics.length) % metrics.length;
    loadChartA();
  });

  btnNextB.addEventListener("click", () => {
    currentMetricB = (currentMetricB + 1) % metrics.length;
    loadChartB();
  });

  btnPrevB.addEventListener("click", () => {
    currentMetricB = (currentMetricB - 1 + metrics.length) % metrics.length;
    loadChartB();
  });

  // ==========================================================
  // 🔁 Auto Refresh tiap 30 detik
  // ==========================================================
  setInterval(() => {
    loadChartA();
    loadChartB();
  }, 30000);

  // ==========================================================
  // 🚀 Load pertama kali
  // ==========================================================
  loadChartA();
  loadChartB();
});

// ============================================================================
// 🧾 Fungsi Export PDF (dipakai semua chart)
// ============================================================================
// function exportChartPDF(canvasId, title) {
//   const { jsPDF } = window.jspdf;
//   const pdf = new jsPDF();
//   const canvas = document.getElementById(canvasId);
//   const imgData = canvas.toDataURL("image/png");
//   pdf.text(title, 15, 15);
//   pdf.addImage(imgData, "PNG", 10, 25, 190, 100);
//   pdf.save(`${title}.pdf`);
// }
