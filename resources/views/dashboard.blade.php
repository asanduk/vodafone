<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Vertragsverwaltung Dashboard') }}
            </h2>
            <a href="{{ route('contracts.export') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2 transition-colors duration-200"
               style="background-color: #16a34a; color: white; border: none; cursor: pointer;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Excel Export
            </a>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Quick Navigation -->
            <div class="flex flex-wrap items-center gap-2 mb-6">
                @if(isset($overallStats) && auth()->user()->is_admin)
                    <a href="#sec-overall" class="inline-flex items-center px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm hover:bg-blue-100">
                        <i class="fas fa-globe mr-2"></i> Gesamt
                    </a>
                @endif
                @if(isset($categories) && isset($totalCommission) && isset($monthlyCommission))
                    <a href="#sec-user-stats" class="inline-flex items-center px-3 py-1.5 rounded-full bg-red-50 text-red-700 text-sm hover:bg-red-100">
                        <i class="fas fa-chart-bar mr-2"></i> Meine Stats
                    </a>
                @endif
                @if(auth()->user()->is_admin && isset($settings) && ($settings->show_admin_earnings ?? true) && isset($adminEarningsContracts) && $adminEarningsContracts->count())
                    <a href="#sec-owner-per-contract" class="inline-flex items-center px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-sm hover:bg-green-100">
                        <i class="fas fa-wallet mr-2"></i> Pro Vertrag
                    </a>
                @endif
                @if(auth()->user()->is_admin && isset($settings) && ($settings->show_admin_category_earnings ?? true) && isset($adminCategoryEarnings) && $adminCategoryEarnings->count())
                    <a href="#sec-owner-monthly" class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-sm hover:bg-indigo-100">
                        <i class="fas fa-layer-group mr-2"></i> Grundprovision
                    </a>
                @endif
                @if(isset($settings) && ($settings->show_ranking ?? false) && isset($ranking) && $ranking->count())
                    <a href="#sec-ranking" class="inline-flex items-center px-3 py-1.5 rounded-full bg-yellow-50 text-yellow-700 text-sm hover:bg-yellow-100">
                        <i class="fas fa-trophy mr-2"></i> Ranking
                    </a>
                @endif
                <a href="#sec-announcements" class="inline-flex items-center px-3 py-1.5 rounded-full bg-orange-50 text-orange-700 text-sm hover:bg-orange-100">
                    <i class="fas fa-bullhorn mr-2"></i> Ankündigungen
                </a>
                <a href="#sec-welcome" class="inline-flex items-center px-3 py-1.5 rounded-full bg-gray-50 text-gray-700 text-sm hover:bg-gray-100">
                    <i class="fas fa-info-circle mr-2"></i> Willkommen
                </a>
            </div>

            <div id="dashboard-grid" class="grid grid-cols-1 gap-6">
                <div id="dashboard-left" class="flex flex-col">
                    @if(isset($overallStats) && auth()->user()->is_admin)
                        <div id="sec-overall" class="dashboard-section" data-section="overall-stats">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-800">
                                        <i class="fas fa-globe mr-2 text-blue-600"></i>
                                        Gesamtstatistiken (Alle Benutzer)
                                    </h3>
                                    <div class="flex space-x-2">
                                        <button 
                                            class="overall-statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 active-tab" 
                                            data-target="overall-commission-stats"
                                        >
                                            <i class="fas fa-euro-sign mr-2"></i>Provisionen
                                        </button>
                                        <button 
                                            class="overall-statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300" 
                                            data-target="overall-status-overview"
                                        >
                                            <i class="fas fa-chart-pie mr-2"></i>Übersicht
                                        </button>
                                        <button 
                                            class="overall-statistics-tab px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300" 
                                            data-target="overall-monthly-stats"
                                        >
                                            <i class="fas fa-chart-line mr-2"></i>Monatlich
                                        </button>
                                    </div>
                                </div>
                                <div class="overall-statistics-content">
                                    <!-- Overall Übersicht Tab -->
                                    <div id="overall-status-overview" class="overall-statistics-panel hidden">
                                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                            @foreach($categories as $category)
                                            <div class="group p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-all duration-300 border border-gray-100 hover:border-blue-200">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <div class="text-gray-600 text-sm font-medium group-hover:text-blue-600 transition-colors">{{ $category->name }}</div>
                                                        <div class="text-2xl font-bold text-gray-800 group-hover:text-blue-700">{{ $category->contracts()->count() }}</div>
                                                    </div>
                                                    <div class="p-2 bg-white rounded-full group-hover:bg-blue-100 transition-colors">
                                                        <i class="fas fa-file-contract text-blue-600 text-xl group-hover:text-blue-700"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Overall Provisionen Tab -->
                                    <div id="overall-commission-stats" class="overall-statistics-panel">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <!-- Daily Commission Card -->
                                            <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition-all duration-300">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-full bg-yellow-100">
                                                        <i class="fas fa-sun text-yellow-600 text-xl"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm text-gray-500">Provision für {{ \Carbon\Carbon::today()->format('d.m.Y') }}</div>
                                                        <div class="text-2xl font-bold text-gray-800">{{ number_format($overallStats['daily_commission'], 2) }}€</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Weekly Commission Card -->
                                            <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition-all duration-300">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-full bg-purple-100">
                                                        <i class="fas fa-calendar-week text-purple-600 text-xl"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm text-gray-500">Provision für KW {{ \Carbon\Carbon::now()->week }}</div>
                                                        <div class="text-2xl font-bold text-gray-800">{{ number_format($overallStats['weekly_commission'], 2) }}€</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Monthly Commission Card -->
                                            <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition-all duration-300">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-full bg-blue-100">
                                                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm text-gray-500">Provision für {{ \Carbon\Carbon::now()->format('F Y') }}</div>
                                                        <div class="text-2xl font-bold text-gray-800">{{ number_format($overallStats['monthly_commission'], 2) }}€</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Yearly Commission Card -->
                                            <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition-all duration-300">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-full bg-green-100">
                                                        <i class="fas fa-calendar text-green-600 text-xl"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm text-gray-500">Provision für {{ \Carbon\Carbon::now()->year }}</div>
                                                        <div class="text-2xl font-bold text-gray-800">{{ number_format($overallStats['yearly_commission'], 2) }}€</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Commission Card -->
                                            <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition-all duration-300">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-full bg-red-100">
                                                        <i class="fas fa-euro-sign text-red-600 text-xl"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm text-gray-500">Gesamtprovision</div>
                                                        <div class="text-2xl font-bold text-gray-800">{{ number_format($overallStats['total_commission'], 2) }}€</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Contracts Card -->
                                            <div class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition-all duration-300">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-full bg-indigo-100">
                                                        <i class="fas fa-file-contract text-indigo-600 text-xl"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm text-gray-500">Gesamtverträge</div>
                                                        <div class="text-2xl font-bold text-gray-800">{{ number_format($overallStats['total_contracts']) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Overall Monatliche Statistiken Tab -->
                                    <div id="overall-monthly-stats" class="overall-statistics-panel hidden">
                                        <div class="mb-6">
                                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Verträge der letzten 12 Monate (Alle Benutzer)</h4>
                                            <div id="overall-monthly-chart" class="h-80"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($categories) && isset($totalCommission) && isset($monthlyCommission))
                        <div id="sec-user-stats" class="dashboard-section" data-section="user-stats">
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
                        </div>
                    @else
                        <div class="dashboard-section" data-section="user-stats-empty">
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                                <span class="block sm:inline">Keine Daten verfügbar.</span>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->is_admin && isset($settings) && ($settings->show_admin_earnings ?? true) && isset($adminEarningsContracts) && $adminEarningsContracts->count())
                        <div id="sec-owner-per-contract" class="dashboard-section" data-section="owner-per-contract">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800">
                                        <i class="fas fa-wallet mr-2 text-green-600"></i>
                                        Inhaber-Provisionen (pro Vertrag)
                                    </h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kunde</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provision</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($adminEarningsContracts as $c)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($c->contract_date)->format('d.m.Y') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">{{ $c->customer_name ?? '-' }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        @php
                                                            $ownerAmount = isset($c->owner_amount) ? (float) $c->owner_amount : (float) $c->commission_amount;
                                                            $ownerBonus = (float) ($c->owner_bonus ?? 0);
                                                            $ownerLevel = (int) ($c->owner_level ?? 1);
                                                        @endphp
                                                        {{ number_format($ownerAmount, 2, ',', '.') }}€
                                                        @if($ownerBonus > 0)
                                                            <span class="ml-2 text-xs font-semibold text-green-700 bg-green-100 border border-green-200 rounded px-2 py-0.5">+{{ number_format($ownerBonus, 2, ',', '.') }}€ (Level {{ $ownerLevel }})</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->is_admin && isset($settings) && ($settings->show_admin_category_earnings ?? true) && isset($adminCategoryEarnings) && $adminCategoryEarnings->count())
                        <div id="sec-owner-monthly" class="dashboard-section" data-section="owner-monthly-categories">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800">
                                        <i class="fas fa-layer-group mr-2 text-indigo-600"></i>
                                        Grundprovision (monatlich nach Kategorie)
                                    </h3>
                                    <div class="text-sm text-gray-600">
                                        <span class="mr-2">Bisher gesamt (Grundprovision):</span>
                                        <span class="font-semibold">{{ number_format(($overallBaseTotal ?? 0), 2, ',', '.') }}€</span>
                                    </div>
                                </div>

                                @php
                                    $monthKeys = $adminCategoryEarnings->keys()->values();
                                @endphp

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Modus</label>
                                        <select id="owner-mode" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            <option value="month" selected>Monat</option>
                                            <option value="cumulative">Bis heute (kumulativ)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Zeitraum</label>
                                        <select id="owner-period" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            <option value="3">Letzte 3 Monate</option>
                                            <option value="6">Letzte 6 Monate</option>
                                            <option value="12" selected>Letzte 12 Monate</option>
                                            <option value="all">Alle</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <div class="flex items-end justify-between gap-3">
                                            <div class="flex-1">
                                                <label class="block text-xs text-gray-500 mb-1">Monat</label>
                                                <select id="owner-month" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                                    @foreach($monthKeys as $i => $monthKey)
                                                        <option value="admin-month-panel-{{ $i }}">{{ \Carbon\Carbon::createFromFormat('Y-m', $monthKey)->format('F Y') }} — {{ number_format($adminCategoryEarnings[$monthKey]['total'], 2, ',', '.') }}€ ({{ number_format($adminCategoryEarnings[$monthKey]['count']) }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="owner-mode-badge" class="text-xs px-2 py-1 rounded bg-indigo-50 text-indigo-700 border border-indigo-200 hidden">Modus: Kumulativ aktiv</div>
                                        </div>
                                    </div>
                                </div>

                                <div id="owner-cumulative" class="hidden p-0 overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorie</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verträge</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grundprovision</th>
                                            </tr>
                                        </thead>
                                        <tbody id="owner-cumulative-body" class="bg-white divide-y divide-gray-200"></tbody>
                                    </table>
                                </div>

                                @foreach($adminCategoryEarnings as $monthKey => $data)
                                    @php $idx = $loop->index; @endphp
                                    <div id="admin-month-panel-{{ $idx }}" class="admin-month-panel {{ $idx === 0 ? '' : 'hidden' }}">
                                        <div class="p-0 overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorie</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verträge</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grundprovision</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($data['categories'] as $categoryName => $cat)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $categoryName }}</td>
                                                            <td class="px-4 py-2 whitespace-nowrap">{{ number_format($cat['count']) }}</td>
                                                            <td class="px-4 py-2 whitespace-nowrap font-semibold">
                                                                {{ number_format($cat['total'], 2, ',', '.') }}€
                                                                @if(($cat['bonus_total'] ?? 0) > 0)
                                                                    <span class="ml-2 text-xs font-semibold text-green-700 bg-green-100 border border-green-200 rounded px-2 py-0.5">
                                                                        +{{ number_format($cat['bonus_total'] ?? 0, 2, ',', '.') }}€ (Level {{ $cat['level'] ?? 1 }} · {{ $cat['level_count'] ?? 0 }}x)
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @if(($settings->admin_earnings_show_subcategories ?? true) && !empty($cat['subcategories']))
                                                            @foreach($cat['subcategories'] as $subName => $subTotal)
                                                                <tr class="hover:bg-gray-50">
                                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 pl-8">— {{ $subName }}</td>
                                                                    <td class="px-4 py-2 whitespace-nowrap text-sm">{{ number_format($subTotal['count']) }}</td>
                                                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                                        {{ number_format($subTotal['total'], 2, ',', '.') }}€
                                                                        @if(($subTotal['bonus_total'] ?? 0) > 0)
                                                                            <span class="ml-2 text-[10px] font-semibold text-green-700 bg-green-100 border border-green-200 rounded px-1.5 py-0.5">
                                                                                +{{ number_format($subTotal['bonus_total'] ?? 0, 2, ',', '.') }}€ ({{ $subTotal['level_count'] ?? 0 }}x)
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div id="dashboard-right" class="flex flex-col">
                    @if(isset($settings) && ($settings->show_ranking ?? false) && isset($ranking) && $ranking->count())
                        <div id="sec-ranking" class="dashboard-section" data-section="ranking">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-0 mb-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800">
                                        <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                                        Ranking
                                    </h3>
                                </div>
                                @php
                                    $cols = $settings->ranking_metrics ?? ['total_contracts','total_commission','monthly_commission'];
                                @endphp
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mitarbeiter</th>
                                                @if(in_array('total_contracts', $cols))
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verträge</th>
                                                @endif
                                                @if(in_array('total_commission', $cols))
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gesamtprovision</th>
                                                @endif
                                                @if(in_array('monthly_commission', $cols))
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provision (Monat)</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($ranking as $index => $user)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2 whitespace-nowrap font-semibold">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">{{ $user->name }}</td>
                                                    @if(in_array('total_contracts', $cols))
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ number_format($user->total_contracts ?? 0) }}</td>
                                                    @endif
                                                    @if(in_array('total_commission', $cols))
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ number_format($user->total_commission ?? 0, 2) }}€</td>
                                                    @endif
                                                    @if(in_array('monthly_commission', $cols))
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ number_format($user->monthly_commission ?? 0, 2) }}€</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div id="sec-announcements" class="dashboard-section" data-section="announcements">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    <i class="fas fa-bullhorn mr-2 text-red-600"></i>
                                    Neueste Ankündigungen
                                </h3>
                            </div>
                            <div class="space-y-3">
                                @php $latest = collect($announcements ?? [])->sortByDesc('date')->take(3); @endphp
                                @forelse($latest as $idx => $a)
                                    @php
                                        $full = $a['content'] ?? '';
                                        $short = Str::limit($full, 140);
                                        $isTruncated = mb_strlen($full) > mb_strlen($short);
                                    @endphp
                                    <div class="border rounded p-3 {{ $a['type'] === 'info' ? 'bg-blue-50 border-blue-200' : ($a['type'] === 'success' ? 'bg-green-50 border-green-200' : ($a['type'] === 'warning' ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200')) }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <div class="font-semibold text-sm text-gray-800">{{ $a['title'] }}</div>
                                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($a['date'])->format('d.m.Y') }}</div>
                                                </div>
                                                <div class="text-xs text-gray-700 mt-1">
                                                    <span id="ann-prev-{{ $idx }}">{{ $short }}</span>
                                                    @if($isTruncated)
                                                        <span id="ann-full-{{ $idx }}" class="hidden whitespace-pre-line">{{ $full }}</span>
                                                        <button type="button" class="text-xs text-indigo-700 hover:underline ml-1" onclick="toggleAnnouncement({{ $idx }})">Weiterlesen</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500">Keine Ankündigungen vorhanden.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div id="sec-welcome" class="dashboard-section" data-section="welcome">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold text-gray-800">Willkommen im Vodafone Vertragssystem!</h3>
                            </div>
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
                    </div>
                </div>
            </div>

            <style>
                .statistics-tab { @apply text-gray-500 hover:text-gray-700 hover:bg-gray-50; }
                .active-tab { @apply text-red-600 bg-red-50; }
            </style>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for quick nav
            document.querySelectorAll('a[href^="#sec-"]').forEach(a => {
                a.addEventListener('click', (e) => {
                    const target = document.querySelector(a.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            const containers = Array.from(document.querySelectorAll('#dashboard-left, #dashboard-right'));
            // Ensure cards are relatively positioned for absolute controls
            containers.forEach(container => {
                container.querySelectorAll('.dashboard-section').forEach(sec => {
                    let card = sec.querySelector('.bg-white');
                    if (!card) card = sec.firstElementChild;
                    if (card && getComputedStyle(card).position === 'static') {
                        card.style.position = 'relative';
                    }
                });
            });

            // Read initial collapsed from localStorage or server
            let initialFromServer = @json($dashboardCollapsed ?? []);
            let initialFromStorage = [];
            try { initialFromStorage = JSON.parse(localStorage.getItem('dashboardCollapsed') || '[]') || []; } catch(e) { initialFromStorage = []; }
            const collapsedInitial = new Set((initialFromStorage && initialFromStorage.length) ? initialFromStorage : initialFromServer);

            let saveCollapsedAborter = null;
            function saveCollapsedState(collapsedArray) {
                try { localStorage.setItem('dashboardCollapsed', JSON.stringify(collapsedArray)); } catch(e) {}
                try { if (saveCollapsedAborter) saveCollapsedAborter.abort(); } catch(e) {}
                saveCollapsedAborter = new AbortController();
                fetch('{{ route('dashboard.collapsed.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    keepalive: true,
                    signal: saveCollapsedAborter.signal,
                    body: JSON.stringify({ collapsed: collapsedArray })
                }).catch(()=>{});
            }

            // Inject controls (drag handle + collapse toggle) into each card
            containers.forEach(container => {
                container.querySelectorAll('.dashboard-section').forEach(sec => {
                    if (sec.querySelector('.section-controls')) return;
                    let card = sec.querySelector('.bg-white');
                    if (!card) card = sec.firstElementChild;
                    if (!card) return;

                    const controls = document.createElement('div');
                    controls.className = 'section-controls';
                    Object.assign(controls.style, { position: 'absolute', top: '0.5rem', right: '0.5rem', display: 'flex', gap: '0.375rem', zIndex: 30 });

                    const dragBtn = document.createElement('button');
                    dragBtn.type = 'button';
                    dragBtn.className = 'drag-handle text-gray-400 hover:text-gray-600';
                    dragBtn.title = 'Zum Verschieben ziehen';
                    dragBtn.innerHTML = '<i class="fas fa-grip-vertical"></i>';

                    const toggleBtn = document.createElement('button');
                    toggleBtn.type = 'button';
                    toggleBtn.className = 'collapse-toggle text-gray-400 hover:text-gray-600';
                    toggleBtn.title = 'Abschnitt ein-/ausblenden';
                    toggleBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';

                    controls.appendChild(dragBtn);
                    controls.appendChild(toggleBtn);

                    if (card && !card.querySelector('.section-body')) {
                        const bodyWrapper = document.createElement('div');
                        bodyWrapper.className = 'section-body';
                        const children = Array.from(card.children);
                        if (children.length > 0) {
                            const headerLike = children[0] && children[0].className && children[0].className.indexOf('flex') !== -1 && children[0].className.indexOf('justify-between') !== -1;
                            const startIdx = headerLike ? 1 : 0;
                            for (let i = startIdx; i < children.length; i++) {
                                bodyWrapper.appendChild(children[i]);
                            }
                            card.appendChild(bodyWrapper);
                        }
                    }

                    card.appendChild(controls);

                    // initial collapsed
                    const key = sec.dataset.section;
                    const body = card.querySelector('.section-body');
                    if (collapsedInitial.has(key) && body) {
                        body.style.display = 'none';
                        sec.classList.add('is-collapsed');
                        toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    }

                    // toggle
                    toggleBtn.addEventListener('click', () => {
                        const bodyEl = card.querySelector('.section-body');
                        if (!bodyEl) return;
                        const isHidden = bodyEl.style.display === 'none';
                        bodyEl.style.display = isHidden ? '' : 'none';
                        sec.classList.toggle('is-collapsed', !isHidden);
                        toggleBtn.innerHTML = isHidden ? '<i class="fas fa-chevron-up"></i>' : '<i class="fas fa-chevron-down"></i>';
                        clearTimeout(sec._saveCollapsedTimer);
                        sec._saveCollapsedTimer = setTimeout(() => {
                            const current = Array.from(document.querySelectorAll('.dashboard-section.is-collapsed')).map(s => s.dataset.section);
                            saveCollapsedState(current);
                        }, 120);
                    });
                });
            });

            // Apply saved order within each container if present
            const savedOrder = @json($dashboardLayout ?? []);
            if (Array.isArray(savedOrder) && savedOrder.length > 0) {
                const sectionMap = {};
                document.querySelectorAll('.dashboard-section').forEach(sec => { sectionMap[sec.dataset.section] = sec; });
                savedOrder.forEach(key => { if (sectionMap[key] && sectionMap[key].parentElement) sectionMap[key].parentElement.appendChild(sectionMap[key]); });
            }

            // Make containers sortable if present
            containers.forEach(container => {
                Sortable.create(container, {
                    group: { name: 'dashboard', pull: false, put: false },
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'drag-ghost',
                    chosenClass: 'drag-chosen',
                    onEnd: function () {
                        const order = Array.from(document.querySelectorAll('.dashboard-section')).map(sec => sec.dataset.section);
                        fetch('{{ route('dashboard.layout.save') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            keepalive: true,
                            body: JSON.stringify({ order })
                        });
                    }
                });
            });

            // Re-apply collapsed after potential reordering
            (function reapplyCollapsed() {
                let stored = [];
                try { stored = JSON.parse(localStorage.getItem('dashboardCollapsed') || '[]') || []; } catch(e) { stored = []; }
                const collapsedSet = new Set((stored && stored.length) ? stored : (@json($dashboardCollapsed ?? [])));
                document.querySelectorAll('.dashboard-section').forEach(sec => {
                    const key = sec.dataset.section;
                    let card = sec.querySelector('.bg-white');
                    if (!card) card = sec.firstElementChild;
                    const body = card ? card.querySelector('.section-body') : null;
                    const toggle = card ? card.querySelector('.collapse-toggle') : null;
                    if (collapsedSet.has(key) && body) {
                        body.style.display = 'none';
                        sec.classList.add('is-collapsed');
                        if (toggle) toggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    }
                });
            })();

            // Owner monthly by category: disable other filters when cumulative mode is selected
            const ownerMode = document.getElementById('owner-mode');
            const ownerPeriod = document.getElementById('owner-period');
            const ownerMonth = document.getElementById('owner-month');
            const ownerModeBadge = document.getElementById('owner-mode-badge');
            const ownerCumulative = document.getElementById('owner-cumulative');
            const adminMonthPanels = Array.from(document.querySelectorAll('.admin-month-panel'));

            // Prepare cumulative data source from backend map
            const adminCategoryEarningsData = @json($adminCategoryEarnings ?? collect());
            const showSubcategories = @json(($settings->admin_earnings_show_subcategories ?? true));
            const levelsEnabled = @json(($settings->enable_category_levels ?? false));

            function renderCumulativeTable() {
                if (!ownerCumulative) return;
                const tbody = document.getElementById('owner-cumulative-body');
                if (!tbody) return;
                const cumulative = {};

                // Aggregate categories across all months
                Object.keys(adminCategoryEarningsData || {}).forEach(monthKey => {
                    const month = adminCategoryEarningsData[monthKey];
                    if (!month || !month.categories) return;
                    Object.keys(month.categories).forEach(catName => {
                        const c = month.categories[catName];
                        if (!cumulative[catName]) {
                            cumulative[catName] = { total: 0, count: 0, bonus_total: 0, level: c.level || 1, level_count: 0, subcategories: {} };
                        }
                        cumulative[catName].total += Number(c.total || 0);
                        cumulative[catName].count += Number(c.count || 0);
                        cumulative[catName].bonus_total += Number(c.bonus_total || 0);
                        cumulative[catName].level_count += Number(c.level_count || 0);
                        if (showSubcategories && c.subcategories) {
                            Object.keys(c.subcategories).forEach(subName => {
                                if (!cumulative[catName].subcategories[subName]) {
                                    cumulative[catName].subcategories[subName] = { total: 0, count: 0, bonus_total: 0, level_count: 0 };
                                }
                                cumulative[catName].subcategories[subName].total += Number(c.subcategories[subName].total || 0);
                                cumulative[catName].subcategories[subName].count += Number(c.subcategories[subName].count || 0);
                                cumulative[catName].subcategories[subName].bonus_total += Number(c.subcategories[subName].bonus_total || 0);
                                cumulative[catName].subcategories[subName].level_count += Number(c.subcategories[subName].level_count || 0);
                            });
                        }
                    });
                });

                // Sort categories alphabetically for stable rendering
                const categoryNames = Object.keys(cumulative).sort((a,b) => a.localeCompare(b, 'de'));

                // Render rows
                tbody.innerHTML = '';
                categoryNames.forEach(catName => {
                    const cat = cumulative[catName];
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="px-4 py-2 whitespace-nowrap font-medium">${catName}</td>
                        <td class="px-4 py-2 whitespace-nowrap">${(cat.count || 0).toLocaleString('de-DE')}</td>
                        <td class="px-4 py-2 whitespace-nowrap font-semibold">${(cat.total || 0).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}€
                            ${levelsEnabled && (cat.bonus_total || 0) > 0 ? `<span class="ml-2 text-xs font-semibold text-green-700 bg-green-100 border border-green-200 rounded px-2 py-0.5">+${(cat.bonus_total || 0).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}€ (Level ${cat.level || 1} · ${(cat.level_count || 0)}x)</span>` : ''}
                        </td>
                    `;
                    tbody.appendChild(tr);

                    if (showSubcategories) {
                        const subs = Object.keys(cat.subcategories || {}).sort((a,b) => a.localeCompare(b, 'de'));
                        subs.forEach(subName => {
                            const sub = cat.subcategories[subName];
                            const subTr = document.createElement('tr');
                            subTr.className = 'hover:bg-gray-50';
                            subTr.innerHTML = `
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 pl-8">— ${subName}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm">${(sub.count || 0).toLocaleString('de-DE')}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm">${(sub.total || 0).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}€
                                    ${(sub.bonus_total || 0) > 0 ? `<span class="ml-2 text-[10px] font-semibold text-green-700 bg-green-100 border border-green-200 rounded px-1.5 py-0.5">+${(sub.bonus_total || 0).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}€ (${(sub.level_count || 0)}x)</span>` : ''}
                                </td>
                            `;
                            tbody.appendChild(subTr);
                        });
                    }
                });
            }

            function showMonthPanelById(panelId) {
                adminMonthPanels.forEach(p => p.classList.add('hidden'));
                const panel = document.getElementById(panelId);
                if (panel) panel.classList.remove('hidden');
            }

            function applyOwnerMode() {
                if (!ownerMode) return;
                const mode = ownerMode.value;
                const isCumulative = mode === 'cumulative';

                if (ownerPeriod) ownerPeriod.disabled = isCumulative;
                if (ownerMonth) ownerMonth.disabled = isCumulative;
                if (ownerModeBadge) ownerModeBadge.classList.toggle('hidden', !isCumulative);

                if (isCumulative) {
                    if (ownerCumulative) {
                        ownerCumulative.classList.remove('hidden');
                        renderCumulativeTable();
                    }
                    adminMonthPanels.forEach(p => p.classList.add('hidden'));
                } else {
                    if (ownerCumulative) ownerCumulative.classList.add('hidden');
                    // Ensure the currently selected month panel is visible
                    if (ownerMonth) {
                        showMonthPanelById(ownerMonth.value);
                    }
                }
            }

            if (ownerMode) {
                ownerMode.addEventListener('change', applyOwnerMode);
                if (ownerMonth) ownerMonth.addEventListener('change', () => {
                    if (!ownerMode || ownerMode.value === 'cumulative') return;
                    showMonthPanelById(ownerMonth.value);
                });
                // Initialize state on load
                applyOwnerMode();
            }
        });

        function toggleAnnouncement(idx) {
            const prev = document.getElementById('ann-prev-' + idx);
            const full = document.getElementById('ann-full-' + idx);
            if (!prev || !full) return;
            const btn = event.target;
            const isShowingFull = !full.classList.contains('hidden');
            if (isShowingFull) {
                full.classList.add('hidden');
                prev.classList.remove('hidden');
                btn.textContent = 'Weiterlesen';
            } else {
                prev.classList.add('hidden');
                full.classList.remove('hidden');
                btn.textContent = 'Weniger anzeigen';
            }
        }
    </script>

    <style>
        .drag-ghost { background-color: #ef4444 !important; border: 2px dashed #7f1d1d !important; opacity: 0.95 !important; transform: rotate(0.5deg); }
        .drag-chosen { outline: 3px solid #dc2626; outline-offset: -3px; }
    </style>

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
                // Original statistics tabs
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

                // Overall statistics tabs
                const overallTabs = document.querySelectorAll('.overall-statistics-tab');
                const overallPanels = document.querySelectorAll('.overall-statistics-panel');

                overallTabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        overallTabs.forEach(t => {
                            t.classList.remove('active-tab');
                            t.classList.add('border-transparent');
                        });

                        tab.classList.add('active-tab');
                        tab.classList.remove('border-transparent');

                        overallPanels.forEach(panel => {
                            panel.classList.add('hidden');
                        });

                        const targetPanel = document.getElementById(tab.dataset.target);
                        targetPanel.classList.remove('hidden');

                        if (tab.dataset.target === 'overall-monthly-stats') {
                            // Render overall monthly chart
                            var overallOptions = {
                                series: [{
                                    name: 'Verträge (Alle Benutzer)',
                                    data: {!! json_encode($overallMonthlyData) !!}
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
                                        return val.toFixed(0);
                                    }
                                },
                                stroke: {
                                    curve: 'smooth',
                                    width: 2
                                },
                                title: {
                                    text: 'Monatliche Vertragsabschlüsse (Alle Benutzer)',
                                    align: 'left'
                                },
                                xaxis: {
                                    categories: {!! json_encode($overallMonthlyLabels) !!},
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
                                            return val.toFixed(0);
                                        }
                                    }
                                },
                                colors: ['#2563eb'], // Blue color for overall stats
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

                            var overallChart = new ApexCharts(document.querySelector("#overall-monthly-chart"), overallOptions);
                            overallChart.render();
                        }
                    });
                });
            });
        </script>

        <style>
            .statistics-tab {
                @apply text-gray-500 hover:text-gray-700 border-transparent transition duration-300 flex items-center;
            }
            .overall-statistics-tab {
                @apply text-gray-500 hover:text-gray-700 border-transparent transition duration-300 flex items-center;
            }
            .active-tab {
                @apply text-red-600 border-red-600;
            }
            .overall-statistics-tab.active-tab {
                @apply text-blue-600 border-blue-600;
            }
        </style>
    @endif

    <!-- Announcements Modal remains -->
    @if(isset($unseenAnnouncements) && count($unseenAnnouncements) > 0)
        <div id="announcements-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: block;">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-bullhorn mr-2 text-red-600"></i>
                        Neue Ankündigungen
                    </h3>
                    <button onclick="closeAnnouncementsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-3 max-h-96 overflow-y-auto space-y-4">
                    @foreach($unseenAnnouncements as $announcement)
                        <div class="border rounded-lg p-4 {{ $announcement['type'] === 'info' ? 'bg-blue-50 border-blue-200' : ($announcement['type'] === 'success' ? 'bg-green-50 border-green-200' : ($announcement['type'] === 'warning' ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200')) }}">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-800">{{ $announcement['title'] }}</h4>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($announcement['date'])->format('d.m.Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $announcement['content'] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="markAnnouncementsAsRead()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Als gelesen markieren
                    </button>
                </div>
            </div>
        </div>

        <script>
            function closeAnnouncementsModal() {
                document.getElementById('announcements-modal').style.display = 'none';
            }

            function markAnnouncementsAsRead() {
                const ids = @json(array_column($unseenAnnouncements, 'id'));
                
                fetch('{{ route("announcements.mark-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeAnnouncementsModal();
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    closeAnnouncementsModal();
                });
            }

            // Close modal when clicking outside
            document.getElementById('announcements-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAnnouncementsModal();
                }
            });
        </script>
    @endif
</x-app-layout>

