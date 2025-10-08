document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("chartProduksi").getContext("2d");
  const chartTitle = document.getElementById("chartTitle");
  const nextBtn = document.getElementById("nextChart");
  const prevBtn = document.getElementById("prevChart");
  const lineSelect = document.getElementById("chartLine");
  const monthSelect = document.getElementById("chartMonth");
  const yearInput = document.getElementById("chartYear");

  let datasets = [];
  let currentIndex = 0;
  let chart;
  let autoChange;

  // Fungsi ambil data dari backend
  async function fetchData() {
    const line = lineSelect.value;
    const bulan = monthSelect.value;
    const tahun = yearInput.value;

    const res = await fetch(
      `backend/chart_data.php?line=${line}&bulan=${bulan}&tahun=${tahun}`
    );
    const data = await res.json();

    const labels = data.map((d) => d.hari.toString());

    datasets = [
      {
        key: "batch_count",
        title: "Batch Count",
        color: "#007bff",
        data: data.map((d) => +d.batch_count),
      },
      {
        key: "productivity",
        title: "Productivity",
        color: "#28a745",
        data: data.map((d) => +d.productivity),
      },
      {
        key: "production_speed",
        title: "Production Speed",
        color: "#ffc107",
        data: data.map((d) => +d.production_speed),
      },
      {
        key: "batch_weight",
        title: "Batch Weight",
        color: "#17a2b8",
        data: data.map((d) => +d.batch_weight),
      },
      {
        key: "operation_factor",
        title: "Operation Factor",
        color: "#6f42c1",
        data: data.map((d) => +d.operation_factor),
      },
      {
        key: "cycle_time",
        title: "Cycle Time",
        color: "#fd7e14",
        data: data.map((d) => +d.cycle_time),
      },
      {
        key: "grade_change_sequence",
        title: "Grade Change Sequence",
        color: "#20c997",
        data: data.map((d) => +d.grade_change_sequence),
      },
      {
        key: "grade_change_time",
        title: "Grade Change Time",
        color: "#dc3545",
        data: data.map((d) => +d.grade_change_time),
      },
      {
        key: "feed_raw_material",
        title: "Feed Raw Material",
        color: "#343a40",
        data: data.map((d) => +d.feed_raw_material),
      },
    ];

    // Hapus chart lama
    if (chart) chart.destroy();

    const activeData = datasets[currentIndex];
    const maxValue = Math.max(...activeData.data);
    const minValue = Math.min(...activeData.data);
    const step = Math.max(1, Math.ceil((maxValue - minValue) / 5));

    chart = new Chart(ctx, {
      type: "line",
      data: {
        labels,
        datasets: [
          {
            label: activeData.title,
            data: activeData.data,
            borderColor: activeData.color,
            backgroundColor: activeData.color + "33",
            tension: 0.3,
            fill: true,
            pointRadius: 3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: step, // jarak antar angka Y otomatis
              font: { size: 11 },
            },
            min: 0,
            max: maxValue + step * 1.5, // jarak kecil biar gak mentok atas
            grid: {
              color: "#eee",
            },
          },
          x: {
            ticks: {
              autoSkip: true,
              maxTicksLimit: 15,
              font: { size: 10 },
            },
            grid: {
              display: false,
            },
          },
        },
        animation: {
          duration: 500,
          easing: "easeOutCubic",
        },
      },
    });

    chartTitle.textContent = activeData.title;
  }

  // Fungsi update chart (saat next / prev)
  function updateChart() {
    const d = datasets[currentIndex];
    const maxValue = Math.max(...d.data);
    const minValue = Math.min(...d.data);
    const step = Math.max(1, Math.ceil((maxValue - minValue) / 5));

    chart.data.datasets[0].data = d.data;
    chart.data.datasets[0].label = d.title;
    chart.data.datasets[0].borderColor = d.color;
    chart.data.datasets[0].backgroundColor = d.color + "33";
    chart.options.scales.y.max = maxValue + step * 1.5;
    chart.options.scales.y.ticks.stepSize = step;
    chartTitle.textContent = d.title;
    chart.update();
  }

  // Tombol Next & Prev
  nextBtn.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % datasets.length;
    updateChart();
  });

  prevBtn.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + datasets.length) % datasets.length;
    updateChart();
  });

  // Auto ganti tiap 10 detik
  function startAutoChange() {
    clearInterval(autoChange);
    autoChange = setInterval(() => {
      currentIndex = (currentIndex + 1) % datasets.length;
      updateChart();
    }, 10000);
  }

  // Ganti data saat filter berubah
  [lineSelect, monthSelect, yearInput].forEach((el) => {
    el.addEventListener("change", async () => {
      currentIndex = 0;
      await fetchData();
      startAutoChange();
    });
  });

  // Load awal
  fetchData().then(startAutoChange);
});
