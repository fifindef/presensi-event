<x-app-layout>

<x-slot name="header">
    📅 Manajemen Event
</x-slot>

<div class="bg-white rounded-2xl shadow-lg p-10">

    {{-- HEADER SECTION --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold">Kelola Event</h2>
            <p class="text-gray-500 mt-1">Atur seluruh event yang tersedia di sistem</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ url('/event-hub') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow">
                🚀 Join Event
            </a>

            <a href="{{ url('/event/create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow">
                ➕ Tambah Event
            </a>
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-700 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- TOTAL --}}
    <div class="mb-6 text-lg font-semibold">
        Total Event: {{ count($events) }}
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-lg border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 border text-center w-20">No</th>
                    <th class="p-4 border">Nama Event</th>
                    <th class="p-4 border text-center w-40">Tanggal</th>
                    <th class="p-4 border text-center w-32">Jam</th>
                    <th class="p-4 border">Lokasi</th>
                    <th class="p-4 border">Kategori</th>
                    <th class="p-4 border text-center w-56">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $e)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 border text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-4 border font-semibold">
                        {{ $e->nama_event }}
                    </td>

                    <td class="p-4 border text-center">
                        {{ \Carbon\Carbon::parse($e->tanggal)->format('d M Y') }}
                    </td>

                    <td class="p-4 border text-center">
                        {{ $e->jam }}
                    </td>

                    <td class="p-4 border">
                        {{ $e->lokasi }}
                    </td>

                    <td class="p-4 border">
                        {{ $e->kategori->nama_kategori ?? '-' }}
                    </td>

                    <td class="p-4 border text-center space-x-2">

                        {{-- EDIT --}}
                        <button onclick="confirmEdit({{ $e->id_event }}, '{{ $e->access_code }}')"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                            Edit
                        </button>

                        {{-- DELETE --}}
                        <form action="{{ url('/event/delete/'.$e->id_event) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirmDelete(event, '{{ $e->access_code }}')">
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
                    <td colspan="7" class="p-6 text-center text-gray-500">
                        Belum ada event
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


{{-- ================= MODAL EDIT ================= --}}
@foreach($events as $e)
<div id="modal{{ $e->id_event }}"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6">

        <h2 class="text-xl font-bold mb-4">Edit Event</h2>

        <form action="{{ url('/event/update/'.$e->id_event) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-2 font-medium">Nama Event</label>
                <input type="text"
                       name="nama_event"
                       value="{{ $e->nama_event }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 font-medium">Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           value="{{ $e->tanggal }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Jam</label>
                    <input type="time"
                           name="jam"
                           value="{{ $e->jam }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Lokasi</label>
                <input type="text"
                       name="lokasi"
                       value="{{ $e->lokasi }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-medium">Kategori</label>
                <select name="id_kategori"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id_kategori }}"
                            {{ $k->id_kategori == $e->id_kategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal({{ $e->id_event }})"
                        class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">
                    Batal
                </button>

                <button class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                    Update
                </button>
            </div>

        </form>

    </div>
</div>
@endforeach


<script>
function confirmEdit(id, correctCode) {
    let input = prompt("Masukkan Access Code untuk mengedit event ini:");

    if (input === null) return;

    if (input === correctCode) {
        openModal(id);
    } else {
        alert("Access Code salah!");
    }
}

function confirmDelete(e, correctCode) {
    e.preventDefault();

    let input = prompt("Masukkan Access Code untuk menghapus event ini:");

    if (input === null) return false;

    if (input === correctCode) {
        if (confirm("Yakin ingin menghapus event ini?")) {
            e.target.submit();
        }
    } else {
        alert("Access Code salah!");
    }

    return false;
}

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