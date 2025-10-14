// =============================
// 📌 SCRIPT UNTUK INPUT HARIAN
// =============================
document.addEventListener("DOMContentLoaded", () => {
  const lineUtama = document.getElementById("lineUtama");
  const bulanUtama = document.getElementById("bulanUtama");
  const tahunUtama = document.getElementById("tahunUtama");
  const tabelUtama = document.querySelector(".table-container");

  function updateTabelUtama() {
    const line = lineUtama.value;
    const bulan = bulanUtama.value;
    const tahun = tahunUtama.value;

    tabelUtama.innerHTML =
      '<div class="text-center py-3 text-muted">Loading data...</div>';

    fetch(
      `backend/inputharianajax.php?line=${line}&bulan=${bulan}&tahun=${tahun}`
    )
      .then((res) => res.text())
      .then((html) => (tabelUtama.innerHTML = html))
      .catch((err) => {
        tabelUtama.innerHTML =
          '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
        console.error(err);
      });
  }

  [lineUtama, bulanUtama, tahunUtama].forEach((el) =>
    el.addEventListener("change", updateTabelUtama)
  );

  updateTabelUtama(); // load awal
});

// ==================================
// 📘 SCRIPT UNTUK RANGKUMAN TAHUNAN
// ==================================
document.addEventListener("DOMContentLoaded", () => {
  const lineSelect = document.getElementById("lineSelect");
  const tahunInput = document.getElementById("tahunInput");
  const tabelContainer = document.getElementById("tabelContainer");
  const btnPDF = document.getElementById("btnPDF");
  const btnExcel = document.getElementById("btnExcel");

  function updateTabel() {
    const line = lineSelect.value;
    const tahun = tahunInput.value;

    btnPDF.href = `export/exportpdf.php?line=${line}&tahun=${tahun}`;
    btnExcel.href = `export/export_excel.php?line=${line}&tahun=${tahun}`;

    tabelContainer.innerHTML =
      '<div class="text-center py-3 text-muted">Loading data...</div>';

    fetch(`backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
      .then((res) => res.text())
      .then((html) => (tabelContainer.innerHTML = html))
      .catch((err) => {
        tabelContainer.innerHTML =
          '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
        console.error(err);
      });
  }

  lineSelect.addEventListener("change", updateTabel);
  tahunInput.addEventListener("change", updateTabel);

  updateTabel(); // load awal
});

// ===============================
// 📊 SCRIPT UNTUK DASHBOARD CHART
// ===============================
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
            position: "bottom",
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
  // setInterval(() => {
  //   currentDataset = (currentDataset + 1) % datasetKeys.length;
  //   loadChart();
  // }, 10000);

  // Load pertama kali
  loadChart();
});

// ==============================
// 📊 SCRIPT UNTUK EXPOR PDF
// ==============================
document.addEventListener("DOMContentLoaded", () => {
  const exportBtn = document.querySelector("#exportPDF");
  const chartCanvas = document.getElementById("myChart");

  exportBtn.addEventListener("click", async () => {
    try {
      // Ambil image dari chart (canvas)
      const canvasImage = chartCanvas.toDataURL("image/png", 1.0);

      // Buat dokumen PDF baru
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF({
        orientation: "landscape", // biar lebar muat chart
        unit: "px",
        format: [800, 500],
      });

      // Tambahkan judul di atas chart
      pdf.setFontSize(14);
      pdf.text("📊 Laporan Chart Produksi", 20, 30);

      // Masukkan gambar chart ke PDF
      pdf.addImage(canvasImage, "PNG", 20, 50, 760, 400);

      // Simpan file PDF
      pdf.save("chart-produksi.pdf");

      console.log("PDF berhasil dibuat!");
    } catch (error) {
      console.error("Gagal export PDF:", error);
      alert("Gagal membuat PDF, coba lagi!");
    }
  });
});
