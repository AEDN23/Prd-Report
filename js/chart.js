// ============================================================================
// 📈 LINE CHART BULANAN
// ============================================================================
// ============================================================================
// 📊 BAR CHART BULANAN PER LINE (A & B TERPISAH)
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
  const bulan = new Date().getMonth() + 1;
  const tahun = new Date().getFullYear();

  // Konfigurasi umum
  const warna = [
    "#1B1E23",
    "#FF90BB",
    "#FF0000 ",
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

  // ========================================================================
  // 🅰️ CHART LINE A
  // ========================================================================
  createBarChart({
    canvasId: "BarChartA",
    prevBtnId: "prevBarA",
    nextBtnId: "nextBarA",
    lineId: 1, // Line A
  });

  // ========================================================================
  // 🅱️ CHART LINE B
  // ========================================================================
  createBarChart({
    canvasId: "BarChartB",
    prevBtnId: "prevBarB",
    nextBtnId: "nextBarB",
    lineId: 2, // Line B
  });

  // ========================================================================
  // 🔧 FUNGSI GENERIK UNTUK BUAT CHART
  // ========================================================================
  function createBarChart({ canvasId, prevBtnId, nextBtnId, lineId }) {
    const ctx = document.getElementById(canvasId).getContext("2d");
    const btnPrev = document.getElementById(prevBtnId);
    const btnNext = document.getElementById(nextBtnId);

    let currentDataset = 0;
    let chartInstance;

    function loadChart() {
      fetch(
        `backend/chart-line.php?bulan=${bulan}&tahun=${tahun}&line=${lineId}`
      )
        .then((res) => res.json())
        .then((data) => {
          if (data && data.lines && data.lines.length > 0) {
            renderChart(data.lines);
          } else {
            console.warn("Tidak ada data untuk line " + lineId);
          }
        })
        .catch((err) => console.error("Gagal load chart line " + lineId, err));
    }

    function renderChart(rows) {
      const key = datasetKeys[currentDataset];
      const label = datasetLabels[currentDataset];
      const labels = Array.from({ length: 31 }, (_, i) => i + 1);

      const dataMap = {};
      rows.forEach((r) => {
        dataMap[parseInt(r.hari)] = parseFloat(r[key]) || 0;
      });

      const dataset = {
        label: `${label}`,
        data: labels.map((hari) => dataMap[hari] || 0),
        backgroundColor: warna[lineId % warna.length] + "88",
        borderColor: warna[lineId % warna.length],
        borderWidth: 1.5,
      };

      if (chartInstance) chartInstance.destroy();

      chartInstance = new Chart(ctx, {
        type: "bar",
        data: { labels, datasets: [dataset] },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: `📊 ${label} - Line ${
                lineId === 1 ? "A" : "B"
              } (${bulan}/${tahun})`,
              font: { size: 15 },
            },
          },
          scales: {
            y: { beginAtZero: true },
            x: { title: { display: true, text: "Hari (1–31)" } },
          },
        },
      });
    }

    btnNext.addEventListener("click", () => {
      currentDataset = (currentDataset + 1) % datasetKeys.length;
      loadChart();
    });
    btnPrev.addEventListener("click", () => {
      currentDataset =
        (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
      loadChart();
    });

    setInterval(() => {
      currentDataset = (currentDataset + 1) % datasetKeys.length;
      loadChart();
    }, 50000); //UBAH ANGKA NYAA

    loadChart();
  }
});

