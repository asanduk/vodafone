<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vertragsverwaltung Dashboard') }}
        </h2>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Statistics Section -->
            @if(isset($categories) && isset($totalCommission) && isset($monthlyCommission))
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-chart-bar mr-2 text-red-600"></i>
                            Vertragsstatistiken
                        </h3>
                        <div class="flex space-x-2">
                            <button 
                                class="statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 active-tab" 
                                data-target="commission-stats"
                            >
                                <i class="fas fa-euro-sign mr-2"></i>Provisionen
                            </button>
                            <button 
                                class="statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300" 
                                data-target="status-overview"
                            >
                                <i class="fas fa-chart-pie mr-2"></i>Übersicht
                            </button>
                            <button 
                                class="statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300" 
                                data-target="monthly-stats"
                            >
                                <i class="fas fa-chart-line mr-2"></i>Monatlich
                            </button>
                        </div>
                    </div>

                    <!-- Tab Contents -->
                    <div class="statistics-content">
                        <!-- Vertragsübersicht Tab -->
                        <div id="status-overview" class="statistics-panel hidden">
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                @foreach($categories as $category)
                                <a href="{{ route('contracts.index', ['category' => $category->id]) }}" 
                                   class="group p-4 bg-gray-50 rounded-lg hover:bg-red-50 transition-all duration-300 border border-gray-100 hover:border-red-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-gray-600 text-sm font-medium group-hover:text-red-600 transition-colors">{{ $category->name }}</div>
                                            <div class="text-2xl font-bold text-gray-800 group-hover:text-red-700">{{ $category->contracts_count }}</div>
                                        </div>
                                        <div class="p-2 bg-white rounded-full group-hover:bg-red-100 transition-colors">
                                            <i class="fas fa-file-contract text-red-600 text-xl group-hover:text-red-700"></i>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Provisionen Tab -->
                        <div id="commission-stats" class="statistics-panel">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Günlük Provision Card -->
                                <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-full bg-yellow-100">
                                            <i class="fas fa-sun text-yellow-600 text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-500">Provision für {{ \Carbon\Carbon::today()->format('d.m.Y') }}</div>
                                            <div class="text-2xl font-bold text-gray-800">{{ number_format($dailyCommission, 2) }}€</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Haftalık Provision Card -->
                                <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-full bg-purple-100">
                                            <i class="fas fa-calendar-week text-purple-600 text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-500">Provision für KW {{ \Carbon\Carbon::now()->week }}</div>
                                            <div class="text-2xl font-bold text-gray-800">{{ number_format($weeklyCommission, 2) }}€</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Monatliche Provision Card -->
                                <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-full bg-blue-100">
                                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-500">Provision für {{ \Carbon\Carbon::now()->format('F Y') }}</div>
                                            <div class="text-2xl font-bold text-gray-800">{{ number_format($monthlyCommission, 2) }}€</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Yıllık Provision Card -->
                                <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-full bg-green-100">
                                            <i class="fas fa-calendar text-green-600 text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-500">Provision für {{ \Carbon\Carbon::now()->year }}</div>
                                            <div class="text-2xl font-bold text-gray-800">{{ number_format($yearlyCommission, 2) }}€</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gesamtprovision Card -->
                                <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-full bg-red-100">
                                            <i class="fas fa-euro-sign text-red-600 text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-500">Gesamtprovision</div>
                                            <div class="text-2xl font-bold text-gray-800">{{ number_format($totalCommission, 2) }}€</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monatliche Statistiken Tab -->
                        <div id="monthly-stats" class="statistics-panel hidden">
                            <div id="monthlyStats" class="bg-white rounded-lg p-4 border border-gray-100"></div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">Keine Daten verfügbar.</span>
                </div>
            @endif

            <!-- Willkommen Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Willkommen im Vodafone Vertragssystem!</h3>
                <p class="text-gray-700 mb-4">
                    Mit diesem System können Sie Ihre täglichen Vertragsabschlüsse verwalten und Ihre Provisionen verfolgen:
                </p>
                <ul class="list-disc list-inside text-gray-700">
                    <li><strong>Vertrag Hinzufügen:</strong> Wählen Sie aus 5 Hauptkategorien und entsprechenden Unterkategorien.</li>
                    <li><strong>Provision Berechnung:</strong> Sehen Sie Ihre Provisionen in Echtzeit.</li>
                    <li><strong>Statistiken:</strong> Verfolgen Sie Ihre Leistung und Vertragsabschlüsse.</li>
                    <li><strong>Excel Export:</strong> Exportieren Sie Ihre Verträge und Provisionen.</li>
                </ul>
            </div>

            <style>
                .statistics-tab {
                    @apply text-gray-500 hover:text-gray-700 hover:bg-gray-50;
                }
                .active-tab {
                    @apply text-red-600 bg-red-50;
                }
            </style>
        </div>
    </div>

    @if(isset($monthlyData) && isset($monthlyLabels))
        <!-- ApexCharts Script -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        
        <script>
            var options = {
                series: [{
                    name: 'Verträge',
                    data: {!! json_encode($monthlyData) !!}
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(0); // Tam sayı olarak göster
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                title: {
                    text: 'Monatliche Vertragsabschlüsse',
                    align: 'left'
                },
                xaxis: {
                    categories: {!! json_encode($monthlyLabels) !!},
                    labels: {
                        rotate: 0,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Anzahl der Verträge'
                    },
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: function (val) {
                            return val.toFixed(0); // Tam sayı olarak göster
                        }
                    }
                },
                colors: ['#e60000'], // Vodafone Red
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return Math.round(value) + ' Verträge';
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#monthlyStats"), options);
            chart.render();

            // Tab Functionality
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.statistics-tab');
                const panels = document.querySelectorAll('.statistics-panel');

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        tabs.forEach(t => {
                            t.classList.remove('active-tab');
                            t.classList.add('border-transparent');
                        });

                        tab.classList.add('active-tab');
                        tab.classList.remove('border-transparent');

                        panels.forEach(panel => {
                            panel.classList.add('hidden');
                        });

                        const targetPanel = document.getElementById(tab.dataset.target);
                        targetPanel.classList.remove('hidden');

                        if (tab.dataset.target === 'monthly-stats') {
                            chart.render();
                        }
                    });
                });
            });
        </script>

        <style>
            .statistics-tab {
                @apply text-gray-500 hover:text-gray-700 border-transparent transition duration-300 flex items-center;
            }
            .active-tab {
                @apply text-red-600 border-red-600;
            }
        </style>
    @endif
</x-app-layout>

