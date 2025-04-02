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
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Heute -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <i class="fas fa-calendar-day text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Heute</p>
                                <p class="text-lg font-semibold">
                                    {{ number_format($todayStats->commission ?? 0, 2, ',', '.') }}€
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $todayStats->count ?? 0 }} Verträge
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Diese Woche -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <i class="fas fa-calendar-week text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Diese Woche</p>
                                <p class="text-lg font-semibold">
                                    {{ number_format($weekStats->commission ?? 0, 2, ',', '.') }}€
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $weekStats->count ?? 0 }} Verträge
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Dieser Monat -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100">
                                <i class="fas fa-calendar-alt text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Dieser Monat</p>
                                <p class="text-lg font-semibold">
                                    {{ number_format($monthStats->commission ?? 0, 2, ',', '.') }}€
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $monthStats->count ?? 0 }} Verträge
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Dieses Jahr -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100">
                                <i class="fas fa-calendar text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Dieses Jahr</p>
                                <p class="text-lg font-semibold">
                                    {{ number_format($yearStats->commission ?? 0, 2, ',', '.') }}€
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $yearStats->count ?? 0 }} Verträge
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Chart -->
                <div class="mt-6">
                    <div id="monthlyStats"></div>
                </div>
            </div>

            <!-- Debug Information -->
            @if(Auth::user()->is_admin)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-8">
                    <h3 class="text-lg font-semibold mb-4">Tüm Sözleşmeler</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Müşteri</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sözleşme No</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alt Kategori</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komisyon</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($allContracts ?? [] as $contract)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->contract_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->customer_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->contract_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->category_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->subcategory_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($contract->commission_amount, 2, ',', '.') }}€
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Henüz sözleşme bulunmamaktadır.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Debug Stats -->
                    <div class="mt-8 bg-gray-50 p-4 rounded">
                        <h4 class="font-semibold mb-2">İstatistikler:</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Bugün:</p>
                                <p class="font-medium">{{ $todayStats->count ?? 0 }} Sözleşme</p>
                                <p class="text-sm">{{ number_format($todayStats->commission ?? 0, 2, ',', '.') }}€</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Bu Hafta:</p>
                                <p class="font-medium">{{ $weekStats->count ?? 0 }} Sözleşme</p>
                                <p class="text-sm">{{ number_format($weekStats->commission ?? 0, 2, ',', '.') }}€</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Bu Ay:</p>
                                <p class="font-medium">{{ $monthStats->count ?? 0 }} Sözleşme</p>
                                <p class="text-sm">{{ number_format($monthStats->commission ?? 0, 2, ',', '.') }}€</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Bu Yıl:</p>
                                <p class="font-medium">{{ $yearStats->count ?? 0 }} Sözleşme</p>
                                <p class="text-sm">{{ number_format($yearStats->commission ?? 0, 2, ',', '.') }}€</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return parseInt(val); // Tam sayı göster
                    },
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                title: {
                    text: 'Ihre monatlichen Vertragsabschlüsse',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                xaxis: {
                    categories: {!! json_encode($monthlyLabels) !!},
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '12px',
                            fontWeight: 500
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Anzahl der Verträge'
                    },
                    min: 0,
                    tickAmount: 5,
                    labels: {
                        formatter: function (val) {
                            return parseInt(val);
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: [{
                        formatter: function (val) {
                            return parseInt(val) + ' Verträge';
                        }
                    }]
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

