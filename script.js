// Fungsi untuk menampilkan bagian tertentu dan menyembunyikan lainnya
function showSection(event, sectionId) {
    if (event) event.preventDefault(); // Cegah reload halaman

    const sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active')); // Sembunyikan semua bagian

    setTimeout(() => {
        const target = document.getElementById(sectionId);
        if (target) target.classList.add('active'); // Tampilkan bagian yang dipilih
    }, 50);
}

// Fungsi untuk mengganti antara mode terang dan gelap
function toggleMode() {
    const body = document.body;

    // Hapus kedua mode terlebih dahulu
    body.classList.remove('light-mode', 'dark-mode');

    // Ganti mode
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('light-mode');
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
    }
}

// Terapkan tema saat halaman dimuat
window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.classList.remove('light-mode', 'dark-mode');
    document.body.classList.add(savedTheme + '-mode');
});
