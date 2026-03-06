<x-app-layout>

<x-slot name="header">
    👤 Jenis Tamu
</x-slot>

<div class="bg-white rounded-2xl shadow-lg p-10">

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-700 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM -->
    <div class="mb-10">
        <h3 class="text-xl font-bold mb-4">Tambah Jenis Tamu</h3>

        <form action="{{ url('/jenis-tamu/store') }}" method="POST" class="flex gap-4">
            @csrf

            <input type="text"
                   name="nama_jenis"
                   placeholder="Masukkan nama jenis tamu..."
                   class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                   required>

            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-xl font-semibold">
                ➕ Tambah
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full text-lg border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 border text-center w-20">No</th>
                    <th class="p-4 border">Nama Jenis</th>
                    <th class="p-4 border text-center w-48">Tanggal</th>
                    <th class="p-4 border text-center w-48">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisTamus as $jt)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 border text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-4 border">
                        {{ $jt->nama_jenis }}
                    </td>

                    <td class="p-4 border text-center">
                        {{ $jt->created_at->format('d M Y') }}
                    </td>

                    <td class="p-4 border text-center space-x-2">

                        <!-- EDIT -->
                        <button onclick="openModal({{ $jt->id_jenis_tamu }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                            Edit
                        </button>

                        <!-- DELETE -->
                        <form action="{{ url('/jenis-tamu/destroy/'.$jt->id_jenis_tamu) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">
                        Belum ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


{{-- ================= MODAL EDIT ================= --}}
@foreach($jenisTamus as $jt)
<div id="modal{{ $jt->id_jenis_tamu }}"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6">

        <h2 class="text-xl font-bold mb-4">Edit Jenis Tamu</h2>

        <form action="{{ url('/jenis-tamu/update/'.$jt->id_jenis_tamu) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="block mb-2 font-medium">Nama Jenis Tamu</label>
            <input type="text"
                   name="nama_jenis"
                   value="{{ $jt->nama_jenis }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-6 focus:ring-2 focus:ring-indigo-500"
                   required>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal({{ $jt->id_jenis_tamu }})"
                        class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">
                    Batal
                </button>

                <button class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>
@endforeach


{{-- ================= SCRIPT MODAL ================= --}}
<script>
function openModal(id) {
    const modal = document.getElementById('modal' + id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(id) {
    const modal = document.getElementById('modal' + id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

</x-app-layout>