document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("simulation-form");
    const resultsDiv = document.getElementById("results");

    // Elemen panel dinamis
    const panelContainer = document.getElementById("animated-panel-list");

    // Output elemen
    const outputJurnalDailyEnergyWh = document.getElementById(
        "output-jurnal-daily-energy-wh"
    );
    const outputRequiredPanelCapacityWp = document.getElementById(
        "output-required-panel-capacity-wp"
    );
    const outputInstalledPanelCapacityWp = document.getElementById(
        "output-installed-panel-capacity-wp"
    );
    const outputEstimatedDailyEnergyWh = document.getElementById(
        "output-estimated-daily-energy-wh"
    );
    const outputTotalSystemEfficiency = document.getElementById(
        "output-total-system-efficiency"
    );
    const outputBatteryCapacityWh = document.getElementById(
        "output-battery-capacity-wh"
    );
    const outputIsSufficient = document.getElementById("output-is-sufficient");
    const outputSurplusDeficitWh = document.getElementById(
        "output-surplus-deficit-wh"
    );
    const outputMessage = document.getElementById("output-message");

    // Fungsi update jumlah panel dinamis
    function updateAnimatedPanels(count) {
        panelContainer.innerHTML = "";
        for (let i = 0; i < count; i++) {
            const panel = document.createElement("div");
            panel.classList.add("animated-panel-item");
            animatedPanelList.appendChild(panel); // Tambah dari kiri
        }
    }

    // === Animasi Panel Surya Dinamis Langsung dari Input ===
    const numberOfPanelsInput = document.getElementById("number_of_panels");
    const animatedPanelList = document.getElementById("animated-panel-list");

    function updatePanelAnimation(n) {
        // Kosongkan panel sebelumnya
        animatedPanelList.innerHTML = "";

        // Tambahkan panel sesuai jumlah input
        for (let i = 0; i < n; i++) {
            const panel = document.createElement("div");
            panel.classList.add("animated-panel-item");
            animatedPanelList.appendChild(panel);
        }
    }

    // Event listener agar langsung update saat input berubah
    numberOfPanelsInput.addEventListener("input", function () {
        const jumlah = parseInt(this.value);
        if (!isNaN(jumlah) && jumlah >= 0) {
            updatePanelAnimation(jumlah);
        }
    });

    // Jalankan sekali di awal (default 4 panel)
    updatePanelAnimation(parseInt(numberOfPanelsInput.value));

    // Event saat submit form
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const homeLoadWh = parseFloat(formData.get("home_load_wh"));
        const panelEfficiency =
            parseFloat(formData.get("panel_efficiency")) / 100;
        const sunHoursPerDay = parseFloat(formData.get("sun_hours_per_day"));
        const inverterEfficiency =
            parseFloat(formData.get("inverter_efficiency")) / 100;
        const batteryCapacityAh = parseFloat(
            formData.get("battery_capacity_ah")
        );
        const batteryVoltageV = parseFloat(formData.get("battery_voltage_v"));
        const panelWattagePerUnit = parseFloat(
            formData.get("panel_wattage_per_unit")
        );
        const numberOfPanels = parseInt(formData.get("number_of_panels"));

        // Hitung data
        const effectiveSunHours = sunHoursPerDay;
        const requiredPanelCapacityWp =
            homeLoadWh / (effectiveSunHours * panelEfficiency);
        const installedPanelCapacityWp = numberOfPanels * panelWattagePerUnit;
        const totalSystemEfficiency = panelEfficiency * inverterEfficiency;
        const estimatedDailyEnergyWh =
            installedPanelCapacityWp *
            effectiveSunHours *
            totalSystemEfficiency;
        const batteryCapacityWh = batteryCapacityAh * batteryVoltageV;
        const isSufficient = estimatedDailyEnergyWh >= homeLoadWh;
        const surplusDeficitWh = estimatedDailyEnergyWh - homeLoadWh;

        // Tampilkan hasil
        resultsDiv.style.display = "block";
        outputJurnalDailyEnergyWh.textContent = `${homeLoadWh.toFixed(0)} Wh`;
        outputRequiredPanelCapacityWp.textContent = `${requiredPanelCapacityWp.toFixed(
            2
        )} Wp`;
        outputInstalledPanelCapacityWp.textContent = `${installedPanelCapacityWp.toFixed(
            0
        )} Wp`;
        outputEstimatedDailyEnergyWh.textContent = `${estimatedDailyEnergyWh.toFixed(
            2
        )} Wh`;
        outputTotalSystemEfficiency.textContent = `${(
            totalSystemEfficiency * 100
        ).toFixed(2)}%`;
        outputBatteryCapacityWh.textContent = `${batteryCapacityWh.toFixed(
            0
        )} Wh`;
        outputIsSufficient.textContent = isSufficient ? "Cukup" : "Kurang";
        outputIsSufficient.style.color = isSufficient ? "#34d399" : "#ef4444";
        outputSurplusDeficitWh.textContent = `${surplusDeficitWh.toFixed(
            2
        )} Wh`;
        outputSurplusDeficitWh.style.color =
            surplusDeficitWh >= 0 ? "#34d399" : "#ef4444";
        outputMessage.textContent = isSufficient
            ? "Sistem PLTS yang dirancang diperkirakan mencukupi kebutuhan harian!"
            : "Sistem PLTS yang dirancang diperkirakan belum sepenuhnya mencukupi kebutuhan harian.";
        outputMessage.style.backgroundColor = isSufficient
            ? "#dcfce7"
            : "#fee2e2";
        outputMessage.style.color = isSufficient ? "#16a34a" : "#dc2626";

        // Animasi panel sesuai input
        updateAnimatedPanels(numberOfPanels);

        // Scroll ke hasil
        resultsDiv.scrollIntoView({ behavior: "smooth", block: "start" });
    });
    document
        .getElementById("simulation-form")
        .addEventListener("submit", function (e) {
            e.preventDefault();

            const numberOfPanels = parseInt(
                document.getElementById("number_of_panels").value
            );
            const energyFlowContainer = document.querySelector(
                ".energy-flow-container"
            );

            // Hitung panjang berdasarkan jumlah panel: 1 panel = 60px lebar kabel
            const cableLength = Math.max(1, numberOfPanels) * 60; // Minimum 60px
            energyFlowContainer.style.width = cableLength + "px";

            // Tampilkan jalurnya
            energyFlowContainer.style.display = "block";
        });
});
