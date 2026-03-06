<x-app-layout>

<div class="max-w-7xl mx-auto py-10 px-6">

    <!-- HEADER -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-800">
            Manajemen Presensi
        </h1>
        <p class="text-gray-500 mt-1">
            Kelola undangan, QR Code, dan daftar presensi tamu.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-green-50 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if($events->isEmpty())
        <div class="bg-red-50 border border-red-200 text-red-700 p-6 rounded-xl">
            ⚠️ Anda belum memiliki akses ke event manapun.
        </div>
    @else

    <!-- ================= FORM ================= -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-12">

        <h3 class="text-xl font-semibold text-gray-800 mb-6">
            Tambah Undangan / Presensi
        </h3>

        <form action="{{ route('presensi.store') }}" method="POST"
              class="grid md:grid-cols-3 gap-6">
            @csrf

            <!-- EVENT -->
            <div>
                <label class="block font-semibold mb-2">Event</label>
                <select name="id_event"
                        id="event-form"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    @foreach($events as $event)
                        <option value="{{ $event->id_event }}"
                            {{ $selectedEventId == $event->id_event ? 'selected' : '' }}>
                            {{ $event->nama_event }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- TAMU DROPDOWN -->
            <div>
                <label class="block font-semibold mb-2">Tamu</label>
                <select name="id_tamu"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Tamu --</option>
                    @foreach($tamus as $tamu)
                        <option value="{{ $tamu->id_tamu }}">
                            {{ $tamu->nama_tamu }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 pt-4">
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-md">
                    🎫 Buat Undangan + QR
                </button>
            </div>
        </form>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

        <div class="px-8 py-5 border-b border-gray-100">
            <h3 class="text-2xl font-bold text-gray-800">
                Daftar Presensi
            </h3>
        </div>

        <div class="p-8">

            <!-- CONTROL -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">

                <!-- SHOW ENTRIES -->
                <div class="flex items-center gap-3">
                    <span class="text-gray-600 text-sm">Show</span>

                    <select id="entries-select"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>

                    <span class="text-gray-600 text-sm">entries</span>
                </div>

                <!-- FILTER EVENT -->
                <div>
                    <select id="filter-event"
                            class="px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                        @foreach($events as $event)
                            <option value="{{ $event->id_event }}"
                                {{ $selectedEventId == $event->id_event ? 'selected' : '' }}>
                                {{ $event->nama_event }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto border rounded-xl">

                <table class="min-w-full text-lg text-gray-700">

                    <thead class="bg-gray-100 text-gray-800 uppercase text-sm tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center">No</th>
                            <th class="px-6 py-4 text-center">Nama Event</th>
                            <th class="px-6 py-4 text-center">Nama Tamu</th>
                            <th class="px-6 py-4 text-center">Kode Unik</th>
                            <th class="px-6 py-4 text-center">QR Code</th>
                            <th class="px-6 py-4 text-center w-64">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="presensi-body"
                           class="divide-y divide-gray-200 text-center font-medium">
                    </tbody>

                </table>
            </div>

            <div id="table-info"
                 class="mt-4 text-sm text-gray-600">
            </div>

        </div>
    </div>

    @endif
</div>

<script>

let allData = [];
let currentLimit = 10;

// ================= SYNC EVENT =================
document.getElementById("event-form")
    .addEventListener("change", function() {
        document.getElementById("filter-event").value = this.value;
        loadPresensi();
    });

document.getElementById("filter-event")
    .addEventListener("change", loadPresensi);

// ================= AUTO LOAD =================
window.addEventListener("DOMContentLoaded", function () {
    let selectedEvent = document.getElementById("filter-event").value;
    if (selectedEvent) {
        loadPresensi();
    }
});

// ================= FETCH DATA =================
function loadPresensi() {

    let eventId = document.getElementById("filter-event").value;

    fetch("{{ route('presensi.fetch') }}?id_event=" + eventId)
        .then(res => res.json())
        .then(data => {
            allData = data;
            renderTable();
        });
}

// ================= RENDER TABLE =================
function renderTable() {

    let tbody = document.getElementById("presensi-body");
    tbody.innerHTML = "";

    let sliced = allData.slice(0, currentLimit);

    if (sliced.length === 0) {
        tbody.innerHTML =
            `<tr><td colspan="6" class="py-10 text-gray-400">
                Belum ada data presensi
             </td></tr>`;
        updateInfo(0, 0, 0);
        return;
    }

    sliced.forEach((p, index) => {

        let qrHtml = p.qr_code
            ? `<img src="/${p.qr_code}" class="w-20 mx-auto rounded-lg shadow">`
            : '-';

        let row = `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-5">${index + 1}</td>
                <td class="px-6 py-5">${p.event?.nama_event ?? '-'}</td>
                <td class="px-6 py-5">${p.tamu?.nama_tamu ?? '-'}</td>
                <td class="px-6 py-5 font-bold">${p.kode_unik}</td>
                <td class="px-6 py-5">${qrHtml}</td>
                <td class="px-6 py-5">
                    <div class="flex justify-center gap-3 whitespace-nowrap">
                        <a href="/${p.qr_code}" download
                            class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm">
                            Download
                        </a>
                        <form action="/presensi/${p.id_presensi}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        `;

        tbody.insertAdjacentHTML("beforeend", row);
    });

    updateInfo(1, sliced.length, allData.length);
}

// ================= TABLE INFO =================
function updateInfo(start, end, total) {
    document.getElementById("table-info").innerText =
        `Showing ${start} to ${end} of ${total} entries`;
}

// ================= SHOW ENTRIES =================
document.getElementById("entries-select")
    .addEventListener("change", function() {
        currentLimit = parseInt(this.value);
        renderTable();
    });

</script>

</x-app-layout>