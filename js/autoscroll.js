document.addEventListener("DOMContentLoaded", () => {
  // Semua section chart
  const sections = ["chart-bulanan", "chart-bar-bulanan", "chart-tahunan"];
  let currentIndex = 0;

  function scrollToNext() {
    const nextSection = document.getElementById(sections[currentIndex]);
    if (nextSection) {
      nextSection.scrollIntoView({ behavior: "smooth", block: "start" });
    }
    currentIndex = (currentIndex + 1) % sections.length;
  }

  // Jalankan auto scroll setiap 15 detik
  setInterval(scrollToNext, 3000);
});
