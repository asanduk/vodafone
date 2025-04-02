<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Neuer Vertrag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Progress Steps -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div id="step1-indicator" class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">1</div>
                            <div class="h-1 w-16 bg-red-600"></div>
                        </div>
                        <div class="flex items-center">
                            <div id="step2-indicator" class="w-10 h-10 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold">2</div>
                            <div class="h-1 w-16 bg-gray-300"></div>
                        </div>
                        <div class="flex items-center">
                            <div id="step3-indicator" class="w-10 h-10 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold">3</div>
                            <div class="h-1 w-16 bg-gray-300"></div>
                        </div>
                        <div class="flex items-center">
                            <div id="step4-indicator" class="w-10 h-10 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold">4</div>
                            <div class="h-1 w-16 bg-gray-300"></div>
                        </div>
                        <div class="flex items-center">
                            <div id="step5-indicator" class="w-10 h-10 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold">5</div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-sm text-gray-600">Kategorie</span>
                        <span class="text-sm text-gray-600">Vertrag</span>
                        <span class="text-sm text-gray-600">Kunde</span>
                        <span class="text-sm text-gray-600">Zusatz</span>
                        <span class="text-sm text-gray-600">Übersicht</span>
                    </div>
                </div>

                <form id="contractForm" action="{{ route('contracts.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Schritt 1: Kategorie Auswahl -->
                    <div id="step1" class="space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Kategorie wählen</h3>
                            <p class="text-sm text-gray-500">Wählen Sie die Haupt- und Unterkategorie aus</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="category_id" value="{{ __('Hauptkategorie') }}" />
                                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <option value="">Hauptkategorie wählen</option>
                                    @foreach($mainCategories as $category)
                                        <option value="{{ $category->id }}" 
                                                data-base-commission="{{ $category->base_commission }}"
                                                data-commission-rate="{{ $category->commission_rate }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="subcategory_id" value="{{ __('Produktname') }}" />
                                <div class="relative">
                                    <input type="text" 
                                           id="subcategory_search" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" 
                                           placeholder="Produktname suchen..."
                                           autocomplete="off">
                                    <select name="subcategory_id" 
                                            id="subcategory_id" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                        <option value="">Erst Hauptkategorie wählen</option>
                                    </select>
                                </div>
                                @error('subcategory_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" onclick="nextStep(1)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-md">
                                Weiter <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Schritt 2: Vertragsdaten -->
                    <div id="step2" class="hidden space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Vertragsdaten</h3>
                            <p class="text-sm text-gray-500">Geben Sie die Vertragsinformationen ein</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="contract_date" value="{{ __('Vertragsdatum') }}" />
                                <x-input id="contract_date" type="date" name="contract_date" class="mt-1 block w-full" :value="old('contract_date')" required />
                                @error('contract_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="contract_number" value="{{ __('Vertragsnummer (optional)') }}" />
                                <x-input id="contract_number" type="text" name="contract_number" class="mt-1 block w-full" :value="old('contract_number')" />
                                @error('contract_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="contract_type" value="{{ __('Vertragstyp') }}" />
                                <select name="contract_type" id="contract_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <option value="new">Neukunde</option>
                                    <option value="upgrade">Upgrade</option>
                                    <option value="extension">Verlängerung</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" onclick="prevStep(2)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md">
                                <i class="fas fa-arrow-left mr-2"></i> Zurück
                            </button>
                            <button type="button" onclick="nextStep(2)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-md">
                                Weiter <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Schritt 3: Kundeninformationen -->
                    <div id="step3" class="hidden space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Kundeninformationen</h3>
                            <p class="text-sm text-gray-500">Geben Sie die Kundeninformationen ein</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="customer_name" value="{{ __('Kundenname') }}" />
                                <x-input id="customer_name" type="text" name="customer_name" class="mt-1 block w-full" :value="old('customer_name')" required />
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="customer_email" value="{{ __('E-Mail') }}" />
                                <x-input id="customer_email" type="email" name="customer_email" class="mt-1 block w-full" :value="old('customer_email')" />
                            </div>

                            <div>
                                <x-label for="customer_phone" value="{{ __('Telefon') }}" />
                                <x-input id="customer_phone" type="tel" name="customer_phone" class="mt-1 block w-full" :value="old('customer_phone')" />
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" onclick="prevStep(3)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md">
                                <i class="fas fa-arrow-left mr-2"></i> Zurück
                            </button>
                            <button type="button" onclick="nextStep(3)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-md">
                                Weiter <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Schritt 4: Zusätzliche Informationen -->
                    <div id="step4" class="hidden space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Zusätzliche Informationen</h3>
                            <p class="text-sm text-gray-500">Geben Sie zusätzliche Informationen ein</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="notes" value="{{ __('Notizen') }}" />
                                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('notes') }}</textarea>
                            </div>

                            <div>
                                <x-label for="commission_amount" value="{{ __('Provision (€)') }}" />
                                <x-input id="commission_amount" type="number" step="0.01" name="commission_amount" class="mt-1 block w-full bg-gray-100" readonly />
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-info-circle"></i> 
                                    Wird automatisch berechnet
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" onclick="prevStep(4)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md">
                                <i class="fas fa-arrow-left mr-2"></i> Zurück
                            </button>
                            <button type="button" onclick="nextStep(4)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-md">
                                Weiter <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Schritt 5: Übersicht -->
                    <div id="step5" class="hidden space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Übersicht</h3>
                            <p class="text-sm text-gray-500">Überprüfen Sie die eingegebenen Informationen</p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-700">Kategorie</h4>
                                    <p id="summary_category" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-700">Produkt</h4>
                                    <p id="summary_subcategory" class="text-gray-900"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-700">Vertragsdatum</h4>
                                    <p id="summary_date" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-700">Vertragstyp</h4>
                                    <p id="summary_type" class="text-gray-900"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-700">Kundenname</h4>
                                    <p id="summary_customer" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-700">Provision</h4>
                                    <p id="summary_commission" class="text-gray-900"></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" onclick="prevStep(5)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md">
                                <i class="fas fa-arrow-left mr-2"></i> Zurück
                            </button>
                            <div class="flex space-x-3">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-md">
                                    <i class="fas fa-check mr-2"></i> Speichern
                                </button>
                                <button type="button" onclick="resetForm()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md">
                                    <i class="fas fa-plus mr-2"></i> Neuer Vertrag
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Adım göstergesini güncelleyen fonksiyon
        function updateStepIndicators(activeStep) {
            for (let i = 1; i <= 5; i++) {
                const indicator = document.getElementById(`step${i}-indicator`);
                const line = indicator.nextElementSibling;
                
                if (i <= activeStep) {
                    indicator.classList.remove('bg-gray-300');
                    indicator.classList.add('bg-red-600');
                    if (line) {
                        line.classList.remove('bg-gray-300');
                        line.classList.add('bg-red-600');
                    }
                } else {
                    indicator.classList.remove('bg-red-600');
                    indicator.classList.add('bg-gray-300');
                    if (line) {
                        line.classList.remove('bg-red-600');
                        line.classList.add('bg-gray-300');
                    }
                }
            }
        }

        // İleri gitme fonksiyonu
        function nextStep(currentStep) {
            if (!validateStep(currentStep)) {
                return;
            }

            document.getElementById(`step${currentStep}`).classList.add('hidden');
            document.getElementById(`step${currentStep + 1}`).classList.remove('hidden');
            
            updateStepIndicators(currentStep + 1);

            // Son adımda özeti güncelle
            if (currentStep + 1 === 5) {
                updateSummary();
            }
        }

        // Geri gitme fonksiyonu
        function prevStep(currentStep) {
            document.getElementById(`step${currentStep}`).classList.add('hidden');
            document.getElementById(`step${currentStep - 1}`).classList.remove('hidden');
            
            updateStepIndicators(currentStep - 1);
        }

        // Adım validasyonu
        function validateStep(step) {
            switch(step) {
                case 1:
                    const category = document.getElementById('category_id').value;
                    const subcategory = document.getElementById('subcategory_id').value;
                    if (!category || !subcategory) {
                        alert('Bitte wählen Sie eine Hauptkategorie und einen Produktnamen aus.');
                        return false;
                    }
                    return true;

                case 2:
                    const date = document.getElementById('contract_date').value;
                    if (!date) {
                        alert('Bitte geben Sie ein Vertragsdatum ein.');
                        return false;
                    }
                    return true;

                case 3:
                    const customerName = document.getElementById('customer_name').value;
                    if (!customerName) {
                        alert('Bitte geben Sie einen Kundenname ein.');
                        return false;
                    }
                    return true;

                default:
                    return true;
            }
        }

        // Özet güncelleme
        function updateSummary() {
            document.getElementById('summary_category').textContent = document.getElementById('category_id').options[document.getElementById('category_id').selectedIndex].text;
            document.getElementById('summary_subcategory').textContent = document.getElementById('subcategory_id').options[document.getElementById('subcategory_id').selectedIndex].text;
            document.getElementById('summary_date').textContent = document.getElementById('contract_date').value;
            document.getElementById('summary_type').textContent = document.getElementById('contract_type').options[document.getElementById('contract_type').selectedIndex].text;
            document.getElementById('summary_customer').textContent = document.getElementById('customer_name').value;
            document.getElementById('summary_commission').textContent = document.getElementById('commission_amount').value + ' €';
        }

        // Form resetleme fonksiyonu
        function resetForm() {
            document.getElementById('contractForm').reset();
            document.getElementById('category_id').value = '';
            document.getElementById('subcategory_id').innerHTML = '<option value="">Erst Hauptkategorie wählen</option>';
            document.getElementById('commission_amount').value = '';
            
            document.getElementById('step5').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            
            updateStepIndicators(1);
            document.getElementById('category_id').focus();
        }

        // Sayfa yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            updateStepIndicators(1);
            
            // Kategori değiştiğinde alt kategorileri güncelle
            document.getElementById('category_id').addEventListener('change', function() {
                const categoryId = this.value;
                const subcategorySelect = document.getElementById('subcategory_id');
                
                if (categoryId) {
                    fetch(`/categories/${categoryId}/subcategories`)
                        .then(response => response.json())
                        .then(data => {
                            subcategorySelect.innerHTML = '<option value="">Produktname wählen</option>';
                            data.forEach(subcategory => {
                                subcategorySelect.innerHTML += `<option value="${subcategory.id}" 
                                    data-base-commission="${subcategory.base_commission}"
                                    data-commission-rate="${subcategory.commission_rate}">
                                    ${subcategory.name}
                                </option>`;
                            });
                        });
                } else {
                    subcategorySelect.innerHTML = '<option value="">Erst Hauptkategorie wählen</option>';
                }
            });

            // Alt kategori değiştiğinde komisyonu hesapla
            document.getElementById('subcategory_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const baseCommission = parseFloat(selectedOption.dataset.baseCommission);
                    const commissionRate = parseFloat(selectedOption.dataset.commissionRate);
                    const commission = (baseCommission * commissionRate / 100).toFixed(2);
                    document.getElementById('commission_amount').value = commission;
                } else {
                    document.getElementById('commission_amount').value = '';
                }
            });

            // Alt kategori arama
            document.getElementById('subcategory_search').addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const options = document.getElementById('subcategory_id').options;
                
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const text = option.text.toLowerCase();
                    option.style.display = text.includes(searchText) ? '' : 'none';
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 