<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Tambah Event Baru</h1>
                <p class="text-gray-500 mt-1">Buat event baru dan atur detailnya dengan lengkap.</p>
            </div>

            <a href="{{ url('/event') }}"
               class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition">
                ← Kembali
            </a>
        </div>

        <!-- Card Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl">
                    <ul class="text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/event/store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Event -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Event
                    </label>
                    <input type="text"
                           name="nama_event"
                           value="{{ old('nama_event') }}"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>

                <!-- Tanggal & Jam -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal
                        </label>
                        <input type="date"
                               name="tanggal"
                               value="{{ old('tanggal') }}"
                               required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Jam
                        </label>
                        <input type="time"
                               name="jam"
                               value="{{ old('jam') }}"
                               required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Lokasi
                    </label>
                    <input type="text"
                           name="lokasi"
                           value="{{ old('lokasi') }}"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kategori Event
                    </label>
                    <select name="id_kategori"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Access Code -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Access Code (Opsional)
                    </label>
                    <input type="text"
                           name="access_code"
                           value="{{ old('access_code') }}"
                           placeholder="Kosongkan jika tidak diperlukan"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    <p class="text-xs text-gray-400 mt-1">
                        Digunakan untuk join event dan konfirmasi edit/hapus.
                    </p>
                </div>

                <!-- Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-md transition">
                        💾 Simpan Event
                    </button>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>