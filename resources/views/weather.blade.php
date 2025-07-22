<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Cuaca - Modern & Interaktif</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Mengatur font Poppins untuk seluruh elemen */
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Latar belakang gradien dinamis berdasarkan cuaca */
        .sunny-bg {
            /* Gradien untuk siang hari cerah: Kuning ke Oranye Cerah */
            background: linear-gradient(135deg, #FFD700, #FFC107, #FFA000, #FF8C00); 
        }
        
        .cloudy-bg { 
            /* Gradien untuk cuaca berawan: Biru Langit ke Ungu Kebiruan */
            background: linear-gradient(135deg, #74b9ff, #0984e3, #74b9ff, #a29bfe);
        }
        
        .rainy-bg { 
            /* Gradien untuk cuaca hujan/badai: Abu-abu Gelap ke Biru Cerah */
            background: linear-gradient(135deg, #636e72, #2d3436, #74b9ff, #0984e3);
        }
        
        .snowy-bg { 
            /* Gradien untuk cuaca salju: Ungu Sangat Muda ke Putih */
            background: linear-gradient(135deg, #ddd6fe, #e0e7ff, #f1f5f9, #ffffff);
        }
        
        .default-bg { /* Latar belakang default jika tidak ada data cuaca atau ikon tidak jelas */
            /* Gradien default: Biru Keunguan ke Merah Muda */
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb, #f5576c);
        }

        .night-bg { /* Latar belakang untuk malam hari (akan menimpa background cuaca jika malam) */
            /* Gradien malam: Biru Tua Gelap (#1A237E), Biru Tua (#283593), Biru Ungu (#303F9F), Biru Sedang (#3F51B5) */
            background: linear-gradient(135deg, #1A237E, #283593, #303F9F, #3F51B5); 
        }
        
        /* Animasi gradien latar belakang */
        .animated-bg {
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Efek Glassmorphism untuk kartu utama */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Efek Glassmorphism untuk input */
        .glass-input {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Animasi masuk kartu cuaca */
        .weather-card-enter {
            animation: slideInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Animasi gradien teks untuk tampilan suhu */
        .temperature-display {
            background: linear-gradient(45deg, #ff6b6b, #ffd93d, #6bcf7f, #4d79ff);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientText 3s ease infinite;
        }
        
        @keyframes gradientText {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Animasi ikon cuaca */
        .weather-icon {
            animation: float 3s ease-in-out infinite;
            /* Drop shadow yang lebih kuat */
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.4));
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        
        /* Efek tombol pencarian */
        .search-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .search-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .search-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .search-btn:hover:before {
            left: 100%;
        }
        
        /* Animasi loading */
        .loading-pulse {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Kartu detail cuaca */
        .detail-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .detail-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        /* Animasi guncangan error */
        .error-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        /* Partikel latar belakang */
        .particle {
            position: fixed;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat 8s linear infinite;
            z-index: 1;
        }
        
        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Styling ikon header (awan dan matahari) */
        .header-icon-gradient {
            /* Menerapkan gradien kuning ke putih pada ikon Font Awesome */
            background: linear-gradient(45deg, #FFD700, #FFFFFF); /* Kuning ke Putih */
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        /* Penyesuaian responsif untuk mobile */
        @media (max-width: 640px) {
            .temperature-display {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body class="default-bg animated-bg min-h-screen flex items-center justify-center p-4 relative" id="appBody">
    <!-- Kontainer Partikel Dinamis -->
    <div id="particleContainer"></div>

    <!-- Kontainer Utama Aplikasi Cuaca -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-2xl w-full relative z-10 weather-card-enter">
        <!-- Header dengan judul animasi -->
        <div class="text-center mb-8">
            <div class="text-5xl sm:text-6xl mb-4">
                {{-- Menggunakan Font Awesome ikon awan dan matahari dengan gradien warna --}}
                <i class="fas fa-cloud-sun header-icon-gradient drop-shadow-lg"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">
                Pemantauan Cuaca
            </h1>
            <p class="text-white/70 text-lg">
                Temukan kondisi cuaca di seluruh dunia
            </p>
        </div>

        <!-- Form Pencarian -->
        <form id="weatherForm" action="{{ route('weather.get') }}" method="POST" class="mb-8">
            @csrf
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-grow">
                    <input type="text" 
                           name="city" 
                           id="cityInput" 
                           placeholder="Masukkan nama kota..." 
                           class="glass-input w-full rounded-2xl border-0 p-4 pr-12 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-300" 
                           required>
                </div>
                <button type="submit" 
                        class="search-btn text-white px-8 py-4 rounded-2xl font-semibold text-lg shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Cari
                </button>
            </div>
        </form>

        <!-- Pesan Error -->
        @if (session('error'))
            <div class="error-shake glass-card bg-red-500/20 text-white rounded-2xl p-4 mb-6 border border-red-300/30" id="errorMessage">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @else
            <div class="hidden error-shake glass-card bg-red-500/20 text-white rounded-2xl p-4 mb-6 border border-red-300/30" id="errorMessage">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                    <span class="error-text"></span>
                </div>
            </div>
        @endif

        <!-- Kontainer Hasil Cuaca -->
        <div id="weatherResult">
            @if (isset($data))
                <div class="weather-card-enter">
                    <!-- Informasi Cuaca Utama -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-red-400 mr-2"></i>
                            {{ $data['city'] }}, {{ $data['country'] }}
                        </h2>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-6">
                            <img src="http://openweathermap.org/img/wn/{{ $data['icon'] }}@4x.png" 
                                 alt="{{ $data['description'] }}" 
                                 class="weather-icon w-32 h-32">
                            <div class="text-center sm:text-left">
                                <div class="temperature-display text-6xl sm:text-7xl font-extrabold mb-2">
                                    {{ round($data['temperature']) }}°C
                                </div>
                                <p class="text-xl text-white/90 capitalize font-medium">
                                    {{ $data['description'] }}
                                </p>
                                <p class="text-white/70 text-sm mt-1">
                                    Terasa seperti {{ round($data['feels_like']) }}°C
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Detail Cuaca -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-tint text-3xl text-blue-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Kelembaban Relatif</div>
                            <div class="text-white text-xl font-bold">${data.humidity}%</div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-wind text-3xl text-green-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Kecepatan Angin</div>
                            <div class="text-white text-xl font-bold">${data.wind_speed} m/s</div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-thermometer-half text-3xl text-orange-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Tekanan Udara</div>
                            <div class="text-white text-xl font-bold">${data.pressure} hPa</div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-eye text-3xl text-purple-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Jarak Pandang</div>
                            <div class="text-white text-xl font-bold">${(data.visibility / 1000) || 'N/A'} km</div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="detail-card rounded-2xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <i class="fas fa-sun text-2xl text-yellow-400 mb-1"></i>
                                    <div class="text-white/80 text-sm">Matahari Terbit</div>
                                    <div class="text-white text-lg font-semibold">${formatTime(data.sunrise)}</div>
                                </div>
                                <div class="text-right">
                                    <i class="fas fa-moon text-2xl text-indigo-400 mb-1"></i>
                                    <div class="text-white/80 text-sm">Matahari Terbenam</div>
                                    <div class="text-white text-lg font-semibold">${formatTime(data.sunset)}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4">
                            <div class="text-center">
                                <i class="fas fa-cloud text-2xl text-gray-300 mb-2"></i>
                                <div class="text-white/80 text-sm">Keadaan Berawan</div>
                                <div class="text-white text-xl font-bold">${data.clouds}%</div>
                                <div class="w-full bg-white/20 rounded-full h-2 mt-2">
                                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full transition-all duration-500" style="width: ${data.clouds}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-6">
                        <i class="fas fa-search text-white/50"></i>
                    </div>
                    <h3 class="text-2xl text-white font-semibold mb-2">
                        Siap Menjelajah?
                    </h3>
                    <p class="text-white/70 text-lg">
                        Masukkan nama kota untuk mendapatkan informasi cuaca terperinci
                    </p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-white/20 text-center">
            <p class="text-white/60 text-sm flex items-center justify-center">
                <i class="fas fa-bolt text-yellow-400 mr-2"></i>
                Didukung oleh OpenWeather API
            </p>
        </div>
    </div>

    <script>
        // Membuat partikel mengambang
        function createParticles() {
            const container = document.getElementById('particleContainer');
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.width = Math.random() * 6 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 5 + 8) + 's';
                container.appendChild(particle);
                
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.remove();
                    }
                }, 13000);
            }
        }

        // Inisialisasi partikel
        createParticles();
        setInterval(createParticles, 5000);

        // Mengubah latar belakang dinamis berdasarkan kode cuaca
        function updateBackground(weatherCode, iconCode) { // Mengubah parameter dari description ke iconCode
            const body = document.getElementById('appBody');
            body.className = body.className.replace(/\w+-bg/g, ''); // Hapus kelas latar belakang yang ada
            
            // Memetakan ID cuaca OpenWeatherMap ke kelas latar belakang kustom
            if (weatherCode >= 200 && weatherCode < 300) { // Badai Petir
                body.classList.add('rainy-bg');
            } else if (weatherCode >= 300 && weatherCode < 600) { // Gerimis, Hujan
                body.classList.add('rainy-bg');
            } else if (weatherCode >= 600 && weatherCode < 700) { // Salju
                body.classList.add('snowy-bg');
            } else if (weatherCode >= 700 && weatherCode < 800) { // Atmosfer (Kabut, Asap, Kabut Asap, dll.)
                body.classList.add('cloudy-bg');
            } else if (weatherCode === 800) { // Langit cerah
                body.classList.add('sunny-bg');
            } else if (weatherCode > 800) { // Berawan
                body.classList.add('cloudy-bg');
            } else {
                body.classList.add('default-bg');
            }
            
            // Override untuk malam hari jika ikon menunjukkan malam
            if (iconCode && iconCode.includes('n')) {
                body.classList.remove('sunny-bg', 'cloudy-bg', 'rainy-bg', 'snowy-bg', 'default-bg'); // Hapus semua background cuaca
                body.classList.add('night-bg'); // Terapkan background malam
            }
            
            body.classList.add('animated-bg');
        }

        // Penanganan submit form yang ditingkatkan
        document.getElementById('weatherForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // Mencegah submit form default

            const cityInput = document.getElementById('cityInput');
            const errorMessageDiv = document.getElementById('errorMessage');
            const errorTextSpan = errorMessageDiv.querySelector('.error-text');
            const weatherResultDiv = document.getElementById('weatherResult');
            const csrfToken = this.querySelector('input[name="_token"]').value;

            // Sembunyikan pesan error sebelumnya dan tampilkan loading
            errorMessageDiv.classList.add('hidden');
            
            const city = cityInput.value.trim();

            if (!city) {
                showError('Mohon masukkan nama kota.');
                return;
            }

            // Animasi loading yang ditingkatkan
            weatherResultDiv.innerHTML = `
                <div class="text-center py-12 loading-pulse">
                    <div class="inline-block">
                        <i class="fas fa-spinner fa-spin text-4xl text-white mb-4"></i>
                    </div>
                    <h3 class="text-2xl text-white font-semibold mb-2">
                        Mengambil Data Cuaca
                    </h3>
                    <p class="text-white/70">
                        Mohon tunggu saat kami mendapatkan informasi terbaru untuk ${city}
                    </p>
                    <div class="mt-4 flex justify-center">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-white rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            `;

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({ city: city }).toString()
                });

                // Jika Laravel melakukan redirect (misal: karena validasi non-AJAX atau error lain),
                // kita ikuti redirect tersebut. Ini terjadi jika request tidak terdeteksi sebagai AJAX.
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                // Jika respons bukan redirect, berarti ini adalah respons JSON dari request AJAX
                const result = await response.json();

                if (result.error) {
                    showError(result.error);
                    showEmptyState();
                } else if (result.data) {
                    displayWeatherData(result.data);
                    // Perbarui latar belakang berdasarkan kode cuaca aktual DAN ikon (d/n)
                    updateBackground(result.data.weather_id, result.data.icon); // Menggunakan result.data.icon
                } else {
                    showError('Respons tidak valid dari server.');
                    showEmptyState();
                }

            } catch (error) {
                console.error('Error fetching weather:', error);
                showError('Kesalahan jaringan. Mohon periksa koneksi Anda dan coba lagi.');
                showEmptyState();
            }
        });

        // Menampilkan pesan error
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.querySelector('.error-text').textContent = message;
            errorDiv.classList.remove('hidden');
            errorDiv.classList.add('error-shake');
            setTimeout(() => errorDiv.classList.add('hidden'), 5000); // Auto-hide error after 5 seconds
        }

        // Menampilkan tampilan kosong/default
        function showEmptyState() {
            document.getElementById('weatherResult').innerHTML = `
                <div class="text-center py-12">
                    <div class="text-6xl mb-6">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <h3 class="text-2xl text-white font-semibold mb-2">
                        Data cuaca tidak ditemukan
                    </h3>
                    <p class="text-white/70 text-lg">
                        Mohon coba cari kota lain
                    </p>
                </div>
            `;
        }

        // Menampilkan data cuaca
        function displayWeatherData(data) {
            // Mengkonversi timestamp ke format waktu yang mudah dibaca untuk matahari terbit/terbenam
            const formatTime = (timestamp) => {
                // Kembalikan 'N/A' jika timestamp null, undefined, atau 0
                if (!timestamp) return 'N/A';
                const date = new Date(timestamp * 1000); // OpenWeatherMap menyediakan Unix timestamp dalam detik
                // Periksa apakah objek tanggal valid (misal: bukan 'Invalid Date')
                if (isNaN(date.getTime())) {
                    return 'N/A';
                }
                // Gunakan toLocaleTimeString untuk format waktu yang benar berdasarkan lokal
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
            };

            document.getElementById('weatherResult').innerHTML = `
                <div class="weather-card-enter">
                    <!-- Informasi Cuaca Utama -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-red-400 mr-2"></i>
                            ${data.city}, ${data.country}
                        </h2>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-6">
                            <img src="http://openweathermap.org/img/wn/${data.icon}@4x.png" 
                                 alt="${data.description}" 
                                 class="weather-icon w-32 h-32">
                            <div class="text-center sm:text-left">
                                <div class="temperature-display text-6xl sm:text-7xl font-extrabold mb-2">
                                    ${Math.round(data.temperature)}°C
                                </div>
                                <p class="text-xl text-white/90 capitalize font-medium">
                                    ${data.description}
                                </p>
                                <p class="text-white/70 text-sm mt-1">
                                    Terasa seperti ${Math.round(data.feels_like)}°C
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Detail Cuaca -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-tint text-3xl text-blue-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Kelembaban Relatif</div>
                            <div class="text-white text-xl font-bold">${data.humidity}%</div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-wind text-3xl text-green-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Kecepatan Angin</div>
                            <div class="text-white text-xl font-bold">${data.wind_speed} m/s</div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-thermometer-half text-3xl text-orange-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Tekanan Udara</div>
                            <div class="text-white text-xl font-bold">${data.pressure} hPa</div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4 text-center">
                            <i class="fas fa-eye text-3xl text-purple-400 mb-2"></i>
                            <div class="text-white/80 text-sm">Jarak Pandang</div>
                            <div class="text-white text-xl font-bold">${(data.visibility / 1000) || 'N/A'} km</div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="detail-card rounded-2xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <i class="fas fa-sun text-2xl text-yellow-400 mb-1"></i>
                                    <div class="text-white/80 text-sm">Matahari Terbit</div>
                                    <div class="text-white text-lg font-semibold">${formatTime(data.sunrise)}</div>
                                </div>
                                <div class="text-right">
                                    <i class="fas fa-moon text-2xl text-indigo-400 mb-1"></i>
                                    <div class="text-white/80 text-sm">Matahari Terbenam</div>
                                    <div class="text-white text-lg font-semibold">${formatTime(data.sunset)}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-card rounded-2xl p-4">
                            <div class="text-center">
                                <i class="fas fa-cloud text-2xl text-gray-300 mb-2"></i>
                                <div class="text-white/80 text-sm">Keadaan Berawan</div>
                                <div class="text-white text-xl font-bold">${data.clouds}%</div>
                                <div class="w-full bg-white/20 rounded-full h-2 mt-2">
                                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full transition-all duration-500" style="width: ${data.clouds}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Peningkatan input
        const cityInput = document.getElementById('cityInput');
        
        cityInput.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 0 0 3px rgba(255, 255, 255, 0.2)';
        });

        cityInput.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });

        // Sembunyikan pesan error otomatis setelah 5 detik
        function autoHideError() {
            const errorDiv = document.getElementById('errorMessage');
            if (!errorDiv.classList.contains('hidden')) {
                setTimeout(() => {
                    errorDiv.classList.add('hidden');
                }, 5000);
            }
        }

        // Panggil auto-hide saat halaman dimuat jika ada error
        if (!document.getElementById('errorMessage').classList.contains('hidden')) {
            autoHideError();
        }
    </script>
</body>
</html>
