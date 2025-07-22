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
}