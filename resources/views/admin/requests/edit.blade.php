<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Proses Permintaan #{{ $request->id }} - {{ $request->customer_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Kolom Detail Permintaan (Read-only) -->
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Detail Permintaan Pelanggan</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Informasi ini disediakan oleh pelanggan.
                    </p>
                </div>
                <div class="bg-white shadow sm:rounded-lg mt-4 p-6">
                    <p><strong>Nama:</strong> {{ $request->customer_name }}</p>
                    <p><strong>Alamat:</strong> {{ $request->customer_address }}</p>
                    <p><strong>Telepon:</strong> {{ $request->customer_phone }}</p>
                    <p><strong>Kota:</strong> {{ $request->city }}</p>
                    <p class="text-lg mt-2"><strong>Kebutuhan Energi:</strong> <span class="font-bold text-blue-600">{{ number_format($request->daily_energy_wh) }} Wh/hari</span></p>
                </div>
            </div>

            <!-- Kolom Form Admin -->
            <div class="md:col-span-2">
                <form action="{{ route('admin.requests.update', $request->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Form Prediksi & Update Status</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Masukkan PSH tahunan untuk kota ini untuk menghitung rekomendasi dan mengubah status permintaan.
                                </p>
                            </div>

                            <!-- Input Peak Sun Hour -->
                            <div>
                                <label for="yearly_psh" class="block text-sm font-medium text-gray-700">Peak Sun Hour (PSH) Tahunan</label>
                                <input type="number" name="yearly_psh" id="yearly_psh" step="0.1" value="{{ old('yearly_psh', 4.5) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-2 text-xs text-gray-500">Masukkan rata-rata jam penyinaran matahari efektif per hari untuk kota <span class="font-bold">{{ $request->city }}</span>.</p>
                                @error('yearly_psh') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Hasil Prediksi (Read-only) -->
                            @if($request->recommended_panel_wp)
                                <div class="bg-blue-50 p-4 rounded-md">
                                    <p class="text-sm font-medium text-blue-800">Rekomendasi Sebelumnya:</p>
                                    <p class="text-xl font-bold text-blue-900">{{ number_format($request->recommended_panel_wp) }} Wp</p>
                                </div>
                            @endif

                            <!-- Ubah Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status Permintaan</label>
                                <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="Pending" @if($request->status == 'Pending') selected @endif>Pending</option>
                                    <option value="Processed" @if($request->status == 'Processed') selected @endif>Processed</option>
                                    <option value="Completed" @if($request->status == 'Completed') selected @endif>Completed</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Catatan Admin -->
                            <div>
                                <label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                <textarea id="admin_notes" name="admin_notes" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('admin_notes', $request->admin_notes) }}</textarea>
                                @error('admin_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan & Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>