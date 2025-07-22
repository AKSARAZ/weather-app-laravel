<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallationRequest; // <-- Import model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan daftar semua permintaan instalasi.
     * Ini adalah dashboard utama untuk admin.
     */
    public function index()
    {
        // Ambil semua permintaan, urutkan dari yang terbaru,
        // dan gunakan with('user') untuk mengambil data user terkait tanpa query tambahan (Eager Loading).
        $requests = InstallationRequest::with('user')->latest()->paginate(10); // Paginate untuk data yang banyak

        return view('admin.dashboard', compact('requests'));
    }

    /**
     * Menampilkan form untuk mengedit permintaan & melakukan prediksi.
     */
    public function edit(InstallationRequest $request) // <-- Menggunakan Route-Model Binding
    {
        // Laravel akan otomatis mencari InstallationRequest berdasarkan ID dari URL
        return view('admin.requests.edit', compact('request'));
    }

    /**
     * Memperbarui permintaan instalasi DAN menyimpan hasil prediksi.
     */
    public function update(Request $request, InstallationRequest $installationRequest) // <-- Ganti nama var agar tidak konflik
    {
        // 1. Validasi input dari admin
        $validated = $request->validate([
            'yearly_psh' => 'required|numeric|min:1|max:8', // Peak Sun Hour tahunan
            'status' => 'required|string|in:Pending,Processed,Completed', // Status harus salah satu dari ini
            'admin_notes' => 'nullable|string', // Catatan tidak wajib
        ]);

        // 2. Logika Perhitungan Prediksi
        $daily_need_wh = $installationRequest->daily_energy_wh;
        $psh = $validated['yearly_psh'];
        $system_efficiency = 0.75; // Asumsi total efisiensi sistem (panel, inverter, kabel, dll) adalah 75%

        // Rumus: Rekomendasi Wp = Kebutuhan Energi Harian / (PSH * Efisiensi Sistem)
        $recommended_wp = $daily_need_wh / ($psh * $system_efficiency);

        // 3. Update data di database
        $installationRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'recommended_panel_wp' => round($recommended_wp), // Simpan hasil perhitungan (dibulatkan)
        ]);

        // 4. Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.requests.index')->with('success', 'Permintaan berhasil diperbarui dan prediksi disimpan!');
    }

    // Method lain seperti create, store, show, destroy bisa Anda tambahkan nanti jika perlu.
    // Untuk saat ini, kita fokus pada index, edit, dan update.
}