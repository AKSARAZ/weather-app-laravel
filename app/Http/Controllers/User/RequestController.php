<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstallationRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function dashboard()
    {
        // Ambil riwayat permintaan milik user yang sedang login
        $myRequests = Auth::user()->installationRequests()->latest()->get();
        return view('dashboard', compact('myRequests'));
    }

    public function create()
    {
        // Kita perlu membuat file view untuk ini nanti
        return view('user.requests.create'); 
    }

    public function store(Request $request)
    {
        // Simpan hasil validasi ke dalam variabel
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'customer_phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'daily_energy_wh' => 'required|integer|min:1',
        ]);

        // Gunakan HANYA data yang sudah divalidasi
        Auth::user()->installationRequests()->create($validatedData); 

        return redirect()->route('dashboard')->with('success', 'Permintaan instalasi Anda telah berhasil dikirim!');
    }
}