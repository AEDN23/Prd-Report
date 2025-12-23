document.addEventListener("DOMContentLoaded", () => {
    const scrollInterval = 10000; // 10 detik
    const maxScrolls = 6; // 6x10 detik = 60 detik (1 menit)
    let scrollCount = 0;

    const scrollStep = window.innerHeight; // 100vh

    setInterval(() => {
        const scrollTop = window.scrollY;
        const windowHeight = window.innerHeight;
        const documentHeight = document.body.scrollHeight;

        // kalau sudah mentok bawah ATAU sudah 6x scroll
        if (scrollTop + windowHeight >= documentHeight - 10 || scrollCount >= maxScrolls) {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
            scrollCount = 0;
        } else {
            window.scrollBy({
                top: scrollStep,
                behavior: "smooth"
            });
            scrollCount++;
        }
    }, scrollInterval);
});
