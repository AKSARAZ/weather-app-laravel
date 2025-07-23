@extends('layouts.app')

@section('header_title', 'Detail Permintaan #' . $request->id)

@section('content')
    <div class="card max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="flex justify-between items-start border-b pb-4 mb-6">
            <!-- Bagian Judul (fleksibel) -->
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Permintaan dari {{ $request->customer_name }}</h2>
                <p class="text-gray-500 text-sm mt-1">Diajukan oleh: {{ $request->user->name }} ({{ $request->user->email }}) pada {{ $request->created_at->format('d M Y') }}</p>
            </div>
            <!-- Bagian Link Kembali (tidak akan mengecil) -->
            <div class="ml-4 flex-shrink-0">
                <a href="{{ route('admin.requests.index') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 whitespace-nowrap">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Kembali ke Daftar Request
                </a>
            </div>
        </div>

        <!-- Konten Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Kolom Informasi Pelanggan -->
            <div class="space-y-4">
                <h3 class="font-bold text-lg text-gray-700 border-b pb-2">Informasi Pelanggan</h3>
                <div class="text-sm">
                    <p class="text-gray-500">Nama Lengkap</p>
                    <p class="font-semibold text-gray-800 text-base">{{ $request->customer_name }}</p>
                </div>
                <div class="text-sm">
                    <p class="text-gray-500">Alamat Pemasangan</p>
                    <p class="font-semibold text-gray-800 text-base">{{ $request->customer_address }}</p>
                </div>
                <div class="text-sm">
                    <p class="text-gray-500">Nomor Telepon</p>
                    <p class="font-semibold text-gray-800 text-base">{{ $request->customer_phone }}</p>
                </div>
                <div class="text-sm">
                    <p class="text-gray-500">Kota / Kabupaten</p>
                    <p class="font-semibold text-gray-800 text-base">{{ $request->city }}</p>
                </div>
                <div class="text-sm pt-2">
                    <p class="text-gray-500">Kebutuhan Energi Harian</p>
                    <p class="font-bold text-blue-600 text-xl">{{ number_format($request->daily_energy_wh) }} Wh</p>
                </div>
            </div>

            <!-- Kolom Status & Rekomendasi Admin -->
            <div class="space-y-4">
                <h3 class="font-bold text-lg text-gray-700 border-b pb-2">Status & Rekomendasi</h3>
                <div class="text-sm">
                    <p class="text-gray-500">Status Saat Ini</p>
                    <p class="font-semibold text-gray-800 text-base">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($request->status == 'Pending') bg-yellow-100 text-yellow-800 @endif
                            @if($request->status == 'Processed') bg-blue-100 text-blue-800 @endif
                            @if($request->status == 'Completed') bg-green-100 text-green-800 @endif">
                            {{ $request->status }}
                        </span>
                    </p>
                </div>
                
                @if($request->status != 'Pending')
                <div class="bg-blue-50 p-4 rounded-lg space-y-3">
                     <div class="text-sm">
                        <p class="text-gray-500">Rekomendasi Kapasitas Panel</p>
                        <p class="font-bold text-blue-800 text-base">{{ number_format($request->recommended_panel_wp ?? 0) }} Wp</p>
                    </div>
                     <div class="text-sm">
                        <p class="text-gray-500">Catatan dari Admin</p>
                        <p class="font-semibold text-gray-800 italic">"{{ $request->admin_notes ?? 'Tidak ada catatan.' }}"</p>
                    </div>
                </div>
                @else
                 <div class="bg-gray-50 p-4 rounded-lg text-sm text-center text-gray-600">
                    Permintaan ini belum diproses.
                </div>
                @endif
            </div>
        </div>

        <!-- Tombol Aksi di Bagian Bawah -->
        <div class="mt-6 border-t pt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.requests.edit', $request->id) }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-lg transition-all text-sm">
                Edit Permintaan Ini
            </a>
        </div>
    </div>
@endsection