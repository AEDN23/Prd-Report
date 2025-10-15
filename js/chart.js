// =========================================================================================================================================================
// 📊 SCRIPT UNTUK LINE CHART BULANAN
// =========================================================================================================================================================
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("myChart").getContext("2d");
  const bulan = document.getElementById("bulanUtama");
  const tahun = document.getElementById("tahunUtama");
  const chartLegend = document.getElementById("chartLegend");

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

  // Tombol navigasi antar dataset
  const btnPrev = document.createElement("button");
  const btnNext = document.createElement("button");
  btnPrev.textContent = "◀ Prev";
  btnNext.textContent = "Next ▶";
  btnPrev.className = "btn btn-sm btn-secondary me-2";
  btnNext.className = "btn btn-sm btn-primary ms-2";
  chartLegend.append(btnPrev, btnNext);

  // Ambil data dari backend
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

  // Render chart dinamis
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

    // Konversi angka bulan ke nama bulan Indonesia
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
    const bulanIndex = parseInt(bulan.value, 10) - 1;
    const bulanNama = namaBulan[bulanIndex] || bulan.value;

    chartInstance = new Chart(ctx, {
      type: "line",
      data: { labels, datasets },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: "index" },
        plugins: {
          title: {
            display: true,
            text: `📊 ${label} Antar Line Produksi Bulan ${bulanNama} - ${tahun.value}`,
            font: { size: 16 },
          },
          legend: {
            position: "top",
            labels: {
              usePointStyle: true,
              pointStyle: "rectRounded",
            },
            onClick: (e, legendItem, legend) => {
              const index = legendItem.datasetIndex;
              const ci = legend.chart;
              const meta = ci.getDatasetMeta(index);
              meta.hidden =
                meta.hidden === null ? !ci.data.datasets[index].hidden : null;
              ci.update();
            },
          },
        },
        scales: {
          y: { beginAtZero: true },
          x: {
            title: { display: true, text: "Hari (1–31)" },
            ticks: { stepSize: 1 },
          },
        },
      },
    });
  }

  // Tombol navigasi antar dataset
  btnNext.addEventListener("click", () => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    loadChart();
  });
  btnPrev.addEventListener("click", () => {
    currentDataset =
      (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
    loadChart();
  });

  // Filter (bulan, tahun)
  [bulan, tahun].forEach((el) => el.addEventListener("change", loadChart));

  // Auto refresh setiap 10 detik (optional)
  setInterval(() => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    loadChart();
  }, 20000);

  loadChart(); // load awal
});

//=========================================================================================================================================================
// 📊 SCRIPT UNTUK BAR CHART BULANAN
// =========================================================================================================================================================
document.addEventListener("DOMContentLoaded", () => {
  const ctxBar = document.getElementById("BarChart").getContext("2d");
  const bulan = document.getElementById("bulanUtama");
  const tahun = document.getElementById("tahunUtama");
  const barChartLegend = document.getElementById("barchart");

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
  let barChartInstance;

  // Tombol navigasi antar dataset
  const btnPrevBar = document.createElement("button");
  const btnNextBar = document.createElement("button");
  btnPrevBar.textContent = "◀ Prev";
  btnNextBar.textContent = "Next ▶";
  btnPrevBar.className = "btn btn-sm btn-secondary me-2";
  btnNextBar.className = "btn btn-sm btn-primary ms-2";
  barChartLegend.append(btnPrevBar, btnNextBar);

  // Ambil data dari backend
  function loadBarChart() {
    const bulanVal = bulan.value;
    const tahunVal = tahun.value;

    fetch(`backend/chart-line.php?bulan=${bulanVal}&tahun=${tahunVal}`)
      .then((res) => res.json())
      .then((data) => {
        if (data && data.lines) renderBarChart(data.lines);
        else console.error("Data bar chart tidak sesuai format:", data);
      })
      .catch((err) => console.error("Gagal load bar chart:", err));
  }

  // Render chart
  function renderBarChart(lines) {
    const key = datasetKeys[currentDataset];
    const label = datasetLabels[currentDataset];
    const labels = Array.from({ length: 31 }, (_, i) => i + 1);

    // Warna bar-nya beda untuk setiap line
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
        backgroundColor: warna[i % warna.length] + "88",
        borderColor: warna[i % warna.length],
        borderWidth: 1.5,
      };
    });

    if (barChartInstance) barChartInstance.destroy();

    // Nama bulan Indonesia
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
    const bulanIndex = parseInt(bulan.value, 10) - 1;
    const bulanNama = namaBulan[bulanIndex] || bulan.value;

    barChartInstance = new Chart(ctxBar, {
      type: "bar",
      data: { labels, datasets },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: "index" },
        plugins: {
          title: {
            display: true,
            text: `📊 ${label} Antar Line Produksi Bulan ${bulanNama} - ${tahun.value}`,
            font: { size: 16 },
          },
          legend: {
            position: "top",
            labels: {
              usePointStyle: true,
              pointStyle: "rectRounded",
            },
            onClick: (e, legendItem, legend) => {
              const index = legendItem.datasetIndex;
              const ci = legend.chart;
              const meta = ci.getDatasetMeta(index);
              meta.hidden =
                meta.hidden === null ? !ci.data.datasets[index].hidden : null;
              ci.update();
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: "Nilai Parameter" },
          },
          x: {
            title: { display: true, text: "Hari (1–31)" },
            ticks: { stepSize: 1 },
            stacked: false,
          },
        },
        elements: {
          bar: { borderRadius: 5 },
        },
      },
    });
  }

  // Tombol navigasi antar dataset
  btnNextBar.addEventListener("click", () => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    loadBarChart();
  });
  btnPrevBar.addEventListener("click", () => {
    currentDataset =
      (currentDataset - 1 + datasetKeys.length) % datasetKeys.length;
    loadBarChart();
  });

  // Filter bulan/tahun
  [bulan, tahun].forEach((el) => el.addEventListener("change", loadBarChart));

  // Auto refresh setiap 10 detik untuk barchart
  setInterval(() => {
    currentDataset = (currentDataset + 1) % datasetKeys.length;
    loadBarChart();
  }, 20000);

  loadBarChart(); // Load awal
});

