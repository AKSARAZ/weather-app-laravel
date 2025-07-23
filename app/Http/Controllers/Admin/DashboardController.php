<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallationRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchDate = $request->input('search_date');
        
        $query = InstallationRequest::with('user')->latest();

        // JIKA ADA INPUT PENCARIAN TEKS
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // JIKA ADA INPUT TANGGAL
        if ($searchDate) {
            // Tambahkan kondisi 'whereDate' ke query yang sudah ada
            $query->whereDate('created_at', $searchDate);
        }

        // Eksekusi query dengan SEMUA kondisi yang terkumpul
        $allRequests = $query->paginate(10)->withQueryString();

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
    /**
     * Menampilkan detail lengkap dari sebuah permintaan.
     */
    public function show(InstallationRequest $request)
    {
        // Cukup tampilkan view dan kirim data request yang dipilih
        return view('admin.requests.show', compact('request'));
    }

    /**
     * Menghapus data permintaan dari database.
     */
    public function destroy(InstallationRequest $request)
    {
        // KONDISI: Hanya bisa menghapus jika statusnya 'Completed'
        if ($request->status !== 'Completed') {
            return back()->with('error', 'Hanya permintaan dengan status "Completed" yang dapat dihapus.');
        }

        $request->delete();

        return redirect()->route('admin.requests.index')->with('success', 'Data permintaan berhasil dihapus.');
    }
}