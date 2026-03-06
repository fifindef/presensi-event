<x-app-layout>

<div class="max-w-7xl mx-auto py-10 px-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Tamu</h1>
            <p class="text-gray-500 mt-1">
                Kelola daftar tamu berdasarkan event yang sedang aktif.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-green-50 text-green-700 border border-green-200 text-base">
            {{ session('success') }}
        </div>
    @endif


    <!-- FORM TAMBAH TAMU -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-12">

        <h3 class="text-xl font-semibold text-gray-800 mb-6">
            Tambah Tamu
        </h3>

        <form action="{{ url('/tamu/store') }}" method="POST" class="grid md:grid-cols-3 gap-6">
            @csrf

            <div>
                <label class="block text-base font-semibold text-gray-700 mb-2">
                    Nama Tamu
                </label>
                <input type="text"
                       name="nama_tamu"
                       required
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition text-base">
            </div>

            <div>
                <label class="block text-base font-semibold text-gray-700 mb-2">
                    Nomor HP
                </label>
                <input type="text"
                       name="nomor_hp"
                       required
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition text-base">
            </div>

            <div>
                <label class="block text-base font-semibold text-gray-700 mb-2">
                    Jenis Tamu
                </label>
                <select name="id_jenis_tamu"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition text-base">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($jenisTamus as $j)
                        <option value="{{ $j->id_jenis_tamu }}">
                            {{ $j->nama_jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 pt-4">
                <button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-md transition text-base">
                    💾 Simpan Tamu
                </button>
            </div>

        </form>
    </div>



    <!-- TABEL TAMU -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

        <div class="px-8 py-5 border-b border-gray-100">
            <h3 class="text-xl font-semibold text-gray-800">
                Daftar Tamu
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-base text-gray-700">

                <thead class="bg-gray-50 text-gray-800 uppercase tracking-wider text-sm">
                    <tr>
                        <th class="px-8 py-5 text-center w-20">No</th>
                        <th class="px-8 py-5 text-left">Nama</th>
                        <th class="px-8 py-5 text-center">Nomor HP</th>
                        <th class="px-8 py-5 text-center">Jenis</th>
                        <th class="px-8 py-5 text-center w-80">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                @forelse($tamus as $i => $t)
                    <tr class="hover:bg-gray-50 transition duration-200">

                        <td class="px-8 py-6 text-center font-medium">
                            {{ $i+1 }}
                        </td>

                        <td class="px-8 py-6 font-semibold text-gray-900">
                            {{ $t->nama_tamu }}
                        </td>

                        <td class="px-8 py-6 text-center">
                            {{ $t->nomor_hp }}
                        </td>

                        <td class="px-8 py-6 text-center">
                            <span class="px-4 py-1.5 text-sm font-semibold bg-indigo-100 text-indigo-700 rounded-full">
                                {{ $t->jenisTamu->nama_jenis ?? '-' }}
                            </span>
                        </td>

                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center gap-4">

                                <!-- EDIT -->
                                <button onclick="openModal({{ $t->id_tamu }})"
                                    class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                                    ✏ Edit
                                </button>

                                <!-- DELETE -->
                                <form action="{{ url('/tamu/delete/'.$t->id_tamu) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus tamu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                                        🗑 Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>


                    <!-- MODAL EDIT -->
                    <div id="modal{{ $t->id_tamu }}"
                         class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">

                            <h3 class="text-xl font-semibold mb-6">
                                Edit Tamu
                            </h3>

                            <form action="{{ url('/tamu/update/'.$t->id_tamu) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-5">

                                    <div>
                                        <label class="block text-base font-semibold mb-2">
                                            Nama Tamu
                                        </label>
                                        <input type="text"
                                               name="nama_tamu"
                                               value="{{ $t->nama_tamu }}"
                                               required
                                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 text-base">
                                    </div>

                                    <div>
                                        <label class="block text-base font-semibold mb-2">
                                            Nomor HP
                                        </label>
                                        <input type="text"
                                               name="nomor_hp"
                                               value="{{ $t->nomor_hp }}"
                                               required
                                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 text-base">
                                    </div>

                                    <div>
                                        <label class="block text-base font-semibold mb-2">
                                            Jenis Tamu
                                        </label>
                                        <select name="id_jenis_tamu"
                                                required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 text-base">
                                            @foreach($jenisTamus as $j)
                                                <option value="{{ $j->id_jenis_tamu }}"
                                                    {{ $t->id_jenis_tamu == $j->id_jenis_tamu ? 'selected' : '' }}>
                                                    {{ $j->nama_jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="flex justify-end gap-4 mt-8">
                                    <button type="button"
                                            onclick="closeModal({{ $t->id_tamu }})"
                                            class="px-5 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg text-base">
                                        Batal
                                    </button>

                                    <button class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-base font-semibold">
                                        💾 Simpan
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400 text-lg">
                            Belum ada tamu
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>

</div>


<script>
function openModal(id) {
    document.getElementById('modal'+id).classList.remove('hidden');
    document.getElementById('modal'+id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById('modal'+id).classList.add('hidden');
}
</script>

</x-app-layout>