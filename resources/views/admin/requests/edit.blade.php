@extends('layouts.app')

@section('header_title', 'Proses Permintaan #' . $request->id)

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kolom Detail Permintaan -->
        <div class="md:col-span-1 card">
            <h3 class="text-lg font-bold text-gray-800">Detail Permintaan</h3>
            <p class="text-sm text-gray-500 mb-4">Informasi dari pelanggan.</p>
            <div class="space-y-2 text-sm">
                <p><strong>Nama:</strong> {{ $request->customer_name }}</p>
                <p><strong>Alamat:</strong> {{ $request->customer_address }}</p>
                <p><strong>Telepon:</strong> {{ $request->customer_phone }}</p>
                <p><strong>Kota:</strong> {{ $request->city }}</p>
                <p class="text-lg mt-2"><strong>Kebutuhan Energi:</strong> <span class="font-bold text-blue-600">{{ number_format($request->daily_energy_wh) }} Wh/hari</span></p>
            </div>
        </div>

        <!-- Kolom Form Admin -->
        <div class="md:col-span-2 card">
            <form action="{{ route('admin.requests.update', $request->id) }}" method="POST">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-bold text-gray-800">Form Prediksi & Update</h3>
                <p class="text-sm text-gray-500 mb-6">Hitung rekomendasi dan ubah status permintaan.</p>
                
                <div class="space-y-4">
                    <div>
                        <label for="yearly_psh" class="block text-sm font-medium text-gray-700">Peak Sun Hour (PSH) Tahunan</label>
                        <input type="number" name="yearly_psh" id="yearly_psh" step="0.1" value="{{ old('yearly_psh', 4.5) }}" class="mt-1 w-full p-2 border border-gray-300 rounded-md">
                        @error('yearly_psh') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                     @if($request->recommended_panel_wp)
                        <div class="bg-blue-50 p-3 rounded-md">
                            <p class="text-sm font-medium text-blue-800">Rekomendasi Tersimpan: <span class="font-bold text-blue-900">{{ number_format($request->recommended_panel_wp) }} Wp</span></p>
                        </div>
                    @endif
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status Permintaan</label>
                        <select id="status" name="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            <option value="Pending" @if($request->status == 'Pending') selected @endif>Pending</option>
                            <option value="Processed" @if($request->status == 'Processed') selected @endif>Processed</option>
                            <option value="Completed" @if($request->status == 'Completed') selected @endif>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md">{{ old('admin_notes', $request->admin_notes) }}</textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Simpan & Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection