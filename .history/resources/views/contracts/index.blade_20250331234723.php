<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Verträge') }}
            </h2>
            <a href="{{ route('contracts.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus"></i> {{ __('Neuer Vertrag') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filtreleme Formu - Basitleştirilmiş versiyon -->
            <div class="bg-white shadow-sm rounded-lg p-4 mb-4">
                <form method="GET" class="flex flex-wrap gap-2 items-end">
                    <!-- Tarih Aralığı -->
                    <div class="flex-1 min-w-[200px]">
                        <x-label for="date_range" value="{{ __('Zeitraum') }}" class="text-sm" />
                        <select name="date_range" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">Alle Zeiträume</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Heute</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Diese Woche</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Dieser Monat</option>
                            <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>Dieses Jahr</option>
                        </select>
                    </div>

                    <!-- Sıralama -->
                    <div class="flex-1 min-w-[200px]">
                        <x-label for="sort" value="{{ __('Sortierung') }}" class="text-sm" />
                        <select name="sort" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="date_desc">Neueste zuerst</option>
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Älteste zuerst</option>
                            <option value="commission_desc" {{ request('sort') == 'commission_desc' ? 'selected' : '' }}>Höchste Provision</option>
                            <option value="commission_asc" {{ request('sort') == 'commission_asc' ? 'selected' : '' }}>Niedrigste Provision</option>
                        </select>
                    </div>

                    <!-- Arama -->
                    <div class="flex-1 min-w-[200px]">
                        <x-label for="search" value="{{ __('Suche') }}" class="text-sm" />
                        <x-input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Vertragsnr. oder Kunde"
                                class="w-full text-sm" />
                    </div>

                    <!-- Butonlar -->
                    <div class="flex gap-2">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('contracts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-md text-sm">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vertragsnummer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kunde
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unterkategorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Provision
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Datum
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aktionen
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($contracts as $contract)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contract->contract_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contract->customer_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contract->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contract->subcategory->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($contract->commission_amount, 2) }}€
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contract->created_at->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('contracts.edit', $contract) }}" class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Sind Sie sicher?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Keine Verträge gefunden
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4">
                    {{ $contracts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 