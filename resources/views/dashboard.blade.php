<x-app-layout>

    {{-- ================= HEADER ================= --}}
    <x-slot name="header">

        <div class="py-8 relative">

            {{-- JAM & TANGGAL REALTIME --}}
            <div class="absolute top-0 right-0 mt-4 mr-4 text-right">
                <div id="currentTime"
                     class="bg-black bg-opacity-70 text-white font-bold text-2xl px-4 py-2 rounded-lg shadow-lg">
                </div>
                <div id="currentDate"
                     class="bg-black bg-opacity-50 text-white font-semibold text-sm px-3 py-1 rounded-lg mt-1 shadow-md">
                </div>
            </div>

            {{-- SELAMAT DATANG --}}
            <div class="bg-white rounded-xl shadow-md p-8 mb-9 text-left max-w-lg">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Selamat Datang di Halaman Admin Website Presensi Event
                </h2>
            </div>

            {{-- JIKA BELUM ADA EVENT --}}
            @if(!$event)
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-3xl p-12 text-center shadow-xl mb-10">
                <h2 class="text-4xl font-extrabold mb-4">
                    📌 Belum Ada Event Aktif
                </h2>
                <p class="opacity-90 mb-8 max-w-xl mx-auto text-lg">
                    Silakan buat atau gabung event terlebih dahulu untuk menggunakan dashboard analytics.
                </p>
                <a href="{{ route('event.hub') }}"
                   class="bg-white text-indigo-600 font-bold px-10 py-4 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 text-lg">
                    🎉Buat / Pilih Event Sekarang
                </a>
            </div>
            @endif

            {{-- SUMMARY CARDS --}}
            @if($event)
            <div class="grid md:grid-cols-4 gap-6">

                <div class="relative bg-emerald-600 text-white rounded-lg p-6 shadow-md overflow-hidden">
                    <h3 class="text-2xl font-bold">
                        {{ $event->nama_event ?? '-' }}
                    </h3>
                    <p class="text-lg mt-2">Event Aktif</p>
                </div>

                <div class="relative bg-red-500 text-white rounded-lg p-6 shadow-md overflow-hidden">
                    <h3 class="text-5xl font-bold">
                        {{ $summary['totalTamu'] ?? 0 }}
                    </h3>
                    <p class="text-lg mt-2">Total Tamu</p>
                </div>

                <div class="relative bg-yellow-500 text-white rounded-lg p-6 shadow-md overflow-hidden">
                    <h3 class="text-5xl font-bold">
                        {{ $summary['totalUndangan'] ?? 0 }}
                    </h3>
                    <p class="text-lg mt-2">Total Undangan</p>
                </div>

                <div class="relative bg-cyan-600 text-white rounded-lg p-6 shadow-md overflow-hidden">
                    <h3 class="text-5xl font-bold">
                        {{ $summary['totalHadir'] ?? 0 }}
                    </h3>
                    <p class="text-lg mt-2">Total Hadir</p>
                </div>

            </div>
            @endif

        </div>

    </x-slot>

    {{-- ================= CONTENT ================= --}}
    @if($event)
    <div class="min-h-screen bg-gray-50 py-12">

        <div class="max-w-6xl mx-auto px-6">

            <div class="flex justify-between items-center mb-12">
                <h3 class="text-3xl font-bold text-gray-700">
                    📊 Event Analytics
                </h3>

                <a href="{{ route('event.hub') }}"
                   class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 font-semibold">
                    🔄 Kelola Event Lain
                </a>
            </div>

            <div class="grid lg:grid-cols-2 gap-10">

                {{-- PIE CHART --}}
                <div class="bg-white rounded-xl p-8 shadow-md border">
                    <h4 class="font-bold mb-6 text-gray-700 text-xl text-center">
                        Statistik Presensi
                    </h4>
                    <div class="w-80 h-80 mx-auto">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>

                {{-- LINE CHART --}}
                <div class="bg-white rounded-xl p-8 shadow-md border">
                    <h4 class="font-bold mb-6 text-gray-700 text-xl text-center">
                        Trend Kehadiran
                    </h4>
                    <div class="h-80">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

            </div>

        </div>

    </div>
    @endif

    {{-- ================= SCRIPTS ================= --}}
    @push('scripts')

    {{-- JAM REALTIME (SELALU JALAN) --}}
    <script>
    document.addEventListener("DOMContentLoaded", function(){

        function updateTime() {
            const now = new Date();
            const optionsDate = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
            const optionsTime = { hour:'2-digit', minute:'2-digit', second:'2-digit' };

            const timeEl = document.getElementById('currentTime');
            const dateEl = document.getElementById('currentDate');

            if(timeEl && dateEl){
                timeEl.textContent = now.toLocaleTimeString([], optionsTime);
                dateEl.textContent = now.toLocaleDateString('id-ID', optionsDate);
            }
        }

        updateTime();
        setInterval(updateTime, 1000);
    });
    </script>

    {{-- CHART HANYA JIKA ADA EVENT --}}
    @if($event)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function(){

        new Chart(document.getElementById('pieChart'), {
            type:'pie',
            data:{
                labels:['Hadir','Belum Hadir'],
                datasets:[{
                    data:[
                        {{ $summary['totalHadir'] ?? 0 }},
                        {{ ($summary['totalUndangan'] ?? 0) - ($summary['totalHadir'] ?? 0) }}
                    ],
                    backgroundColor:['#10b981','#f59e0b']
                }]
            }
        });

        new Chart(document.getElementById('lineChart'), {
            type:'line',
            data:{
                labels:['Day 1','Day 2','Day 3','Day 4','Day 5'],
                datasets:[{
                    label:'Presensi Harian',
                    data:[
                        {{ rand(5,15) }},
                        {{ rand(10,25) }},
                        {{ rand(20,35) }},
                        {{ rand(30,45) }},
                        {{ rand(40,60) }}
                    ],
                    fill:true,
                    tension:0.4
                }]
            }
        });

    });
    </script>
    @endif

    @endpush

</x-app-layout>