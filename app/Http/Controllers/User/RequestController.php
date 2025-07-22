<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InstallationRequest; // <-- Import model

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

    public function history()
    {
        // Ambil semua permintaan milik pengguna yang sedang login,
        // urutkan dari yang terbaru, dan gunakan paginate.
        $myRequests = Auth::user()
                            ->installationRequests()
                            ->latest()
                            ->paginate(10);

        // Tampilkan view baru dan kirim data permintaan
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