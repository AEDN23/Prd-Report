// ============================================================================
// 📈 LINE CHART BULANAN
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
        `../backend/chart-line.php?bulan=${bulan}&tahun=${tahun}&line=${lineId}`
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

document.addEventListener("DOMContentLoaded", () => {
  const tahun = new Date().getFullYear();

  // 🎨 Warna berbeda untuk tiap line
  const warnaA = "#007bff"; // biru
  const warnaB = "#28a745"; // hijau

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

  let currentMetric = 0; // 🔁 sinkron antar line
  let chartA, chartB;

  // ========================================================================
  // 🔧 GENERIC RENDER CHART
  // ========================================================================
  function renderChart({ ctx, data, metric, warna, lineName }) {
    const dataBulan = data.bulanData || {};
    const targetData = data.target || {};

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

    // Hapus chart lama
    if (ctx.chartInstance) ctx.chartInstance.destroy();

    ctx.chartInstance = new Chart(ctx, {
      type: "bar",
      data: {
        labels: bulanLabels,
        datasets: [
          {
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
            text: `📘 ${lineName} — ${metric.label} vs Target (${tahun})`,
            font: { size: 16 },
          },
          legend: { position: "top" },
        },
        scales: {
          y: { beginAtZero: true },
          x: { title: { display: true, text: "Bulan" } },
        },
      },
    });
  }

  // ========================================================================
  // 🔄 LOAD CHART PER LINE
  // ========================================================================
  function loadChartA() {
    const ctx = document.getElementById("BarCharttahunan").getContext("2d");
    fetch(`../backend/get_chart_tahunan.php?line=1&tahun=${tahun}`)
      .then((res) => res.json())
      .then((data) =>
        renderChart({
          ctx,
          data,
          metric: metrics[currentMetric],
          warna: warnaA,
          lineName: "Line A",
        })
      )
      .catch((err) => console.error("❌ Gagal load chart Line A:", err));
  }

  function loadChartB() {
    const ctx = document
      .getElementById("BarCharttahunanLINEB")
      .getContext("2d");
    fetch(`../backend/get_chart_tahunan.php?line=2&tahun=${tahun}`)
      .then((res) => res.json())
      .then((data) =>
        renderChart({
          ctx,
          data,
          metric: metrics[currentMetric],
          warna: warnaB,
          lineName: "Line B",
        })
      )
      .catch((err) => console.error("❌ Gagal load chart Line B:", err));
  }

  // ========================================================================
  // 🔁 REFRESH SEMUA CHART
  // ========================================================================
  function refreshAllCharts() {
    loadChartA();
    loadChartB();
  }

  // ========================================================================
  // 🎛️ NEXT / PREV BUTTON
  // ========================================================================
  const btnNextA = document.getElementById("nextTahunan");
  const btnPrevA = document.getElementById("prevTahunan");
  const btnNextB = document.getElementById("nextTahunanB");
  const btnPrevB = document.getElementById("prevTahunanB");

  const nextHandler = () => {
    currentMetric = (currentMetric + 1) % metrics.length;
    refreshAllCharts();
  };

  const prevHandler = () => {
    currentMetric = (currentMetric - 1 + metrics.length) % metrics.length;
    refreshAllCharts();
  };

  [btnNextA, btnNextB].forEach((btn) =>
    btn.addEventListener("click", nextHandler)
  );
  [btnPrevA, btnPrevB].forEach((btn) =>
    btn.addEventListener("click", prevHandler)
  );

  // ========================================================================
  // ⏱️ AUTO REFRESH TIAP 5 DETIK
  // ========================================================================
  setInterval(() => {
    currentMetric = (currentMetric + 1) % metrics.length;
    refreshAllCharts();
  }, 5000);

  // ========================================================================
  // 🚀 LOAD PERTAMA
  // ========================================================================
  refreshAllCharts();
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
