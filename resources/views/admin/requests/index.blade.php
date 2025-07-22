@extends('layouts.app')

@section('header_title', 'Daftar Semua Permintaan')

@section('content')
    <div class="card">
         @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kebutuhan Energi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($allRequests as $req)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $req->customer_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $req->city }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($req->daily_energy_wh) }} Wh</td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $req->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : ($req->status == 'Processed' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">{{ $req->status }}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="{{ route('admin.requests.edit', $req->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada permintaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $allRequests->links() }}
        </div>
    </div>
@endsection