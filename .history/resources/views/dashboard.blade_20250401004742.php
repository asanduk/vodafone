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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Willkommen Section - 2/3 width -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                        <h3 class="text-xl font-semibold mb-6 text-gray-800">
                            <i class="fas fa-home mr-2 text-red-600"></i>
                            Willkommen im Vodafone Vertragssystem!
                        </h3>
                        <p class="text-gray-700 mb-6 text-lg">
                            Mit diesem System können Sie Ihre täglichen Vertragsabschlüsse verwalten und Ihre Provisionen verfolgen:
                        </p>
                        <ul class="list-none space-y-4 mb-8">
                            <li class="flex items-start">
                                <div class="p-2 bg-red-100 rounded-full mr-4">
                                    <i class="fas fa-file-contract text-red-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1">Vertrag Hinzufügen</h4>
                                    <p class="text-gray-600">Wählen Sie aus 5 Hauptkategorien und entsprechenden Unterkategorien.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="p-2 bg-green-100 rounded-full mr-4">
                                    <i class="fas fa-euro-sign text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1">Provision Berechnung</h4>
                                    <p class="text-gray-600">Sehen Sie Ihre Provisionen in Echtzeit.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="p-2 bg-blue-100 rounded-full mr-4">
                                    <i class="fas fa-chart-bar text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1">Statistiken</h4>
                                    <p class="text-gray-600">Verfolgen Sie Ihre Leistung und Vertragsabschlüsse.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="p-2 bg-purple-100 rounded-full mr-4">
                                    <i class="fas fa-file-excel text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1">Excel Export</h4>
                                    <p class="text-gray-600">Exportieren Sie Ihre Verträge und Provisionen.</p>
                                </div>
                            </li>
                        </ul>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex space-x-4">
                            <a href="{{ route('contracts.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center">
                                <i class="fas fa-plus mr-2"></i> Neuer Vertrag
                            </a>
                            
                            <a href="{{ route('contracts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center">
                                <i class="fas fa-list mr-2"></i> Alle Verträge
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section - 1/3 width -->
                @if(isset($categories) && isset($totalCommission) && isset($monthlyCommission))
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                            <div class="flex flex-col space-y-2">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-chart-bar mr-2 text-red-600"></i>
                                    Vertragsstatistiken
                                </h3>
                                <div class="flex flex-col space-y-1">
                                    <button 
                                        class="statistics-tab px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 active-tab text-left" 
                                        data-target="status-overview"
                                    >
                                        <i class="fas fa-chart-pie mr-2"></i>Übersicht
                                    </button>
                                    <button 
                                        class="statistics-tab px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left" 
                                        data-target="commission-stats"
                                    >
                                        <i class="fas fa-euro-sign mr-2"></i>Provisionen
                                    </button>
                                    <button 
                                        class="statistics-tab px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left" 
                                        data-target="monthly-stats"
                                    >
                                        <i class="fas fa-chart-line mr-2"></i>Monatlich
                                    </button>
                                </div>
                            </div>

                            <!-- Tab Contents -->
                            <div class="statistics-content mt-3">
                                <!-- Vertragsübersicht Tab -->
                                <div id="status-overview" class="statistics-panel">
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($categories as $category)
                                        <a href="{{ route('contracts.index', ['category' => $category->id]) }}" 
                                           class="group p-2 bg-gray-50 rounded-lg hover:bg-red-50 transition-all duration-300 border border-gray-100 hover:border-red-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-gray-600 text-xs font-medium group-hover:text-red-600 transition-colors">{{ $category->name }}</div>
                                                    <div class="text-lg font-bold text-gray-800 group-hover:text-red-700">{{ $category->contracts_count }}</div>
                                                </div>
                                                <div class="p-1.5 bg-white rounded-full group-hover:bg-red-100 transition-colors">
                                                    <i class="fas fa-file-contract text-red-600 text-sm group-hover:text-red-700"></i>
                                                </div>
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Provisionen Tab -->
                                <div id="commission-stats" class="statistics-panel hidden">
                                    <div class="grid grid-cols-1 gap-2">
                                        <!-- Günlük Provision Card -->
                                        <div class="bg-white rounded-lg p-2 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                            <div class="flex items-center">
                                                <div class="p-1.5 rounded-full bg-yellow-100">
                                                    <i class="fas fa-sun text-yellow-600 text-sm"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-xs text-gray-500">Heutige Provision</div>
                                                    <div class="text-base font-bold text-gray-800">{{ number_format($dailyCommission, 2) }}€</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Haftalık Provision Card -->
                                        <div class="bg-white rounded-lg p-2 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                            <div class="flex items-center">
                                                <div class="p-1.5 rounded-full bg-purple-100">
                                                    <i class="fas fa-calendar-week text-purple-600 text-sm"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-xs text-gray-500">Wöchentliche Provision</div>
                                                    <div class="text-base font-bold text-gray-800">{{ number_format($weeklyCommission, 2) }}€</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Monatliche Provision Card -->
                                        <div class="bg-white rounded-lg p-2 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                            <div class="flex items-center">
                                                <div class="p-1.5 rounded-full bg-blue-100">
                                                    <i class="fas fa-calendar-alt text-blue-600 text-sm"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-xs text-gray-500">Monatliche Provision</div>
                                                    <div class="text-base font-bold text-gray-800">{{ number_format($monthlyCommission, 2) }}€</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Yıllık Provision Card -->
                                        <div class="bg-white rounded-lg p-2 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                            <div class="flex items-center">
                                                <div class="p-1.5 rounded-full bg-green-100">
                                                    <i class="fas fa-calendar text-green-600 text-sm"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-xs text-gray-500">Jährliche Provision</div>
                                                    <div class="text-base font-bold text-gray-800">{{ number_format($yearlyCommission, 2) }}€</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Gesamtprovision Card -->
                                        <div class="bg-white rounded-lg p-2 border border-gray-100 hover:border-red-200 transition-all duration-300">
                                            <div class="flex items-center">
                                                <div class="p-1.5 rounded-full bg-red-100">
                                                    <i class="fas fa-euro-sign text-red-600 text-sm"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-xs text-gray-500">Gesamtprovision</div>
                                                    <div class="text-base font-bold text-gray-800">{{ number_format($totalCommission, 2) }}€</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Monatliche Statistiken Tab -->
                                <div id="monthly-stats" class="statistics-panel hidden">
                                    <div id="monthlyStats" class="bg-white rounded-lg p-2 border border-gray-100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="lg:col-span-1">
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                            <span class="block sm:inline">Keine Daten verfügbar.</span>
                        </div>
                    </div>
                @endif
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