// ============================================================================
// 📊 BAR CHART BULANAN
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("BarChart").getContext("2d");
  const bulan = document.getElementById("bulanUtama");
  const tahun = document.getElementById("tahunUtama");

  const btnPrev = document.getElementById("prevBar");
  const btnNext = document.getElementById("nextBar");
  // const btnExport = document.getElementById("exportPDFbarchart");

  let currentDataset = 0;
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
  let chartInstance;

  function loadChart() {
    fetch(`backend/chart-line.php?bulan=${bulan.value}&tahun=${tahun.value}`)
      .then((res) => res.json())
      .then((data) => renderChart(data.lines))
      .catch((err) => console.error("Gagal load bar chart:", err));
  }

  function renderChart(lines) {
    const key = datasetKeys[currentDataset];
    const label = datasetLabels[currentDataset];
    const labels = Array.from({ length: 31 }, (_, i) => i + 1);
    const warna = [
      "#0046FF",
      "#F9E400",
      "#FF90BB",
      "#450693",
      "#6f42c1",
      "#E9FF97",
      "#fd7e14",
      "#6610f2",
      "#17a2b8",
      "#adb5bd",
    ];

    const datasets = Object.entries(lines).map(([lineName, dataLine], i) => {
      const dataMap = {};
      dataLine.forEach(
        (row) => (dataMap[parseInt(row.hari)] = parseFloat(row[key]) || 0)
      );
      return {
        label: `${lineName} - ${label}`,
        data: labels.map((hari) => dataMap[hari] || 0),
        backgroundColor: warna[i % warna.length] + "88",
        borderColor: warna[i % warna.length],
        borderWidth: 1.5,
      };
    });

    if (chartInstance) chartInstance.destroy();

    const namaBulan = [
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
    const bulanNama = namaBulan[parseInt(bulan.value, 10) - 1] || bulan.value;

    chartInstance = new Chart(ctx, {
      type: "bar",
      data: { labels, datasets },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: `📊 ${label} Antar Line Produksi Bulan ${bulanNama} - ${tahun.value}`,
            font: { size: 16 },
          },
        },
        scales: {
          y: { beginAtZero: true },
          x: { title: { display: true, text: "Hari (1–31)" } },
        },
      },
    });
  }

  btnNext.addEventListener("click", () => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    loadChart();
  });
  btnPrev.addEventListener("click", () => {
    currentDataset =
      (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
    loadChart();
  });

  setInterval(() => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    loadChart();
  }, 50000);

  // btnExport.addEventListener("click", () =>
  //   exportChartPDF("BarChart", "Chart_Bar_Bulanan")
  // );

  [bulan, tahun].forEach((el) => el.addEventListener("change", loadChart));
  loadChart();
});

// ============================================================================
// 📅 BAR CHART TAHUNAN PER LINE
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("BarCharttahunan").getContext("2d");
  const lineSelect = document.getElementById("lineSelect");
  const tahunInput = document.getElementById("tahunInput");

  const btnPrev = document.getElementById("prevTahunan");
  const btnNext = document.getElementById("nextTahunan");
  // const btnExport = document.getElementById("exportPDFbarcharttahunan");

  let currentMetric = 0;
  let chart;

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

  function loadChartyears() {
    fetch(
      `backend/get_chart_tahunan.php?line=${lineSelect.value}&tahun=${tahunInput.value}`
    )
      .then((res) => res.json())
      .then((data) => renderChart(data))
      .catch((err) => console.error("Gagal ambil data chart tahunan:", err));
  }

  function renderChart(data) {
    const metric = metrics[currentMetric];
    const dataBulan = data.bulanData || {};
    const targetData = data.target || {};
    const tahun = data.tahun || tahunInput.value;

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

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
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
            text: `📘 Grafik ${metric.label} vs Target (${tahun})`,
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

  btnNext.addEventListener("click", () => {
    currentMetric = (currentMetric + 1) % metrics.length;
    loadChartyears();
  });
  btnPrev.addEventListener("click", () => {
    currentMetric = (currentMetric - 1 + metrics.length) % metrics.length;
    loadChartyears();
  });
  // btnExport.addEventListener("click", () =>
  //   exportChartPDF("BarCharttahunan", "Chart_Tahunan")
  // );

  [lineSelect, tahunInput].forEach((el) =>
    el.addEventListener("change", loadChartyears)
  );
  loadChartyears();
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
