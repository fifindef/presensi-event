<x-app-layout>

<x-slot name="header">
    📂 Kategori Event
</x-slot>

<div class="bg-white rounded-2xl shadow-lg p-10">

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-700 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM -->
    <div class="mb-10">
        <h3 class="text-xl font-bold mb-4">Tambah Kategori</h3>

        <form action="{{ url('kategori-event') }}" method="POST" class="flex gap-4">
            @csrf

            <input type="text"
                   name="nama_kategori"
                   placeholder="Masukkan nama kategori..."
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
                    <th class="p-4 border">Nama Kategori</th>
                    <th class="p-4 border text-center w-48">Tanggal</th>
                    <th class="p-4 border text-center w-48">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $k)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 border text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-4 border">
                        {{ $k->nama_kategori }}
                    </td>

                    <td class="p-4 border text-center">
                        {{ $k->created_at->format('d M Y') }}
                    </td>

                    <td class="p-4 border text-center space-x-2">

                        <!-- EDIT -->
                        <button onclick="openModal({{ $k->id_kategori }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                            Edit
                        </button>

                        <!-- DELETE -->
                        <form action="{{ url('kategori-event/'.$k->id_kategori) }}"
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
@foreach($kategori as $k)
<div id="modal{{ $k->id_kategori }}"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6">

        <h2 class="text-xl font-bold mb-4">Edit Kategori Event</h2>

        <form action="{{ url('kategori-event/'.$k->id_kategori) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="block mb-2 font-medium">Nama Kategori</label>
            <input type="text"
                name="nama_kategori"
                value="{{ $k->nama_kategori }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-6 focus:ring-2 focus:ring-indigo-500"
                required>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal({{ $k->id_kategori }})"
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