// =========================================================================================================================================================
// 📊 CRIPT UNTUK BAR CHART TAHUNAN
// =========================================================================================================================================================
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("BarCharttahunan").getContext("2d");
  const lineSelect = document.getElementById("lineSelect");
  const tahunInput = document.getElementById("tahunInput");
  const legendContainer = document.getElementById("barcharttahunan");

  let chart;
  let currentMetric = 0;

  // Daftar metrik (kolom di DB) dan labelnya
  const metrics = [
    { key: "productivity", label: "Productivity (Ton/Shift)" },
    { key: "batch_count", label: "Batch Count (Per Day)" },
    { key: "production_speed", label: "Production Speed (Kg/Min)" },
    { key: "feed_raw_material", label: "Feed Raw Material (Kg/Day)" },
    { key: "operation_factor", label: "Operation Factor (%)" },
  ];

  // Buat tombol navigasi
  const btnPrev = document.createElement("button");
  const btnNext = document.createElement("button");
  btnPrev.textContent = "◀ Prev";
  btnNext.textContent = "Next ▶";
  btnPrev.className = "btn btn-sm btn-secondary me-2";
  btnNext.className = "btn btn-sm btn-primary ms-2";
  legendContainer.append(btnPrev, btnNext);

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

  // Ambil data dari backend
  function loadChartyears() {
    const lineId = lineSelect.value || 1;
    const tahun = tahunInput.value || new Date().getFullYear();
    fetch(
      `backend/get_chart_tahunan.php?line=${encodeURIComponent(
        lineId
      )}&tahun=${encodeURIComponent(tahun)}`
    )
      .then((res) => res.json())
      .then((data) => renderChart(data))
      .catch((err) => {
        console.error("Gagal ambil data chart tahunan:", err);
        // jika error, tetap hapus chart lama agar UI konsisten
        if (chart) {
          chart.destroy();
          chart = null;
        }
      });
  }

  // Render chart dari data (menggunakan currentMetric)
  function renderChart(data) {
    const metricKey = metrics[currentMetric].key;
    const metricLabel = metrics[currentMetric].label;
    const dataBulan = data.bulanData || {};
    const targetData = data.target || {};
    const tahun = data.tahun || tahunInput.value;

    // buat array produksi berdasarkan avg_<metricKey>
    const produksiData = bulanLabels.map((_, i) => {
      const bulan = i + 1;
      const avgKey = `avg_${metricKey}`;
      return dataBulan[bulan] && dataBulan[bulan][avgKey] !== undefined
        ? parseFloat(dataBulan[bulan][avgKey])
        : 0;
    });

    // ambil target dari kolom target_<metricKey> di tabel target
    const targetKey = `target_${metricKey}`;
    const targetValue =
      targetData &&
      targetData[targetKey] !== null &&
      targetData[targetKey] !== undefined
        ? parseFloat(targetData[targetKey])
        : 0;

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: bulanLabels,
        datasets: [
          {
            label: `Rata-rata ${metricLabel}`,
            data: produksiData,
            backgroundColor: "rgba(0, 123, 255, 0.6)",
            borderColor: "#007bff",
            borderWidth: 1.5,
            borderRadius: 4,
          },
          {
            label: `🎯 Target ${metricLabel}`,
            data: Array(12).fill(targetValue),
            type: "line",
            borderColor: "#ff0000",
            borderWidth: 2,
            borderDash: [6, 4],
            pointRadius: 0,
            fill: false,
            yAxisID: "y",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: `📘 Grafik ${metricLabel} vs Target (${tahun})`,
            font: { size: 16 },
          },
          legend: {
            position: "top",
            labels: {
              usePointStyle: true,
              pointStyle: "rectRounded",
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: metricLabel },
          },
          x: {
            title: { display: true, text: "Bulan" },
          },
        },
      },
    });
  }

  // Tombol navigasi
  btnNext.addEventListener("click", () => {
    currentMetric = (currentMetric + 1) % metrics.length;
    loadChartyears();
  });
  btnPrev.addEventListener("click", () => {
    currentMetric = (currentMetric - 1 + metrics.length) % metrics.length;
    loadChartyears();
  });

  // event filter
  [lineSelect, tahunInput].forEach((el) =>
    el.addEventListener("change", loadChartyears)
  );

  // Auto refresh setiap 10 detik untuk barchart tahunan
  setInterval(() => {
    currentMetric = (currentMetric + 1) % metrics.length;
    loadChartyears();
  }, 200000);

  loadChartyears(); // Load awal
});
