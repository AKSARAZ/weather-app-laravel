@extends('layouts.app')

@section('header_title', 'Status Pemasangan PLTS Anda')

@section('content')
    <div class="card">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Riwayat Permintaan</h2>
                <p class="text-gray-600">Pantau status semua permintaan pemasangan PLTS Anda di sini.</p>
            </div>

            <!-- TOMBOL KONDISIONAL: TAMBAH PENGAJUAN -->
            @if($myRequests->isNotEmpty())
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('request.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all text-sm">
                        <i class="fa-solid fa-plus mr-2"></i>Tambah Pengajuan Baru
                    </a>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($myRequests as $request)
                <div class="border rounded-lg p-4 sm:p-6">
                    <!-- ... (Konten detail permintaan tetap sama) ... -->
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                        <div>
                            <p class="font-bold text-lg text-gray-800">Permintaan di {{ $request->city }}</p>
                            <p class="text-sm text-gray-500">Diajukan pada: {{ $request->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($request->status == 'Pending') bg-yellow-100 text-yellow-800 @endif
                                @if($request->status == 'Processed') bg-blue-100 text-blue-800 @endif
                                @if($request->status == 'Completed') bg-green-100 text-green-800 @endif">
                                {{ $request->status }}
                            </span>
                        </div>
                    </div>
                    @if($request->status != 'Pending')
                    <div class="mt-4 border-t pt-4">
                        <!-- ... (Konten detail rekomendasi admin tetap sama) ... -->
                    </div>
                    @endif

                    <!-- ======================================================= -->
                    <!-- BAGIAN BARU: TOMBOL AKSI KONDISIONAL (EDIT & HAPUS) -->
                    <!-- ======================================================= -->
                    @if($request->status == 'Pending')
                    <div class="mt-4 border-t pt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('requests.edit', $request->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</a>
                        <form action="{{ route('requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permintaan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Batalkan</button>
                        </form>
                    </div>
                    @endif
                    <!-- ========================== -->
                    <!-- AKHIR BAGIAN BARU -->
                    <!-- ========================== -->
                </div>
            @empty
                <!-- KONDISI JIKA BELUM ADA REQUEST SAMA SEKALI -->
                <div class="text-center py-16 border-dashed border-2 rounded-lg">
                    <i class="fa-solid fa-file-circle-question fa-3x text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800">Anda Belum Memiliki Permintaan</h3>
                    <p class="text-gray-500 mt-2">Mulai dengan mengajukan permintaan pemasangan PLTS pertama Anda.</p>
                    <a href="{{ route('request.create') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all">
                        Buat Pengajuan Pemasangan
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $myRequests->links() }}
        </div>
    </div>
@endsection