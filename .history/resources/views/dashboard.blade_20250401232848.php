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
                                class="statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300" 
                                data-target="status-overview"
                            >
                                <i class="fas fa-chart-pie mr-2"></i>Übersicht
                            </button>
                            <button 
                                class="statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 active-tab" 
                                data-target="commission-stats"
                            >
                                <i class="fas fa-euro-sign mr-2"></i>Provisionen
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

            <!-- Leaderboard Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                        Liderlik Tablosu
                    </h3>
                    <div class="flex space-x-2">
                        <button class="leaderboard-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 active-tab" data-target="weekly-leaderboard">
                            <i class="fas fa-calendar-week mr-2"></i>Haftalık
                        </button>
                        <button class="leaderboard-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300" data-target="monthly-leaderboard">
                            <i class="fas fa-calendar-alt mr-2"></i>Aylık
                        </button>
                    </div>
                </div>

                <div class="leaderboard-content">
                    <!-- Weekly Leaderboard -->
                    <div id="weekly-leaderboard" class="leaderboard-panel">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Top Performers -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold mb-4">En İyi Performans Gösterenler</h4>
                                <div class="space-y-4">
                                    @foreach($topPerformers ?? [] as $index => $performer)
                                    <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium">{{ $performer->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $performer->contracts_count }} Sözleşme</div>
                                            </div>
                                        </div>
                                        <div class="text-lg font-bold text-red-600">{{ number_format($performer->commission, 2) }}€</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Your Performance -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold mb-4">Sizin Performansınız</h4>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-gray-600">Sıralama</div>
                                        <div class="text-2xl font-bold text-red-600">#{{ $userRank ?? '?' }}</div>
                                    </div>
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-gray-600">Haftalık Sözleşme</div>
                                        <div class="text-2xl font-bold text-gray-800">{{ $userWeeklyContracts ?? 0 }}</div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-gray-600">Haftalık Provizyon</div>
                                        <div class="text-2xl font-bold text-red-600">{{ number_format($userWeeklyCommission ?? 0, 2) }}€</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Leaderboard -->
                    <div id="monthly-leaderboard" class="leaderboard-panel hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Monthly Top Performers -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold mb-4">Aylık En İyi Performans Gösterenler</h4>
                                <div class="space-y-4">
                                    @foreach($monthlyTopPerformers ?? [] as $index => $performer)
                                    <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium">{{ $performer->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $performer->monthly_contracts }} Sözleşme</div>
                                            </div>
                                        </div>
                                        <div class="text-lg font-bold text-red-600">{{ number_format($performer->monthly_commission, 2) }}€</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Monthly Your Performance -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold mb-4">Aylık Performansınız</h4>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-gray-600">Aylık Sıralama</div>
                                        <div class="text-2xl font-bold text-red-600">#{{ $userMonthlyRank ?? '?' }}</div>
                                    </div>
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-gray-600">Aylık Sözleşme</div>
                                        <div class="text-2xl font-bold text-gray-800">{{ $userMonthlyContracts ?? 0 }}</div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-gray-600">Aylık Provizyon</div>
                                        <div class="text-2xl font-bold text-red-600">{{ number_format($userMonthlyCommission ?? 0, 2) }}€</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Goal Tracking Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-bullseye mr-2 text-green-500"></i>
                        Hedef Takibi
                    </h3>
                    <button class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Hedef Belirle
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Monthly Goal Progress -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-4">Aylık Hedef</h4>
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200">
                                        İlerleme
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-red-600">
                                        {{ $monthlyGoalProgress ?? 0 }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-200">
                                <div style="width:{{ $monthlyGoalProgress ?? 0 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                            </div>
                        </div>
                        <div class="text-center text-sm text-gray-600">
                            {{ $monthlyContracts ?? 0 }}/{{ $monthlyGoal ?? 0 }} Sözleşme
                        </div>
                    </div>

                    <!-- Commission Goal Progress -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-4">Provizyon Hedefi</h4>
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                                        İlerleme
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-green-600">
                                        {{ $commissionGoalProgress ?? 0 }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                                <div style="width:{{ $commissionGoalProgress ?? 0 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                            </div>
                        </div>
                        <div class="text-center text-sm text-gray-600">
                            {{ number_format($currentCommission ?? 0, 2) }}€/{{ number_format($commissionGoal ?? 0, 2) }}€
                        </div>
                    </div>

                    <!-- Achievement Badges -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-4">Başarı Rozetleri</h4>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($achievements ?? [] as $achievement)
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-2 rounded-full bg-{{ $achievement->color ?? 'yellow' }}-100 flex items-center justify-center">
                                    <i class="fas {{ $achievement->icon ?? 'fa-trophy' }} text-{{ $achievement->color ?? 'yellow' }}-600 text-2xl"></i>
                                </div>
                                <div class="text-xs font-medium text-gray-600">{{ $achievement->name ?? 'Başarı' }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

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

    <!-- Goal Setting Modal -->
    <div id="goalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Hedef Belirle</h3>
                <form id="goalForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="goalType">
                            Hedef Tipi
                        </label>
                        <select name="type" id="goalType" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="monthly_contracts">Aylık Sözleşme Sayısı</option>
                            <option value="commission">Aylık Provizyon</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="goalTarget">
                            Hedef Değeri
                        </label>
                        <input type="number" name="target" id="goalTarget" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="goalMonth">
                            Ay
                        </label>
                        <input type="month" name="month" id="goalMonth" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeGoalModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            İptal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
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

                // Leaderboard Tab Functionality
                const leaderboardTabs = document.querySelectorAll('.leaderboard-tab');
                const leaderboardPanels = document.querySelectorAll('.leaderboard-panel');

                leaderboardTabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        leaderboardTabs.forEach(t => {
                            t.classList.remove('active-tab');
                        });

                        tab.classList.add('active-tab');

                        leaderboardPanels.forEach(panel => {
                            panel.classList.add('hidden');
                        });

                        const targetPanel = document.getElementById(tab.dataset.target);
                        targetPanel.classList.remove('hidden');
                    });
                });
            });

            // Goal Modal Functions
            function openGoalModal() {
                document.getElementById('goalModal').classList.remove('hidden');
                document.getElementById('goalMonth').value = new Date().toISOString().slice(0, 7);
            }

            function closeGoalModal() {
                document.getElementById('goalModal').classList.add('hidden');
            }

            // Goal Form Submission
            document.getElementById('goalForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('{{ route("dashboard.goals") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        closeGoalModal();
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
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
            .leaderboard-tab {
                @apply text-gray-500 hover:text-gray-700 transition duration-300;
            }
            .leaderboard-tab.active-tab {
                @apply text-red-600 bg-red-50;
            }
        </style>
    @endif
</x-app-layout>

