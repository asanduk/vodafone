<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Neuer Vertrag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('contracts.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategorie -->
                        <div>
                            <x-label for="category_id" value="{{ __('Hauptkategorie') }}" />
                            <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Hauptkategorie wählen</option>
                                @foreach($mainCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unterkategorie -->
                        <div>
                            <x-label for="subcategory_id" value="{{ __('Unterkategorie') }}" />
                            <select name="subcategory_id" id="subcategory_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Erst Hauptkategorie wählen</option>
                            </select>
                            @error('subcategory_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vertragsnummer -->
                        <div>
                            <x-label for="contract_number" value="{{ __('Vertragsnummer') }}" />
                            <x-input id="contract_number" type="text" name="contract_number" class="mt-1 block w-full" required />
                            @error('contract_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kundenname -->
                        <div>
                            <x-label for="customer_name" value="{{ __('Kundenname') }}" />
                            <x-input id="customer_name" type="text" name="customer_name" class="mt-1 block w-full" required />
                            @error('customer_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Provision -->
                        <div>
                            <x-label for="commission_amount" value="{{ __('Provision (€)') }}" />
                            <x-input id="commission_amount" type="number" step="0.01" name="commission_amount" class="mt-1 block w-full" required />
                            @error('commission_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <x-button type="button" class="bg-gray-500" onclick="window.history.back()">
                            {{ __('Abbrechen') }}
                        </x-button>
                        <x-button class="bg-red-600">
                            {{ __('Vertrag Erstellen') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const subcategorySelect = document.getElementById('subcategory_id');
            
            // Reset subcategory options
            subcategorySelect.innerHTML = '<option value="">Unterkategorie wählen</option>';
            
            if (categoryId) {
                fetch(`/categories/${categoryId}/subcategories`)
                    .then(response => response.json())
                    .then(subcategories => {
                        subcategories.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
    @endpush
</x-app-layout> 