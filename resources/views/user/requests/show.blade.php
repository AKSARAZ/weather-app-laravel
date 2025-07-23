@extends('layouts.app')

@section('header_title', 'Detail Permintaan Pemasangan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="card">
            <!-- Header Card -->
            <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b pb-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Permintaan #{{ $request->id }}</h2>
                    <p class="text-gray-500">Detail untuk permintaan pemasangan di {{ $request->city }}.</p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <a href="{{ route('requests.history') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">← Kembali ke Riwayat</a>
                </div>
            </div>

            <!-- Konten Detail -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Informasi Pelanggan -->
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-700">Informasi Pelanggan</h3>
                    <div class="text-sm">
                        <p class="text-gray-500">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $request->customer_name }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-500">Alamat Pemasangan</p>
                        <p class="font-semibold text-gray-800">{{ $request->customer_address }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-500">Nomor Telepon</p>
                        <p class="font-semibold text-gray-800">{{ $request->customer_phone }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-500">Kebutuhan Energi</p>
                        <p class="font-semibold text-gray-800">{{ number_format($request->daily_energy_wh) }} Wh/hari</p>
                    </div>
                </div>

                <!-- Kolom Status & Rekomendasi Admin -->
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-700">Status & Rekomendasi</h3>
                    <div class="text-sm">
                        <p class="text-gray-500">Status Saat Ini</p>
                        <p class="font-semibold text-gray-800">
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
                     <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-600">
                        Rekomendasi dari tim kami akan muncul di sini setelah permintaan Anda diproses.
                    </div>
                    @endif
                </div>
            </div>

            <!-- ============================================= -->
            <!-- BAGIAN KUNCI: TOMBOL AKSI HANYA JIKA 'PENDING' -->
            <!-- ============================================= -->
            @if($request->status == 'Pending')
            <div class="mt-6 border-t pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('requests.edit', $request->id) }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg transition-all text-sm">
                    Edit Permintaan
                </a>
                <form action="{{ route('requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permintaan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all text-sm">
                        Batalkan Permintaan
                    </button>
                </form>
            </div>
            @endif
            <!-- ========================== -->
            <!-- AKHIR BAGIAN KUNCI -->
            <!-- ========================== -->

        </div>
    </div>
@endsection