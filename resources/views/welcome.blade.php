<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Skrining Stunting & Analisis Klaster</title>

    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Memuat Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Menggunakan font Inter sebagai default */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Styling untuk canvas background */
        #particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Konten di atas canvas */
        .content-overlay {
            position: relative;
            z-index: 2;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <div id="auth" class="flex min-h-screen">

        <!-- Kolom Kiri: Form Login -->
        <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 lg:p-16">
            <div class="w-full max-w-md">
                <div class="text-center md:text-left mb-10">
                    <!-- Ikon SVG baru: Simbol pertumbuhan dan kesehatan anak -->
                    <svg class="w-12 h-12 mx-auto md:mx-0 mb-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345h5.354a.562.562 0 01.354.955l-4.05 3.502a.563.563 0 00-.182.557l1.528 4.707a.562.562 0 01-.812.622l-4.124-3.258a.563.563 0 00-.67 0l-4.123 3.258a.562.562 0 01-.812-.622l1.528-4.707a.563.563 0 00-.182-.557l-4.05-3.502a.562.562 0 01.354-.955h5.354a.563.563 0 00.475-.345L11.48 3.5z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M18 21a4 4 0 00-4-4H9a4 4 0 00-4 4" />
                    </svg>

                    <h1 class="text-3xl font-bold text-slate-900">Platform Skrining Stunting</h1>
                    <p class="text-slate-500 mt-2">Masuk untuk mengakses data dan wawasan pertumbuhan balita.</p>
                </div>

                <form action="#">
                    <!-- Input Email -->
                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium text-slate-700">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </span>
                            <input type="email" name="email" id="email" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full pl-10 p-2.5" placeholder="contoh@email.com" required>
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div class="mb-6">
                         <label for="password" class="block mb-2 text-sm font-medium text-slate-700">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                               <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" id="password" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full pl-10 p-2.5" placeholder="•••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="remember" type="checkbox" value="" class="w-4 h-4 text-teal-600 bg-slate-100 border-slate-300 rounded focus:ring-teal-500 ring-offset-slate-50">
                            <label for="remember" class="ml-2 text-sm font-medium text-slate-700">Ingat saya</label>
                        </div>
                        <a href="#" class="text-sm text-teal-600 hover:underline">Lupa Kata Sandi?</a>
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit" class="w-full text-white bg-teal-600 hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition duration-300 ease-in-out">Masuk</button>

                    <p class="text-sm text-center text-slate-500 mt-6">
                        Belum punya akun? <a href="#" class="font-medium text-teal-600 hover:underline">Daftar di sini</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Visualisasi/Animasi -->
        <div class="hidden md:block w-1/2 relative bg-white">
            <canvas id="particle-canvas"></canvas>
            <div class="content-overlay flex flex-col justify-center items-center h-full p-16 text-center bg-white bg-opacity-25">
                 <h2 class="text-4xl font-bold leading-tight text-slate-900">Memetakan Pertumbuhan, <br>Mencegah Stunting.</h2>
                 <p class="mt-4 text-lg text-slate-600 max-w-lg">Platform kami membantu mengidentifikasi pola dan risiko stunting melalui analisis klaster untuk intervensi yang lebih dini dan efektif.</p>
            </div>
        </div>

    </div>

    <script>
        // --- Skrip untuk Animasi Partikel di Latar Belakang ---

        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particlesArray;

        const colors = [
            'rgba(13, 148, 136, 0.5)', // Teal-600
            'rgba(14, 165, 233, 0.5)', // Sky-500
            'rgba(56, 189, 248, 0.5)'  // Sky-400
        ];

        // Fungsi untuk menyesuaikan ukuran canvas dengan container
        function setCanvasSize() {
            const container = canvas.parentElement;
            canvas.width = container.offsetWidth;
            canvas.height = container.offsetHeight;
        }

        // Objek partikel
        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2.5 + 1;
                this.speedX = Math.random() * 0.8 - 0.4;
                this.speedY = Math.random() * 0.8 - 0.4;
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }
            // Memperbarui posisi partikel
            update() {
                if (this.x > canvas.width || this.x < 0) {
                    this.speedX = -this.speedX;
                }
                if (this.y > canvas.height || this.y < 0) {
                    this.speedY = -this.speedY;
                }
                this.x += this.speedX;
                this.y += this.speedY;
            }
            // Menggambar partikel
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        // Inisialisasi partikel
        function init() {
            setCanvasSize();
            particlesArray = [];
            let numberOfParticles = (canvas.height * canvas.width) / 10000;
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }
        }

        // Menghubungkan partikel dengan garis untuk membentuk klaster visual
        function connect() {
            let opacityValue = 1;
            for (let a = 0; a < particlesArray.length; a++) {
                for (let b = a; b < particlesArray.length; b++) {
                    let distance = ((particlesArray[a].x - particlesArray[b].x) * (particlesArray[a].x - particlesArray[b].x)) +
                        ((particlesArray[a].y - particlesArray[b].y) * (particlesArray[a].y - particlesArray[b].y));

                    // Jarak koneksi diperkecil untuk menciptakan efek klaster
                    if (distance < (canvas.width / 10) * (canvas.height / 10)) {
                        opacityValue = 1 - (distance / 15000);
                        ctx.strokeStyle = `rgba(14, 165, 233, ${opacityValue})`; // Warna biru langit (sky-500)
                        ctx.lineWidth = 0.5;
                        ctx.beginPath();
                        ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                        ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                        ctx.stroke();
                    }
                }
            }
        }

        // Loop animasi
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
            }
            connect();
            requestAnimationFrame(animate);
        }

        // Event listener untuk resize window
        window.addEventListener('resize', () => {
            init();
        });

        // Memulai semuanya
        init();
        animate();
    </script>

</body>

</html>
