<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallationRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard admin dengan request terbaru.
     */
    public function dashboard()
    {
        // Ambil 5 permintaan terbaru untuk ditampilkan sebagai ringkasan
        $latestRequests = InstallationRequest::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('latestRequests'));
    }

    /**
     * Menampilkan daftar SEMUA permintaan instalasi (halaman "Daftar Request").
     */
    public function index()
    {
        $allRequests = InstallationRequest::with('user')->latest()->paginate(10); // Paginate 10 per halaman

        return view('admin.requests.index', compact('allRequests'));
    }

    /**
     * Menampilkan form untuk mengedit permintaan & melakukan prediksi.
     */
    public function edit(InstallationRequest $request)
    {
        return view('admin.requests.edit', compact('request'));
    }

    /**
     * Memperbarui permintaan instalasi DAN menyimpan hasil prediksi.
     */
    public function update(Request $requestData, InstallationRequest $request)
    {
        // --- TAMBAHKAN BLOK INI ---
        // Ganti koma dengan titik untuk field yearly_psh sebelum validasi
        $requestData->merge([
            'yearly_psh' => str_replace(',', '.', $requestData->yearly_psh),
        ]);
        // --- AKHIR BLOK TAMBAHAN ---

        $validated = $requestData->validate([
            'yearly_psh' => 'required|numeric|min:1|max:8',
            'status' => 'required|string|in:Pending,Processed,Completed',
            'admin_notes' => 'nullable|string',
        ]);

        $daily_need_wh = $request->daily_energy_wh;
        $psh = $validated['yearly_psh'];
        $system_efficiency = 0.75;

        $recommended_wp = $daily_need_wh / ($psh * $system_efficiency);

        $request->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'recommended_panel_wp' => round($recommended_wp),
        ]);

        return redirect()->route('admin.requests.index')->with('success', 'Permintaan berhasil diperbarui!');
    }
}