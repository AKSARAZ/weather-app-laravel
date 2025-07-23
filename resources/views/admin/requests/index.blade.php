@extends('layouts.app')

@section('header_title', 'Daftar Semua Permintaan')

@section('content')
    <div class="card">
        <!-- ==================================== -->
        <!-- MULAI BLOK HASIL PENCARIAN BARU -->
        <!-- ==================================== -->
        @if(request('search'))
        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4 mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fa-solid fa-search mr-3"></i>
                <span>
                    Hasil pencarian untuk: <strong class="font-semibold">{{ request('search') }}</strong>
                    <span class="text-gray-600">({{ $allRequests->total() }} hasil ditemukan)</span>
                </span>
            </div>
            <a href="{{ route('admin.requests.index') }}" class="flex items-center text-sm font-semibold border border-blue-500 text-blue-600 rounded-md px-3 py-1 hover:bg-blue-100">
                <i class="fa-solid fa-times mr-2"></i>
                Reset
            </a>
        </div>
        @endif
        <!-- ================================== -->
        <!-- SELESAI BLOK HASIL PENCARIAN -->
        <!-- ================================== -->
        
        @if(session('success'))
            <!-- ... (pesan sukses Anda) ... -->
        @endif

        <!-- ... (sisa kode tabel Anda) ... -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert"><p>{{ session('success') }}</p></div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert"><p>{{ session('error') }}</p></div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($allRequests as $req)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $req->customer_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $req->city }}</td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $req->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : ($req->status == 'Processed' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">{{ $req->status }}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.requests.show', $req->id) }}" class="text-gray-600 hover:text-gray-900 mr-4">Detail</a>
                            <a href="{{ route('admin.requests.edit', $req->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                            
                            <!-- Tombol Hapus dengan Kondisi & Validasi -->
                            @if($req->status === 'Completed')
                            <form action="{{ route('admin.requests.destroy', $req->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada permintaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $allRequests->links() }}
        </div>
    </div>
@endsection