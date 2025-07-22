@extends('layouts.app')

@section('header_title', 'Simulasi Energi PLTS')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Kolom Kiri: Input & Hasil Cuaca -->
        <div class="flex flex-col gap-8">
            <!-- Card: Pencarian Kota -->
            <div class="card">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Kota Anda</h2>
                <p class="text-gray-500 mb-6">Masukkan nama kota untuk melihat data cuaca dan potensi energi surya.</p>
                <form id="weatherForm" action="{{ route('weather.get') }}" method="POST">
                    @csrf
                    <div class="relative">
                         <i class="fa-solid fa-search text-gray-400 absolute top-1/2 left-5 -translate-y-1/2"></i>
                        <input type="text" name="city" id="cityInput" placeholder="Contoh: Jakarta" class="search-input w-full text-lg" required>
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-full px-6 py-2 transition-all">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card: Hasil Informasi Cuaca -->
            <div class="card">
                 <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Cuaca</h2>
                 <!-- Konten Hasil Cuaca akan dimuat di sini oleh JavaScript -->
                 <div id="weather-results-container">
                    <div class="text-center py-10 text-gray-400">
                        <i class="fa-solid fa-cloud-sun fa-3x mb-4"></i>
                        <p>Hasil data cuaca akan muncul di sini.</p>
                    </div>
                 </div>
            </div>
        </div>

        <!-- Kolom Kanan: Simulator PLTS -->
        <div class="flex flex-col">
            <!-- Card: Simulasi PLTS -->
            <div id="plts-simulator-card" class="card flex-1 transition-opacity duration-500 opacity-50">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Simulasi PLTS</h2>
                <!-- Konten Simulator akan dipindahkan ke sini -->
                @include('partials.plts_simulator_form')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === DEKLARASI ELEMEN ===
    const weatherForm = document.getElementById('weatherForm');
    const cityInput = document.getElementById('cityInput');
    const weatherResultsContainer = document.getElementById('weather-results-container');
    const pltsSimulatorCard = document.getElementById('plts-simulator-card');
    const simulationForm = document.getElementById('simulation-form');

    // === EVENT LISTENER UNTUK PENCARIAN CUACA ===
    weatherForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const city = cityInput.value.trim();
        if (!city) return;

        weatherResultsContainer.innerHTML = `<div class="text-center py-8 text-gray-400 animate-pulse"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Mencari data untuk ${city}...</p></div>`;
        pltsSimulatorCard.classList.add('opacity-25');
        document.getElementById('simulation-results').style.display = 'none'; // Sembunyikan hasil lama

        try {
            const response = await fetch("{{ route('weather.get') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value, 'Accept': 'application/json' },
                body: JSON.stringify({ city: city })
            });
            const result = await response.json();
            if (response.ok && result.success) {
                displayWeatherData(result.data);
                updatePltsSimulator(result.data);
            } else {
                showError(result.error || 'Terjadi kesalahan.');
            }
        } catch (error) {
            showError('Kesalahan jaringan. Periksa koneksi Anda.');
        }
    });

    // === FUNGSI-FUNGSI BANTUAN ===
    function displayWeatherData(data) {
        const sunriseTime = new Date(data.sunrise * 1000).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const sunsetTime = new Date(data.sunset * 1000).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        weatherResultsContainer.innerHTML = `
            <div class="flex items-center gap-4 mb-6">
                <img src="http://openweathermap.org/img/wn/${data.icon}@2x.png" alt="${data.description}" class="w-16 h-16">
                <div>
                    <p class="text-3xl font-bold text-gray-800">${Math.round(data.temperature)}°C</p>
                    <p class="capitalize text-gray-500">${data.description}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div class="bg-gray-50 p-3 rounded-lg"><p class="text-gray-500">Terasa</p><p class="font-semibold text-gray-800">${Math.round(data.feels_like)}°C</p></div>
                <div class="bg-gray-50 p-3 rounded-lg"><p class="text-gray-500">Kelembaban</p><p class="font-semibold text-gray-800">${data.humidity}%</p></div>
                <div class="bg-gray-50 p-3 rounded-lg"><p class="text-gray-500">Angin</p><p class="font-semibold text-gray-800">${data.wind_speed} m/s</p></div>
                <div class="bg-gray-50 p-3 rounded-lg"><p class="text-gray-500">Tekanan</p><p class="font-semibold text-gray-800">${data.pressure} hPa</p></div>
                <div class="bg-gray-50 p-3 rounded-lg"><p class="text-gray-500">Jarak Pandang</p><p class="font-semibold text-gray-800">${data.visibility / 1000} km</p></div>
                <div class="bg-gray-50 p-3 rounded-lg"><p class="text-gray-500">Matahari</p><p class="font-semibold text-gray-800">${sunriseTime} - ${sunsetTime}</p></div>
            </div>`;
    }

    function updatePltsSimulator(data) {
        pltsSimulatorCard.classList.remove('opacity-25');
        const sunHoursInput = document.getElementById('sun_hours_per_day');
        const daylightHours = (data.sunset - data.sunrise) / 3600;
        const cloudFactor = 1 - (data.clouds / 100);
        let effectiveSunHours = Math.max(1, Math.min(daylightHours * 0.45 * cloudFactor, 8));
        sunHoursInput.value = effectiveSunHours.toFixed(1);
        simulationForm.dispatchEvent(new Event('submit'));
    }

    function showError(message) {
        weatherResultsContainer.innerHTML = `<div class="text-center py-8 text-red-500 font-semibold">${message}</div>`;
    }

    // === EVENT LISTENER UNTUK FORM SIMULASI PLTS (BAGIAN LENGKAP) ===
    simulationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Ambil semua nilai dari form
        const homeLoadWh = parseFloat(document.getElementById('home_load_wh').value);
        const panelEfficiency = parseFloat(document.getElementById('panel_efficiency').value) / 100;
        const sunHoursPerDay = parseFloat(document.getElementById('sun_hours_per_day').value);
        const inverterEfficiency = parseFloat(document.getElementById('inverter_efficiency').value) / 100;
        const panelWattagePerUnit = parseFloat(document.getElementById('panel_wattage_per_unit').value);
        const numberOfPanels = parseInt(document.getElementById('number_of_panels').value);

        // Lakukan kalkulasi
        const installedPanelCapacityWp = numberOfPanels * panelWattagePerUnit;
        const totalSystemEfficiency = panelEfficiency * inverterEfficiency;
        const estimatedDailyEnergyWh = installedPanelCapacityWp * sunHoursPerDay * totalSystemEfficiency;
        const surplusDeficitWh = estimatedDailyEnergyWh - homeLoadWh;
        const isSufficient = surplusDeficitWh >= 0;

        // Siapkan elemen untuk menampilkan hasil
        const resultsContainer = document.getElementById('simulation-results');
        resultsContainer.style.display = 'block';

        // Bangun HTML untuk hasilnya
        resultsContainer.innerHTML = `
            <h3 class="font-bold text-gray-700">Hasil Simulasi:</h3>
            <div class="mt-2 text-center p-3 rounded-lg font-semibold ${isSufficient ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${isSufficient ? "Sistem PLTS diperkirakan mencukupi kebutuhan." : "Sistem PLTS diperkirakan kurang dari kebutuhan."}
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 p-3 rounded-md">
                    <span class="text-gray-500">Estimasi Produksi</span>
                    <p class="font-bold text-lg text-green-600">${Math.round(estimatedDailyEnergyWh)} Wh</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-md">
                    <span class="text-gray-500">Kebutuhan</span>
                    <p class="font-bold text-lg text-blue-600">${Math.round(homeLoadWh)} Wh</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-md col-span-2">
                    <span class="text-gray-500">Surplus / Defisit</span>
                    <p class="font-bold text-lg ${surplusDeficitWh >= 0 ? 'text-green-600' : 'text-red-600'}">
                        ${Math.round(surplusDeficitWh)} Wh
                    </p>
                </div>
            </div>`;
    });
});
</script>