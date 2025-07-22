<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simulasi PLTS Rumah Tangga Berbasis Jurnal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    /><link rel="stylesheet" href="{{ asset('css/style.css') }}" /><link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="container">
      <header class="header">
        <h1>Simulator Pembangkit Listrik Tenaga Surya Rumah Tangga</h1>
        <p>
          Simulasikan kebutuhan dan produksi energi PLTS rumah tangga
          berdasarkan parameter dari studi kasus!
        </p>
      </header>

      <main class="main-layout-top-video">
        <section class="animation-section">
          <div class="sky-container">
            <div id="sun-container" class="sun-container">
              <img id="sun-image" src="{{ asset('assets/img/sun.png') }}" alt="Matahari" class="sun" />
            </div>
            <div
              id="cloud-container-1"
              class="cloud-container cloud-container-1"
            >
              <img
                src="{{ asset('assets/img/cloud2.png') }}"
                alt="Awan"
                class="cloud cloud-1"
              />
            </div>
            <div
              id="cloud-container-2"
              class="cloud-container cloud-container-2"
            >
              <img
                src="{{ asset('assets/img/cloud.png') }}"
                alt="Awan"
                class="cloud cloud-2"
              />
            </div>
            <div
              id="cloud-container-3"
              class="cloud-container cloud-container-3"
            >
              <img
                src="{{ asset('assets/img/cloud.png') }}"
                alt="Awan"
                class="cloud cloud-3"
              />
            </div>
            <div
              id="cloud-container-4"
              class="cloud-container cloud-container-4"
            >
              <img
                src="{{ asset('assets/img/cloud.png') }}"
                alt="Awan"
                class="cloud cloud-4"
              />
            </div>
            <div
              id="cloud-container-5"
              class="cloud-container cloud-container-5"
            >
              <img
                src="{{ asset('assets/img/cloud2.png') }}"
                alt="Awan"
                class="cloud cloud-5"
              />
            </div>
            <div
              id="cloud-container-6"
              class="cloud-container cloud-container-6"
            >
              <img
                src="{{ asset('assets/img/cloud.png') }}"
                alt="Awan"
                class="cloud cloud-6"
              />
            </div>
            <div
              id="cloud-container-7"
              class="cloud-container cloud-container-7"
            >
              <img
                src="{{ asset('assets/img/cloud2.png') }}"
                alt="Awan"
                class="cloud cloud-7"
              />
            </div>
            <div class="cloud-container cloud-container-8">
              <img
                src="{{ asset('assets/img/cloud.png') }}"
                alt="Awan"
                class="cloud cloud-8"
              />
            </div>
            <div class="cloud-container cloud-container-9">
              <img
                src="{{ asset('assets/img/cloud2.png') }}"
                alt="Awan"
                class="cloud cloud-9"
              />
            </div>
            <div class="cloud-container cloud-container-10">
              <img
                src="{{ asset('assets/img/cloud2.png') }}"
                alt="Awan"
                class="cloud cloud-10"
              />
            </div>
            <div class="cloud-container cloud-container-11">
              <img
                src="{{ asset('assets/img/cloud.png') }}"
                alt="Awan"
                class="cloud cloud-11"
              />
            </div>
            <div class="cloud-container cloud-container-12">
              <img
                src="{{ asset('assets/img/cloud2.png') }}"
                alt="Awan"
                class="cloud cloud-12"
              />
            </div>
          </div>
          <div id="animated-panel-list" class="animated-panel-list"></div>
          <div class="house-container">
            <img src="{{ asset('assets/img/house.png') }}" alt="Rumah PLTS" class="house" />
          </div>
          <div class="energy-flow-container">
            <div class="energy-flow-dot"></div>
            <div class="energy-flow-dot"></div>
            <div class="energy-flow-dot"></div>
          </div>
        </section>

        <div class="input-output-container-flex">
          <div class="input-panel">
            <form id="simulation-form" class="simulation-form">
              <h2 style="color: #facc15; margin-top: 0; margin-bottom: 1.5rem">
                Masukkan Parameter Simulasi
              </h2>
              <div class="form-grid-columns">
                <div class="form-group">
                  <label for="home_load_wh" class="form-label"
                    >Kebutuhan Energi Harian Rumah Tangga (Wh)</label
                  >
                  <p class="input-hint">
                    Total konsumsi listrik rumah Anda dalam satu hari. (Jurnal:
                    4500 Wh)
                  </p>
                  <input
                    type="number"
                    id="home_load_wh"
                    name="home_load_wh"
                    value="4500"
                    min="0"
                    step="100"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="panel_efficiency" class="form-label"
                    >Efisiensi Panel Surya (%)</label
                  >
                  <p class="input-hint">
                    Efisiensi konversi energi matahari oleh panel. (Jurnal: 80%)
                  </p>
                  <input
                    type="number"
                    id="panel_efficiency"
                    name="panel_efficiency"
                    value="80"
                    min="1"
                    max="100"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="sun_hours_per_day" class="form-label"
                    >Rata-rata Jam Penyinaran Matahari per Hari (Jam)</label
                  >
                  <p class="input-hint">
                    Rata-rata jam penyinaran matahari efektif di lokasi.
                    (Jurnal: 5 Jam)
                  </p>
                  <input
                    type="number"
                    id="sun_hours_per_day"
                    name="sun_hours_per_day"
                    value="5"
                    step="0.1"
                    min="0.1"
                    max="24"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="inverter_efficiency" class="form-label"
                    >Efisiensi Inverter (%)</label
                  >
                  <p class="input-hint">
                    Efisiensi konversi daya DC ke AC oleh inverter. (Jurnal:
                    95%)
                  </p>
                  <input
                    type="number"
                    id="inverter_efficiency"
                    name="inverter_efficiency"
                    value="95"
                    step="1"
                    min="1"
                    max="100"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="battery_capacity_ah" class="form-label"
                    >Kapasitas Baterai (Ah)</label
                  >
                  <p class="input-hint">
                    Kapasitas penyimpanan energi baterai. (Jurnal: 200 Ah @ 12V)
                  </p>
                  <input
                    type="number"
                    id="battery_capacity_ah"
                    name="battery_capacity_ah"
                    value="200"
                    min="1"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="battery_voltage_v" class="form-label"
                    >Tegangan Baterai (V)</label
                  >
                  <p class="input-hint">
                    Tegangan nominal baterai. (Jurnal: 12V)
                  </p>
                  <input
                    type="number"
                    id="battery_voltage_v"
                    name="battery_voltage_v"
                    value="12"
                    min="1"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="panel_wattage_per_unit" class="form-label"
                    >Daya per Panel Surya (Wp)</label
                  >
                  <p class="input-hint">
                    Daya listrik yang dihasilkan satu unit panel. (Jurnal: 300
                    Wp)
                  </p>
                  <input
                    type="number"
                    id="panel_wattage_per_unit"
                    name="panel_wattage_per_unit"
                    value="300"
                    min="1"
                    step="1"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="number_of_panels" class="form-label"
                    >Jumlah Panel Surya Digunakan</label
                  >
                  <p class="input-hint">
                    Berapa unit panel surya yang digunakan. (Jurnal: 4 Panel)
                  </p>
                  <input
                    type="number"
                    id="number_of_panels"
                    name="number_of_panels"
                    value="4"
                    min="0"
                    class="form-input"
                  />
                </div>
              </div>
              <br />
              <button type="submit" class="submit-button">
                Jalankan Simulasi
              </button>
            </form>
          </div>

          <div class="output-panel-only-results">
            <div id="results" class="results-display">
              <h2>Hasil Simulasi</h2>
              <div class="results-grid-columns">
                <div class="result-item">
                  <span>Kebutuhan Energi Harian (Wh):</span
                  ><span id="output-jurnal-daily-energy-wh" class="result-value"
                    >0 Wh</span
                  >
                </div>
                <div class="result-item">
                  <span>Kapasitas Panel Surya Dibutuhkan (Wp):</span
                  ><span
                    id="output-required-panel-capacity-wp"
                    class="result-value"
                    >0 Wp</span
                  >
                </div>
                <div class="result-item">
                  <span>Total Kapasitas Panel Terpasang (Wp):</span
                  ><span
                    id="output-installed-panel-capacity-wp"
                    class="result-value"
                    >0 Wp</span
                  >
                </div>
                <div class="result-item">
                  <span>Estimasi Energi Harian Dihasilkan (Wh):</span
                  ><span
                    id="output-estimated-daily-energy-wh"
                    class="result-value"
                    >0 Wh</span
                  >
                </div>
                <div class="result-item">
                  <span>Efisiensi Sistem Total (Panel x Inverter):</span
                  ><span
                    id="output-total-system-efficiency"
                    class="result-value"
                    >0%</span
                  >
                </div>
                <div class="result-item">
                  <span>Kapasitas Baterai Terpasang (Wh):</span
                  ><span id="output-battery-capacity-wh" class="result-value"
                    >0 Wh</span
                  >
                </div>
                <div class="result-item">
                  <span>Kesesuaian dengan Kebutuhan:</span
                  ><span id="output-is-sufficient" class="result-value"></span>
                </div>
                <div class="result-item">
                  <span>Surplus/Defisit Energi (Wh):</span
                  ><span id="output-surplus-deficit-wh" class="result-value"
                    >0 Wh</span
                  >
                </div>
              </div>
              <p id="output-message" class="results-message"></p>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
  </body>
</html>
