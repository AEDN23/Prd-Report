document.addEventListener("DOMContentLoaded", () => {
  // Ambil semua section utama
  const mainSections = [
    document.getElementById("chart-bar-bulanan-LINEA"),
    document.getElementById("chart-bar-bulanan-LINEB"),
    document.getElementById("chart-tahunan-LINEA"),
    document.getElementById("chart-tahunan-LINEB"),
    document.getElementById("DATA-HARIAN-LINEA"),
    document.getElementById("tabel-tahunan-LINEA"),
  ].filter(Boolean);

  const infoTitles = Array.from(document.querySelectorAll("#informasi h1"));

  const scrollTargets = [...mainSections, ...infoTitles];

  if (scrollTargets.length === 0) return; // kalau kosong, jangan lanjut

  let currentIndex = 0;

  // ===============================================================================================================================
  // FUNGSI UNTUK SCROLL 1000 = 1 DETIK
  // ===============================================================================================================================
  const intervalMs = 2000;

  function scrollToNext() {
    const target = scrollTargets[currentIndex];
    if (target) {
      target.scrollIntoView({ behavior: "smooth", block: "center" });
    }
    currentIndex = (currentIndex + 1) % scrollTargets.length;
  }

  // Jalankan auto-scroll
  let timer = setInterval(scrollToNext, intervalMs);

  // Optional: pause kalau user hover halaman (biar ga ganggu)
  // ============================================================================== komentar fungsi di bawah ini untuk refresh terus menerus
  document.body.addEventListener("mouseenter", () => clearInterval(timer));
  document.body.addEventListener("mouseleave", () => {
    clearInterval(timer);
    timer = setInterval(scrollToNext, intervalMs);
  });
  // // ============================================================================== Komen sampe sini ( block dulu yg atas lalu pencet ctrl + /)
});
