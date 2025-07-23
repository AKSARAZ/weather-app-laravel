@extends('layouts.app')

@section('header_title', 'Status Pemasangan PLTS Anda')

@section('content')
    <div class="card">
        <!-- Header Card -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-start mb-6">
            <!-- Judul dan Subjudul -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Permintaan</h2>
                <p class="text-gray-500 mt-1">Pantau status semua permintaan pemasangan PLTS Anda di sini. Klik pada salah satu permintaan untuk melihat detail lengkap.</p>
            </div>

            <!-- Tombol Tambah Pengajuan -->
            @if($myRequests->isNotEmpty() && !request('search'))
                <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0">
                    <a href="{{ route('request.create') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-md transition-all">
                        <i class="fa-solid fa-plus mr-2"></i>
                        <span>Tambah Pengajuan Baru</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- ====================================================== -->
        <!-- TEMPELKAN BLOK KODE UNTUK USER DI SINI -->
        <!-- ====================================================== -->
        @if(request('search') || request('search_date'))
        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4 mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fa-solid fa-search mr-3"></i>
                <span>
                    Hasil pencarian untuk
                    
                    @if(request('search'))
                        <strong class="font-semibold">"{{ request('search') }}"</strong>
                    @endif

                    @if(request('search') && request('search_date'))
                        <span class="text-gray-600 mx-1">dan</span>
                    @endif
                    
                    @if(request('search_date'))
                        tanggal <strong class="font-semibold">{{ \Carbon\Carbon::parse(request('search_date'))->format('d F Y') }}</strong>
                    @endif

                    <span class="text-gray-600 ml-2">({{ $myRequests->total() }} hasil ditemukan)</span>
                </span>
            </div>
            <a href="{{ route('requests.history') }}" class="flex items-center text-sm font-semibold border border-blue-500 text-blue-600 rounded-md px-3 py-1 hover:bg-blue-100">
                <i class="fa-solid fa-times mr-2"></i>
                Reset
            </a>
        </div>
        @endif
        <!-- ====================================================== -->
        <!-- AKHIR BLOK -->
        <!-- ====================================================== -->

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($myRequests as $request)
                <!-- SETIAP ITEM ADALAH LINK KE HALAMAN DETAIL (requests.show) -->
                <a href="{{ route('requests.show', $request->id) }}" class="block border rounded-lg p-4 sm:p-6 hover:bg-gray-50 transition-colors duration-200">
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
                </a>
            @empty
                <!-- Tampilan jika tidak ada hasil pencarian ATAU tidak ada riwayat sama sekali -->
                <div class="text-center py-16 border-dashed border-2 rounded-lg">
                    <i class="fa-solid fa-file-circle-question fa-3x text-gray-400 mb-4"></i>
                    @if(request('search'))
                        <h3 class="text-xl font-bold text-gray-800">Tidak Ada Hasil Ditemukan</h3>
                        <p class="text-gray-500 mt-2">Tidak ada permintaan yang cocok dengan kata kunci "{{ request('search') }}".</p>
                        <a href="{{ route('requests.history') }}" class="mt-6 inline-block text-blue-600 hover:underline font-semibold">
                            Tampilkan Semua Riwayat
                        </a>
                    @else
                        <h3 class="text-xl font-bold text-gray-800">Anda Belum Memiliki Permintaan</h3>
                        <p class="text-gray-500 mt-2">Mulai dengan mengajukan permintaan pemasangan PLTS pertama Anda.</p>
                        <a href="{{ route('request.create') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all">
                            Buat Pengajuan Pemasangan
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Tampilkan link pagination jika data lebih dari 10 -->
        <div class="mt-6">
            {{ $myRequests->links() }}
        </div>
    </div>
@endsection