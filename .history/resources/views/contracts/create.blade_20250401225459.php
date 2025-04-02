<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Neuer Vertrag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex justify-center items-center mb-8">
                        <div class="flex items-center">
                            <div id="step1-indicator" class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">1</div>
                            <div class="h-1 w-12 bg-gray-300"></div>
                        </div>
                        <div class="flex items-center">
                            <div id="step2-indicator" class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold">2</div>
                            <div class="h-1 w-12 bg-gray-300"></div>
                        </div>
                        <div class="flex items-center">
                            <div id="step3-indicator" class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold">3</div>
                        </div>
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
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="subcategory_id" value="{{ __('Unterkategorie') }}" />
                                <div class="relative">
                                    <input type="text" 
                                           id="subcategory_search" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" 
                                           placeholder="Unterkategorie suchen..."
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

                    <!-- Schritt 3: Kundeninformationen und Provision -->
                    <div id="step3" class="hidden space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Kundeninformationen</h3>
                            <p class="text-sm text-gray-500">Geben Sie die Kundeninformationen ein</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="customer_name" value="{{ __('Kundenname (optional)') }}" />
                                <x-input id="customer_name" type="text" name="customer_name" class="mt-1 block w-full" :value="old('customer_name')" />
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
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
                            <button type="button" onclick="prevStep(3)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md">
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
            // Sadece adım numaralarının renklerini güncelle
            for (let i = 1; i <= 3; i++) {
                const indicator = document.getElementById(`step${i}-indicator`);
                if (i <= activeStep) {
                    indicator.classList.remove('bg-gray-300');
                    indicator.classList.add('bg-red-600');
                } else {
                    indicator.classList.remove('bg-red-600');
                    indicator.classList.add('bg-gray-300');
                }
            }
        }

        // İleri gitme fonksiyonu
        function nextStep(currentStep) {
            // Geçerli form alanlarını kontrol et
            if (!validateStep(currentStep)) {
                return;
            }

            // Adımları değiştir
            document.getElementById(`step${currentStep}`).classList.add('hidden');
            document.getElementById(`step${currentStep + 1}`).classList.remove('hidden');
            
            // Göstergeleri güncelle
            updateStepIndicators(currentStep + 1);
        }

        // Geri gitme fonksiyonu
        function prevStep(currentStep) {
            document.getElementById(`step${currentStep}`).classList.add('hidden');
            document.getElementById(`step${currentStep - 1}`).classList.remove('hidden');
            
            // Göstergeleri güncelle
            updateStepIndicators(currentStep - 1);
        }

        // Adım validasyonu
        function validateStep(step) {
            switch(step) {
                case 1:
                    const category = document.getElementById('category_id').value;
                    const subcategory = document.getElementById('subcategory_id').value;
                    if (!category || !subcategory) {
                        alert('Bitte wählen Sie eine Haupt- und Unterkategorie aus.');
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

                default:
                    return true;
            }
        }

        // Form resetleme fonksiyonu
        function resetForm() {
            // Form'u sıfırla
            document.getElementById('contractForm').reset();
            
            // Kategori ve alt kategori seçimlerini sıfırla
            document.getElementById('category_id').value = '';
            document.getElementById('subcategory_id').innerHTML = '<option value="">Erst Hauptkategorie wählen</option>';
            
            // Provision'u sıfırla
            document.getElementById('commission_amount').value = '';
            
            // İlk adıma dön
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            
            // Göstergeleri sıfırla
            updateStepIndicators(1);
            
            // Kategori seçimine odaklan
            document.getElementById('category_id').focus();
        }

        // Sayfa yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            updateStepIndicators(1);
            
            // Kategori seçimine otomatik odaklan
            document.getElementById('category_id').focus();
            
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const subcategorySearch = document.getElementById('subcategory_search');
            const commissionInput = document.getElementById('commission_amount');

            // Alt kategori arama event listener'ı
            subcategorySearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const options = subcategorySelect.options;
                
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const text = option.text.toLowerCase();
                    
                    if (text.includes(searchText)) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });

            // Kategori değiştiğinde
            categorySelect.addEventListener('change', async function() {
                const categoryId = this.value;
                subcategorySelect.innerHTML = '<option value="">Unterkategorie wählen</option>';
                subcategorySearch.value = '';
                commissionInput.value = '';

                if (!categoryId) return;

                try {
                    const response = await fetch(`/categories/${categoryId}/subcategories`);
                    const subcategories = await response.json();
                    
                    // Alt kategorileri kullanım sayısına göre sırala
                    subcategories.sort((a, b) => b.usage_count - a.usage_count);
                    
                    subcategories.forEach(subcategory => {
                        const option = new Option(subcategory.name, subcategory.id);
                        option.dataset.baseCommission = subcategory.base_commission;
                        option.dataset.commissionRate = subcategory.commission_rate;
                        subcategorySelect.add(option);
                    });
                } catch (error) {
                    console.error('Fehler:', error);
                }
            });

            // Alt kategori seçildiğinde komisyonu hesapla
            subcategorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    subcategorySearch.value = '';
                    const commissionRate = parseFloat(selectedOption.dataset.commissionRate);
                    const baseCommission = 100; // Sabit değer
                    const calculatedCommission = (baseCommission * commissionRate / 100).toFixed(2);
                    commissionInput.value = calculatedCommission;
                } else {
                    commissionInput.value = '';
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 