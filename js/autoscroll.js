document.addEventListener("DOMContentLoaded", () => {
  // Ambil semua section utama
  const mainSections = [
    document.getElementById("chart-bulanan"),
    document.getElementById("chart-bar-bulanan"),
    document.getElementById("chart-tahunan"),
  ].filter(Boolean);

  const infoTitles = Array.from(document.querySelectorAll("#informasi h1"));

  // Gabungkan semua ke dalam satu array urutan scroll
  const scrollTargets = [...mainSections, ...infoTitles];

  if (scrollTargets.length === 0) return; // kalau kosong, jangan lanjut

  let currentIndex = 0;
  const intervalMs = 300000; // 3 detik antar-scroll

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
  document.body.addEventListener("mouseenter", () => clearInterval(timer));
  document.body.addEventListener("mouseleave", () => {
    clearInterval(timer);
    timer = setInterval(scrollToNext, intervalMs);
  });
});
