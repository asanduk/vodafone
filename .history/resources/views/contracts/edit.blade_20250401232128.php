<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vertrag Bearbeiten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('contracts.update', $contract) }}" method="POST" id="contractForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategorie -->
                        <div>
                            <x-label for="category_id" value="{{ __('Hauptkategorie') }}" />
                            <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($mainCategories as $category)
                                    <option value="{{ $category->id }}" {{ $contract->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Unterkategorie -->
                        <div>
                            <x-label for="subcategory_id" value="{{ __('Produktname') }}" />
                            <select name="subcategory_id" id="subcategory_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" 
                                            {{ $contract->subcategory_id == $subcategory->id ? 'selected' : '' }}
                                            data-base-commission="{{ $subcategory->base_commission }}"
                                            data-commission-rate="{{ $subcategory->commission_rate }}">
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Vertragsnummer -->
                        <div>
                            <x-label for="contract_number" value="{{ __('Vertragsnummer') }}" />
                            <x-input id="contract_number" type="text" name="contract_number" :value="$contract->contract_number" class="mt-1 block w-full" />
                        </div>

                        <!-- Kundenname -->
                        <div>
                            <x-label for="customer_name" value="{{ __('Kundenname') }}" />
                            <x-input id="customer_name" type="text" name="customer_name" :value="$contract->customer_name" class="mt-1 block w-full" />
                        </div>

                        <!-- Vertragsdatum -->
                        <div>
                            <x-label for="contract_date" value="{{ __('Vertragsdatum') }}" />
                            <x-input id="contract_date" type="date" name="contract_date" :value="$contract->contract_date->format('Y-m-d')" class="mt-1 block w-full" />
                        </div>

                        <!-- Provision -->
                        <div>
                            <x-label for="commission_amount" value="{{ __('Provision (€)') }}" />
                            <x-input id="commission_amount" type="number" step="0.01" name="commission_amount" :value="$contract->commission_amount" class="mt-1 block w-full bg-gray-100" readonly />
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle"></i> 
                                Wird automatisch berechnet
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <x-button type="button" class="bg-gray-500" onclick="window.history.back()">
                            {{ __('Abbrechen') }}
                        </x-button>
                        <x-button type="submit" class="bg-red-600">
                            {{ __('Änderungen Speichern') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const commissionInput = document.getElementById('commission_amount');
            const initialCommission = commissionInput.value;

            // Function to calculate commission
            function calculateCommission(subcategoryId) {
                if (!subcategoryId) {
                    commissionInput.value = '';
                    return;
                }

                // Get the selected option
                const selectedOption = subcategorySelect.options[subcategorySelect.selectedIndex];
                if (selectedOption && selectedOption.dataset.baseCommission && selectedOption.dataset.commissionRate) {
                    const baseCommission = parseFloat(selectedOption.dataset.baseCommission);
                    const commissionRate = parseFloat(selectedOption.dataset.commissionRate);
                    const calculatedCommission = (baseCommission * commissionRate / 100).toFixed(2);
                    commissionInput.value = calculatedCommission;
                    return;
                }

                // If no data attributes, fetch from server
                fetch(`/categories/${subcategoryId}/commission`)
                    .then(response => response.json())
                    .then(data => {
                        const baseCommission = parseFloat(data.base_commission);
                        const commissionRate = parseFloat(data.commission_rate);
                        const calculatedCommission = (baseCommission * commissionRate / 100).toFixed(2);
                        commissionInput.value = calculatedCommission;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        commissionInput.value = initialCommission;
                    });
            }

            // Update subcategories when category changes
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                subcategorySelect.innerHTML = '<option value="">Produktname wählen</option>';
                commissionInput.value = '';
                
                if (categoryId) {
                    fetch(`/categories/${categoryId}/subcategories`)
                        .then(response => response.json())
                        .then(subcategories => {
                            subcategories.forEach(subcategory => {
                                const option = document.createElement('option');
                                option.value = subcategory.id;
                                option.textContent = subcategory.name;
                                option.dataset.baseCommission = subcategory.base_commission;
                                option.dataset.commissionRate = subcategory.commission_rate;
                                subcategorySelect.appendChild(option);
                            });
                        });
                }
            });

            // Calculate commission when subcategory changes
            subcategorySelect.addEventListener('change', function() {
                calculateCommission(this.value);
            });

            // Only calculate initial commission if it's empty
            if (!commissionInput.value && subcategorySelect.value) {
                calculateCommission(subcategorySelect.value);
            }
        });
    </script>
    @endpush
</x-app-layout> 