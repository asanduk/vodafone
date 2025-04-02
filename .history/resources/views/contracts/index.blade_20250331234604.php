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

            <!-- Filtreleme Formu -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Tarih Filtresi -->
                    <div>
                        <x-label for="date_from" value="{{ __('Datum von') }}" />
                        <x-input type="date" name="date_from" value="{{ request('date_from') }}" 
                                class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-label for="date_to" value="{{ __('Datum bis') }}" />
                        <x-input type="date" name="date_to" value="{{ request('date_to') }}" 
                                class="mt-1 block w-full" />
                    </div>

                    <!-- Provision Filtresi -->
                    <div>
                        <x-label for="commission_min" value="{{ __('Min. Provision') }}" />
                        <x-input type="number" step="0.01" name="commission_min" 
                                value="{{ request('commission_min') }}" 
                                class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-label for="commission_max" value="{{ __('Max. Provision') }}" />
                        <x-input type="number" step="0.01" name="commission_max" 
                                value="{{ request('commission_max') }}" 
                                class="mt-1 block w-full" />
                    </div>

                    <!-- Sıralama -->
                    <div>
                        <x-label for="sort" value="{{ __('Sortierung') }}" />
                        <select name="sort" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">{{ __('Standardsortierung') }}</option>
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>
                                {{ __('Datum aufsteigend') }}
                            </option>
                            <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>
                                {{ __('Datum absteigend') }}
                            </option>
                            <option value="commission_asc" {{ request('sort') == 'commission_asc' ? 'selected' : '' }}>
                                {{ __('Provision aufsteigend') }}
                            </option>
                            <option value="commission_desc" {{ request('sort') == 'commission_desc' ? 'selected' : '' }}>
                                {{ __('Provision absteigend') }}
                            </option>
                            <option value="customer_asc" {{ request('sort') == 'customer_asc' ? 'selected' : '' }}>
                                {{ __('Kundenname A-Z') }}
                            </option>
                            <option value="customer_desc" {{ request('sort') == 'customer_desc' ? 'selected' : '' }}>
                                {{ __('Kundenname Z-A') }}
                            </option>
                        </select>
                    </div>

                    <!-- Arama -->
                    <div>
                        <x-label for="search" value="{{ __('Suche') }}" />
                        <x-input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Vertragsnummer oder Kundenname"
                                class="mt-1 block w-full" />
                    </div>

                    <!-- Filtreleme Butonları -->
                    <div class="md:col-span-2 flex items-end space-x-4">
                        <x-button class="bg-red-600 hover:bg-red-700">
                            <i class="fas fa-filter mr-2"></i>{{ __('Filtern') }}
                        </x-button>
                        <a href="{{ route('contracts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-md">
                            <i class="fas fa-times mr-2"></i>{{ __('Zurücksetzen') }}
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