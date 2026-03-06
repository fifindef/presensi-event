<x-app-layout>

    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Event Hub</h1>
                <p class="text-gray-500 mt-1">
                    Kelola, pilih, dan bergabung dengan event Anda.
                </p>
            </div>

            <a href="{{ route('event.index') }}"
               class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-md transition">
                ➕ Buat / Kelola Event
            </a>
        </div>


        <!-- JOIN EVENT SECTION -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-10">

            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Join Event dengan Access Code
            </h3>

            <form action="{{ route('event.join') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                @csrf

                <input type="text"
                       name="access_code"
                       placeholder="Masukkan kode akses event"
                       required
                       class="flex-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:outline-none transition">

                <button type="submit"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold shadow-md transition">
                    🚀 Join Event
                </button>
            </form>

            @if(session('success'))
                <div class="mt-4 px-4 py-3 rounded-xl bg-green-50 text-green-600 border border-green-200 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 px-4 py-3 rounded-xl bg-red-50 text-red-600 border border-red-200 text-sm">
                    {{ session('error') }}
                </div>
            @endif

        </div>


        <!-- EVENT LIST -->
        <h3 class="text-xl font-semibold text-gray-800 mb-6">
            Event Saya
        </h3>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($events as $event)

                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition border border-gray-100 p-6 flex flex-col justify-between">

                    <div>

                        <h4 class="text-lg font-bold text-gray-800 mb-3">
                            {{ $event->nama_event }}
                        </h4>

                        <div class="text-sm text-gray-600 space-y-1 mb-4">
                            <p>
                                📅 {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}
                            </p>
                            <p>
                                ⏰ {{ $event->jam }}
                            </p>
                            <p>
                                📍 {{ $event->lokasi }}
                            </p>
                        </div>

                        @if(session('active_event_id') == $event->id_event)
                            <span class="inline-block mb-4 px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Event Aktif
                            </span>
                        @endif

                    </div>

                    <!-- ACTION BUTTON -->
                    <div class="flex gap-3 mt-4">

                        <a href="{{ route('event.activate', $event->id_event) }}"
                           class="flex-1 text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                            🚀 Pilih
                        </a>

                        <form action="{{ route('event.cancel', $event->id_event) }}"
                              method="POST"
                              class="flex-1">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition">
                                ❌ Batal
                            </button>
                        </form>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-12 bg-white rounded-2xl shadow border border-gray-100">
                    <p class="text-gray-500">
                        Belum ada event yang kamu miliki atau join.
                    </p>
                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>