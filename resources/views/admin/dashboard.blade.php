@extends('layouts.app')

@section('header_title', 'Dashboard Admin')

@section('content')
    <div class="card">
        <h2 class="text-xl font-bold text-gray-800 mb-4">5 Permintaan Terbaru</h2>
        <div class="space-y-4">
            @forelse ($latestRequests as $request)
                <div class="border rounded-lg p-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $request->customer_name }} - <span class="font-normal text-gray-600">{{ $request->city }}</span></p>
                        <p class="text-sm text-gray-500">Kebutuhan: {{ number_format($request->daily_energy_wh) }} Wh/hari</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $request->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $request->status }}
                        </span>
                        <a href="{{ route('admin.requests.edit', $request->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Lihat & Proses</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Belum ada permintaan yang masuk.</p>
            @endforelse
        </div>
    </div>
@endsection