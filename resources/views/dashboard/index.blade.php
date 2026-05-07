@extends('dashboard.dashboard')

@section('content')

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Pasien</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_pasien']) }}</h3>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($summary['pendapatan'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Menunggu Antrian</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['menunggu']) }}</h3>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Selesai Ditangani</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['selesai']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Line Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Kunjungan 7 Hari Terakhir</h3>
            <div id="chart-kunjungan" style="height: 300px;"></div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Proporsi Tipe Pasien</h3>
            <div id="chart-tipe" style="height: 300px;"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Kunjungan
            var optionsKunjungan = {
                series: [{
                    name: 'Jumlah Pasien',
                    data: {!! $dataKunjungan !!}
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'Poppins, sans-serif'
                },
                colors: ['#16a34a'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: {
                    categories: {!! $labelsKunjungan !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) { return Math.round(val); }
                    }
                }
            };
            var chartKunjungan = new ApexCharts(document.querySelector("#chart-kunjungan"), optionsKunjungan);
            chartKunjungan.render();

            // Chart Tipe Pasien
            var dataTipe = {!! $dataTipe !!};
            var labelsTipe = {!! $labelsTipe !!};
            
            if (dataTipe.length === 0) {
                dataTipe = [1];
                labelsTipe = ['Belum Ada Data'];
            }

            var optionsTipe = {
                series: dataTipe,
                labels: labelsTipe,
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif'
                },
                colors: ['#16a34a', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%'
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: { position: 'bottom' }
            };
            var chartTipe = new ApexCharts(document.querySelector("#chart-tipe"), optionsTipe);
            chartTipe.render();
        });
    </script>
@endsection
