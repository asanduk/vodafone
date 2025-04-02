<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Vertrag Details') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('contracts.edit', $contract) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit"></i> {{ __('Bearbeiten') }}
                </a>
                <a href="{{ route('contracts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> {{ __('Zurück') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Vertragsinformationen</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="font-medium">Vertragsnummer:</span>
                                <span class="ml-2">{{ $contract->contract_number }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Kundenname:</span>
                                <span class="ml-2">{{ $contract->customer_name }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Erstellungsdatum:</span>
                                <span class="ml-2">{{ $contract->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Kategorie & Provision</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="font-medium">Hauptkategorie:</span>
                                <span class="ml-2">{{ $contract->category->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Produktname:</span>
                                <span class="ml-2">{{ $contract->subcategory->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Provision:</span>
                                <span class="ml-2">{{ number_format($contract->commission_amount, 2) }}€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 