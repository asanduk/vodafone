<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Neuer Vertrag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Adım göstergesi -->
                <div class="mb-8">
                    <div class="flex items-center justify-between relative">
                        <div class="w-full absolute top-1/2 transform -translate-y-1/2">
                            <div class="h-1 bg-gray-200">
                                <div class="h-1 bg-red-600 transition-all duration-500" id="progress-bar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="flex justify-between relative w-full">
                            <div class="step active">
                                <div class="step-circle">1</div>
                                <div class="step-label">Kategorie</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">2</div>
                                <div class="step-label">Unterkategorie</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">3</div>
                                <div class="step-label">Vertragsdaten</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">4</div>
                                <div class="step-label">Bestätigung</div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="contract-form" action="{{ route('contracts.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Adım 1: Kategorie -->
                    <div class="step-content" id="step-1">
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                            <h3 class="text-lg font-semibold text-blue-800 mb-4">1. Hauptkategorie wählen</h3>
                            <div>
                                <x-label for="category_id" value="{{ __('Hauptkategorie') }}" />
                                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <option value="">Bitte wählen Sie eine Kategorie</option>
                                    @foreach($mainCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Adım 2: Unterkategorie -->
                    <div class="step-content hidden" id="step-2">
                        <div class="bg-green-50 p-6 rounded-lg border border-green-100">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">2. Unterkategorie wählen</h3>
                            <div>
                                <x-label for="subcategory_id" value="{{ __('Unterkategorie') }}" />
                                <select name="subcategory_id" id="subcategory_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <option value="">Erst Hauptkategorie wählen</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Adım 3: Vertragsdaten -->
                    <div class="step-content hidden" id="step-3">
                        <div class="bg-purple-50 p-6 rounded-lg border border-purple-100">
                            <h3 class="text-lg font-semibold text-purple-800 mb-4">3. Vertragsdaten eingeben</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-label for="contract_date" value="{{ __('Vertragsdatum') }}" />
                                    <x-input id="contract_date" type="date" name="contract_date" class="mt-1 block w-full" :value="old('contract_date')" />
                                </div>
                                <div>
                                    <x-label for="contract_number" value="{{ __('Vertragsnummer (optional)') }}" />
                                    <x-input id="contract_number" type="text" name="contract_number" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-label for="customer_name" value="{{ __('Kundenname (optional)') }}" />
                                    <x-input id="customer_name" type="text" name="customer_name" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-label for="commission_amount" value="{{ __('Provision (€)') }}" />
                                    <x-input id="commission_amount" type="number" step="0.01" name="commission_amount" class="mt-1 block w-full bg-gray-100" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Adım 4: Bestätigung -->
                    <div class="step-content hidden" id="step-4">
                        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-100">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-4">4. Überprüfen und bestätigen</h3>
                            <div id="summary" class="space-y-4">
                                <!-- JavaScript ile doldurulacak özet -->
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Butonları -->
                    <div class="flex justify-between mt-6">
                        <button type="button" 
                                id="prev-step" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-2 rounded hidden">
                            <i class="fas fa-arrow-left mr-2"></i>Zurück
                        </button>
                        
                        <button type="button" 
                                id="next-step" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded">
                            Weiter<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        
                        <button type="submit" 
                                id="submit-form" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded hidden">
                            <i class="fas fa-check mr-2"></i>Vertrag erstellen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .step {
            @apply flex flex-col items-center z-10 relative;
        }
        .step-circle {
            @apply w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold;
        }
        .step.active .step-circle {
            @apply bg-red-600 text-white;
        }
        .step.completed .step-circle {
            @apply bg-green-500 text-white;
        }
        .step-label {
            @apply mt-2 text-sm font-medium text-gray-600;
        }
        .step.active .step-label {
            @apply text-red-600;
        }
        .step.completed .step-label {
            @apply text-green-500;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 4;
            const form = document.getElementById('contract-form');
            const nextBtn = document.getElementById('next-step');
            const prevBtn = document.getElementById('prev-step');
            const submitBtn = document.getElementById('submit-form');

            function updateSteps() {
                // Adımları güncelle
                document.querySelectorAll('.step').forEach((step, index) => {
                    const stepNum = index + 1;
                    if (stepNum < currentStep) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                    } else if (stepNum === currentStep) {
                        step.classList.add('active');
                        step.classList.remove('completed');
                    } else {
                        step.classList.remove('completed', 'active');
                    }
                });

                // İçerikleri göster/gizle
                document.querySelectorAll('.step-content').forEach((content, index) => {
                    content.classList.toggle('hidden', index + 1 !== currentStep);
                });

                // Progress bar'ı güncelle
                const progressBar = document.getElementById('progress-bar');
                progressBar.style.width = `${((currentStep - 1) / (totalSteps - 1)) * 100}%`;

                // Butonları güncelle
                prevBtn.classList.toggle('hidden', currentStep === 1);
                
                // Son adımda next butonu gizlenip submit butonu gösterilecek
                if (currentStep === totalSteps) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                    updateSummary(); // Özeti güncelle
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }

            // Next butonu için event listener
            nextBtn.addEventListener('click', () => {
                if (validateCurrentStep()) {
                    currentStep++;
                    updateSteps();
                }
            });

            // Previous butonu için event listener
            prevBtn.addEventListener('click', () => {
                currentStep--;
                updateSteps();
            });

            // İlk yüklemede adımları güncelle
            updateSteps();

            // Form submit işlemi
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                if (!validateCurrentStep()) {
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Wird erstellt...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showNotification('Vertrag wurde erfolgreich erstellt', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect || '/contracts';
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Ein Fehler ist aufgetreten');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification(error.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Vertrag erstellen';
                }
            });

            // Mevcut kategori seçimi kodunuz...
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const commissionInput = document.getElementById('commission_amount');

            categorySelect.addEventListener('change', async function() {
                const categoryId = this.value;
                
                // Alt kategori ve komisyon inputunu sıfırla
                subcategorySelect.innerHTML = '<option value="">Unterkategorie wählen</option>';
                commissionInput.value = '';

                if (!categoryId) return;

                try {
                    const response = await fetch(`/categories/${categoryId}/subcategories`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Fehler beim Laden der Unterkategorien');

                    const subcategories = await response.json();
                    
                    subcategories.forEach(subcategory => {
                        const option = new Option(subcategory.name, subcategory.id);
                        // Komisyon bilgilerini data attribute olarak ekle
                        option.dataset.baseCommission = subcategory.base_commission;
                        option.dataset.commissionRate = subcategory.commission_rate;
                        subcategorySelect.add(option);
                    });

                } catch (error) {
                    console.error('Fehler:', error);
                    showNotification('Fehler beim Laden der Unterkategorien', 'error');
                }
            });

            // Alt kategori seçildiğinde komisyonu otomatik hesapla
            subcategorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (!selectedOption.value) {
                    commissionInput.value = '';
                    return;
                }

                try {
                    const baseCommission = parseFloat(selectedOption.dataset.baseCommission);
                    const commissionRate = parseFloat(selectedOption.dataset.commissionRate);
                    
                    // Komisyonu hesapla: Temel Komisyon * Komisyon Oranı / 100
                    const calculatedCommission = (baseCommission * commissionRate / 100).toFixed(2);
                    commissionInput.value = calculatedCommission;
                    
                    // Hesaplanan değeri göster (opsiyonel)
                    showNotification(`Provision: ${calculatedCommission}€ (${commissionRate}% von ${baseCommission}€)`, 'success');
                    
                } catch (error) {
                    console.error('Fehler bei der Provisionsberechnung:', error);
                    showNotification('Fehler bei der Provisionsberechnung', 'error');
                }
            });
        });

        // Bildirim gösterme fonksiyonu
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-3 rounded z-50 ${
                type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 
                'bg-red-100 text-red-700 border border-red-400'
            }`;
            notification.style.zIndex = '9999';
            notification.textContent = message;
            
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
    </script>
    @endpush
</x-app-layout> 