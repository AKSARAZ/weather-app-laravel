<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InstallationRequest; // <-- Import model
use Carbon\Carbon;

class RequestController extends Controller
{
    /**
     * Menampilkan halaman/form untuk membuat permintaan baru.
     */
    public function create()
    {
        return view('user.requests.create');
    }

    /**
     * Menyimpan permintaan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi semua input dari form
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'customer_phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'daily_energy_wh' => 'required|integer|min:1',
        ]);

        // Buat entri baru yang terhubung dengan user yang sedang login
        Auth::user()->installationRequests()->create($validatedData);

        // Arahkan kembali ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Permintaan instalasi Anda telah berhasil dikirim! Tim kami akan segera menindaklanjuti.');
    }

    /**
     * Menampilkan halaman riwayat permintaan milik pengguna.
     */
    public function history(Request $request)
    {
        $search = $request->input('search');
        
        // Mulai query HANYA dari permintaan milik user yang sedang login
        $query = Auth::user()->installationRequests()->latest();

        // Jika ada input pencarian
        if ($search) {
            // Coba parsing input sebagai tanggal
            try {
                $searchDate = Carbon::parse($search)->toDateString();
                // Jika berhasil, cari berdasarkan tanggal pembuatan
                $query->whereDate('created_at', $searchDate);
            } catch (\Exception $e) {
                // ===================================
                // PERBAIKAN UTAMA DI SINI
                // ===================================
                // Jika gagal (bukan tanggal), cari di NAMA atau KOTA
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
                });
                // ===================================
                // AKHIR PERBAIKAN
                // ===================================
            }
        }

        // Eksekusi query dengan pagination
        $myRequests = $query->paginate(10)->withQueryString();

        return view('user.requests.history', compact('myRequests'));
    }

    public function edit(InstallationRequest $request)
    {
        // Otorisasi: Pastikan user hanya bisa mengedit permintaannya sendiri
        // DAN statusnya masih 'Pending'.
        if ($request->user_id !== Auth::id() || $request->status !== 'Pending') {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        return view('user.requests.edit', compact('request'));
    }

    /**
     * Memperbarui permintaan yang ada di database.
     */
    public function update(Request $requestData, InstallationRequest $request)
    {
        // Otorisasi
        if ($request->user_id !== Auth::id() || $request->status !== 'Pending') {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        $validatedData = $requestData->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'customer_phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'daily_energy_wh' => 'required|integer|min:1',
        ]);

        $request->update($validatedData);

        return redirect()->route('requests.history')->with('success', 'Permintaan Anda berhasil diperbarui!');
    }

    public function show(InstallationRequest $request)
    {
        // Otorisasi: Pastikan user hanya bisa melihat permintaannya sendiri.
        if ($request->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        return view('user.requests.show', compact('request'));
    }

    /**
     * Menghapus (membatalkan) permintaan.
     */
    public function destroy(InstallationRequest $request)
    {
        // Otorisasi
        if ($request->user_id !== Auth::id() || $request->status !== 'Pending') {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        $request->delete();

        return redirect()->route('requests.history')->with('success', 'Permintaan Anda telah berhasil dibatalkan.');
    }
}