// ============================================================================
// 📈 LINE CHART BULANAN
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("myChart").getContext("2d");
  const bulan = document.getElementById("bulanUtama");
  const tahun = document.getElementById("tahunUtama");

  const btnPrev = document.getElementById("prevLine");
  const btnNext = document.getElementById("nextLine");
  // const btnExport = document.getElementById("exportPDF");

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
    const bulanVal = bulan.value;
    const tahunVal = tahun.value;

    fetch(`backend/chart-line.php?bulan=${bulanVal}&tahun=${tahunVal}`)
      .then((res) => res.json())
      .then((data) => {
        if (data && data.lines) renderChart(data.lines);
        else console.error("Data chart tidak sesuai format:", data);
      })
      .catch((err) => console.error("Gagal load chart:", err));
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
      dataLine.forEach((row) => {
        const hari = parseInt(row.hari);
        dataMap[hari] = parseFloat(row[key]) || 0;
      });
      return {
        label: `${lineName} - ${label}`,
        data: labels.map((hari) => dataMap[hari] || 0),
        borderColor: warna[i % warna.length],
        backgroundColor: warna[i % warna.length] + "33",
        fill: true,
        tension: 0.3,
        pointRadius: 3,
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
      type: "line",
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
          legend: {
            labels: { pointStyle: "rectRounded" },
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

  // FUNGSI REFRESHH LINE CHART
  // ========================================================================================================================
  setInterval(() => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    loadChart();
  }, 50000); //UBAH ANGKA NYAA
  // ========================================================================================================================

  // btnExport.addEventListener("click", () =>
  //   exportChartPDF("myChart", "Chart_Produksi_Bulanan")
  // );

  [bulan, tahun].forEach((el) => el.addEventListener("change", loadChart));
  loadChart();
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
  const tahunInput = document.getElementById("tahunInput");
  const btnPrev = document.getElementById("prevTahunan");
  const btnNext = document.getElementById("nextTahunan");
  let chart;
  let currentMetric = 0;

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
    fetch(`backend/get_chart_tahunan.php?tahun=${tahunInput.value}`)
      .then((res) => res.json())
      .then((data) => renderChart(data))
      .catch((err) => console.error("Gagal ambil data chart tahunan:", err));
  }

  function renderChart(data) {
    const metric = metrics[currentMetric];
    const tahun = data.tahun || tahunInput.value;
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

    // Dataset per line
    const datasets = Object.entries(data.lines || {}).map(
      ([lineName, dataLine], i) => {
        const dataMap = {};
        dataLine.forEach((row) => {
          const bulan = parseInt(row.bulan);
          const avgKey = `avg_${metric.key}`;
          dataMap[bulan] = parseFloat(row[avgKey]) || 0;
        });
        return {
          label: lineName,
          data: bulanLabels.map((_, idx) => dataMap[idx + 1] || 0),
          backgroundColor: warna[i % warna.length] + "99",
          borderColor: warna[i % warna.length],
          borderWidth: 1.5,
          borderRadius: 4,
        };
      }
    );

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: bulanLabels,
        datasets: datasets,
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: `📘 ${metric.label} per Line (${tahun})`,
            font: { size: 16 },
          },
          legend: {
            position: "bottom",
            labels: { usePointStyle: true },
          },
          datalabels: {
            color: "#000",
            anchor: "end",
            align: "top",
            font: { weight: "bold", size: 10 },
            formatter: (v) => (v !== 0 ? v.toFixed(1) : ""),
          },
        },
        scales: {
          y: { beginAtZero: true },
          x: { title: { display: true, text: "Bulan" } },
        },
      },
      plugins: [ChartDataLabels],
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

  setInterval(() => {
    currentMetric = (currentMetric + 1) % metrics.length;
    loadChartyears();
  }, 5000);

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
