<!-- resources/views/partials/plts_simulator_form.blade.php -->

<form id="simulation-form" class="simulation-form">
    <p class="text-gray-500 mb-6 text-sm">Isi parameter di bawah atau gunakan data otomatis dari hasil pencarian cuaca.</p>
    
    <div class="grid grid-cols-2 gap-x-4 gap-y-5">
        <div>
            <label for="sun_hours_per_day" class="block text-xs font-medium text-gray-500">Jam Penyinaran Efektif (Jam)</label>
            <input type="number" id="sun_hours_per_day" value="5" step="0.1" class="mt-1 w-full p-2 border-0 bg-gray-100 rounded-lg text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="home_load_wh" class="block text-xs font-medium text-gray-500">Kebutuhan Energi Harian (Wh)</label>
            <input type="number" id="home_load_wh" value="4500" step="100" class="mt-1 w-full p-2 border-0 bg-gray-100 rounded-lg text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="number_of_panels" class="block text-xs font-medium text-gray-500">Jumlah Panel Surya</label>
            <input type="number" id="number_of_panels" value="4" class="mt-1 w-full p-2 border-0 bg-gray-100 rounded-lg text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="panel_wattage_per_unit" class="block text-xs font-medium text-gray-500">Daya per Panel (Wp)</label>
            <input type="number" id="panel_wattage_per_unit" value="300" step="1" class="mt-1 w-full p-2 border-0 bg-gray-100 rounded-lg text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <input type="hidden" id="panel_efficiency" value="80">
        <input type="hidden" id="inverter_efficiency" value="95">
    </div>
    <br />
    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition-all">
        Jalankan Simulasi
    </button>
</form>

<div id="simulation-results" class="mt-6" style="display: none;">
    <h3 class="font-bold text-gray-700 dark:text-gray-300">Hasil Simulasi:</h3>
    <div id="output-message" class="mt-2 text-center p-3 rounded-lg font-semibold"></div>
    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
            <span class="text-gray-500 dark:text-gray-400">Estimasi Produksi Energi</span>
            <p id="output-estimated-daily-energy-wh" class="font-bold text-lg text-green-600">0 Wh</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
            <span class="text-gray-500 dark:text-gray-400">Kebutuhan Energi</span>
            <p id="output-jurnal-daily-energy-wh" class="font-bold text-lg text-blue-600">0 Wh</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md col-span-2">
            <span class="text-gray-500 dark:text-gray-400">Surplus / Defisit</span>
            <p id="output-surplus-deficit-wh" class="font-bold text-lg">0 Wh</p>
        </div>
    </div>
    
    <!-- Tombol "Ajukan Pemasangan" sudah dihapus dari sini -->
    
</div>