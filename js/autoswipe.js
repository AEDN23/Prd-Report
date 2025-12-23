const pages = [
    "index.php",
    "chart-line-b.php",
    "chart-tahunan-a.php",
    "chart-tahunan-b.php",
    "informasi.php"
];

// ⏱️ 1 MENIT
const interval = 60000; // 60.000 ms = 1 menit
const transitionTime = 1000;

const currentPage = location.pathname.split("/").pop();
let currentIndex = pages.indexOf(currentPage);
if (currentIndex === -1) currentIndex = 0;

const nextIndex = (currentIndex + 1) % pages.length;

setTimeout(() => {
    document.querySelector(".swipe-layer").classList.add("active");

    setTimeout(() => {
        location.href = pages[nextIndex];
    }, transitionTime);

}, interval);
