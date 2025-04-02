<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vertragsverwaltung Dashboard') }}
        </h2>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Willkommen Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Willkommen im Vodafone Vertragssystem!</h3>
                <p class="text-gray-700 mb-4">
                    Mit diesem System können Sie Ihre täglichen Vertragsabschlüsse verwalten und Ihre Provisionen verfolgen:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-4">
                    <li><strong>Vertrag Hinzufügen:</strong> Wählen Sie aus 5 Hauptkategorien und entsprechenden Unterkategorien.</li>
                    <li><strong>Provision Berechnung:</strong> Sehen Sie Ihre Provisionen in Echtzeit.</li>
                    <li><strong>Statistiken:</strong> Verfolgen Sie Ihre Leistung und Vertragsabschlüsse.</li>
                    <li><strong>Excel Export:</strong> Exportieren Sie Ihre Verträge und Provisionen.</li>
                </ul>

                <!-- Action Buttons -->
                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('contracts.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus"></i> Neuer Vertrag
                    </a>
                    
                    <a href="{{ route('contracts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-list"></i> Alle Verträge
                    </a>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Vertragsstatistiken</h3>
                
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-4" aria-label="Statistics Navigation">
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2 active-tab" 
                            data-target="status-overview"
                        >
                            <i class="fas fa-chart-pie mr-2"></i>Übersicht
                        </button>
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2" 
                            data-target="commission-stats"
                        >
                            <i class="fas fa-euro-sign mr-2"></i>Provisionen
                        </button>
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2" 
                            data-target="monthly-stats"
                        >
                            <i class="fas fa-chart-line mr-2"></i>Monatliche Statistiken
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="statistics-content">
                    <!-- Vertragsübersicht Tab -->
                    <div id="status-overview" class="statistics-panel">
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            @foreach($categories as $category)
                            <a href="{{ route('contracts.index', ['category' => $category->id]) }}" 
                               class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-gray-600 text-lg font-semibold">{{ $category->name }}</div>
                                        <div class="text-2xl font-bold">{{ $category->contracts_count }}</div>
                                    </div>
                                    <i class="fas fa-file-contract text-red-600 text-2xl"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Provisionen Tab -->
                    <div id="commission-stats" class="statistics-panel hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Gesamtprovision Card -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <div class="flex items-center mb-4">
                                    <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                                        <i class="fas fa-euro-sign text-white fa-2x"></i>
                                    </div>
                                    <div class="mx-5">
                                        <h4 class="text-3xl font-semibold text-gray-700">{{ number_format($totalCommission, 2) }}€</h4>
                                        <div class="text-gray-500">Gesamtprovision</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Monatliche Provision Card -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <div class="flex items-center mb-4">
                                    <div class="p-3 rounded-full bg-blue-600 bg-opacity-75">
                                        <i class="fas fa-calendar-alt text-white fa-2x"></i>
                                    </div>
                                    <div class="mx-5">
                                        <h4 class="text-3xl font-semibold text-gray-700">{{ number_format($monthlyCommission, 2) }}€</h4>
                                        <div class="text-gray-500">Provision diesen Monat</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monatliche Statistiken Tab -->
                    <div id="monthly-stats" class="statistics-panel hidden">
                        <div id="monthlyStats"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Vertragsabschlüsse Trend',
                align: 'left'
            },
            xaxis: {
                categories: {!! json_encode($monthlyLabels) !!},
            },
            yaxis: {
                title: {
                    text: 'Anzahl der Verträge'
                }
            },
            colors: ['#e60000'], // Vodafone Red
            tooltip: {
                y: {
                    formatter: function (value) {
                        return value + ' Verträge';
                    }
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

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @else
        @if(isset($categories))
            <!-- Kategoriler ile ilgili kodlar -->
        @endif

        @if(isset($totalCommission))
            <!-- Komisyon ile ilgili kodlar -->
        @endif
    @endif
</x-app-layout>

