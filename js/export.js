// ==============================
// 📊 SCRIPT UNTUK EXPOR PDF
// ==============================
document.addEventListener("DOMContentLoaded", () => {
  const exportBtn = document.getElementById("exportPDF"); 
  const chartCanvas = document.getElementById("myChart");

  exportBtn.addEventListener("click", async () => {
    try {
      const canvasImage = chartCanvas.toDataURL("image/png", 2.0);
      const { jsPDF } = window.jspdf;

      // FUNGSI untuk mendapatkan ukuran canvas (biar rasio sama kayak di layar)
      const canvasWidth = chartCanvas.width;
      const canvasHeight = chartCanvas.height;

      // Fungsi mengubah ke skala pixel PDF (agar proporsional)
      const pdfWidth = 1000;
      const ratio = pdfWidth / canvasWidth;
      const pdfHeight = canvasHeight * ratio + 100; // +100 untuk ruang judul

      // Buat dokumen PDF dengan ukuran dinamis
      const pdf = new jsPDF({
        orientation: "landscape",
        unit: "px",
        format: [pdfWidth, pdfHeight],
      });

      // Judul di atas chart
      pdf.setFontSize(18);
      const bulanVal = document.getElementById("bulanUtama").value;
      const tahunVal = document.getElementById("tahunUtama").value;
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
      const bulanNama = namaBulan[parseInt(bulanVal, 10) - 1] || bulanVal;

      pdf.text(
        `Laporan Chart Produksi Bulan ${bulanNama} - ${tahunVal}`,
        30,
        40
      );

      // Tambahkan gambar chart
      pdf.addImage(canvasImage, "PNG", 20, 60, pdfWidth - 40, pdfHeight - 100);

      // fungsi Simpan PDF
      pdf.save("chart-produksi.pdf");
    } catch (err) {
      console.error("Gagal export PDF:", err);
      alert("Gagal membuat PDF!");
    }
  });
});

// ==============================
// 📊 SCRIPT UNTUK EXPOR PDF BARCHART
// ==============================
document.addEventListener("DOMContentLoaded", () => {
  const exportBtn = document.getElementById("exportPDFbarchart");
  const chartCanvas = document.getElementById("BarChart");

  exportBtn.addEventListener("click", async () => {
    try {
      const canvasImage = chartCanvas.toDataURL("image/png", 2.0);
      const { jsPDF } = window.jspdf;

      const canvasWidth = chartCanvas.width;
      const canvasHeight = chartCanvas.height;

      const pdfWidth = 1000;
      const ratio = pdfWidth / canvasWidth;
      const pdfHeight = canvasHeight * ratio + 100;

      const pdf = new jsPDF({
        orientation: "landscape",
        unit: "px",
        format: [pdfWidth, pdfHeight],
      });

      pdf.setFontSize(18);
      const bulanVal = document.getElementById("bulanUtama").value;
      const tahunVal = document.getElementById("tahunUtama").value;
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
      const bulanNama = namaBulan[parseInt(bulanVal, 10) - 1] || bulanVal;

      pdf.text(
        `Laporan Bar Chart Produksi Bulan ${bulanNama} - ${tahunVal}`,
        30,
        40
      );

      pdf.addImage(canvasImage, "PNG", 20, 60, pdfWidth - 40, pdfHeight - 100);

      pdf.save(`barchart-produksi bulan ${bulanNama} - ${tahunVal}.pdf`);
    } catch (err) {
      console.error("Gagal export PDF:", err);
      alert("Gagal membuat PDF!");
    }
  });
});
