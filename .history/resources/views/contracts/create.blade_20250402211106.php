<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Neuer Vertrag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Progress Bar -->
                <div class="px-4 py-5 sm:p-6">
                    <div class="max-w-3xl mx-auto">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <div id="step1-indicator" class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold transition-all duration-300 transform hover:scale-110">
                                        <span class="text-sm">1</span>
                                    </div>
                                    <div class="ml-2">
                                        <p class="text-sm font-medium text-gray-900">Kategorie</p>
                                    </div>
                                </div>
                                <div class="h-1 w-16 bg-gray-200 rounded-full">
                                    <div id="progress-bar-1" class="h-full bg-red-600 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <div id="step2-indicator" class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold transition-all duration-300 transform hover:scale-110">
                                        <span class="text-sm">2</span>
                                    </div>
                                    <div class="ml-2">
                                        <p class="text-sm font-medium text-gray-500">Vertragsdaten</p>
                                    </div>
                                </div>
                                <div class="h-1 w-16 bg-gray-200 rounded-full">
                                    <div id="progress-bar-2" class="h-full bg-red-600 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div id="step3-indicator" class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold transition-all duration-300 transform hover:scale-110">
                                    <span class="text-sm">3</span>
                                </div>
                                <div class="ml-2">
                                    <p class="text-sm font-medium text-gray-500">Kundeninfo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="contractForm" action="{{ route('contracts.store') }}" method="POST" class="space-y-6 p-6">
                    @csrf
                    
                    <!-- Schritt 1: Kategorie Auswahl -->
                    <div id="step1" class="space-y-6 transition-all duration-300 transform">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">Kategorie wählen</h3>
                            <p class="text-sm text-gray-500 mt-1">Wählen Sie die Haupt- und Unterkategorie aus</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Hauptkategorie</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 ease-in-out">
                                    <option value="">Hauptkategorie wählen</option>
                                    @foreach($mainCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="subcategory_id" class="block text-sm font-medium text-gray-700">Produktname</label>
                                <div class="relative">
                                    <input type="text" 
                                           id="subcategory_search" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 ease-in-out" 
                                           placeholder="Produktname suchen..."
                                           autocomplete="off">
                                    <select name="subcategory_id" 
                                            id="subcategory_id" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 ease-in-out">
                                        <option value="">Erst Hauptkategorie wählen</option>
                                    </select>
                                </div>
                                @error('subcategory_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" onclick="nextStep(1)" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                Weiter
                                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Schritt 2: Vertragsdaten -->
                    <div id="step2" class="hidden space-y-6 transition-all duration-300 transform">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">Vertragsdaten</h3>
                            <p class="text-sm text-gray-500 mt-1">Geben Sie die Vertragsinformationen ein</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="contract_date" class="block text-sm font-medium text-gray-700">Vertragsdatum</label>
                                <input type="date" name="contract_date" id="contract_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 ease-in-out" value="{{ old('contract_date') }}" required>
                                @error('contract_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="contract_number" class="block text-sm font-medium text-gray-700">Vertragsnummer (optional)</label>
                                <input type="text" name="contract_number" id="contract_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 ease-in-out" value="{{ old('contract_number') }}">
                                @error('contract_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" onclick="prevStep(2)" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Zurück
                            </button>
                            <button type="button" onclick="nextStep(2)" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                Weiter
                                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Schritt 3: Kundeninformationen und Provision -->
                    <div id="step3" class="hidden space-y-6 transition-all duration-300 transform">
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">Kundeninformationen</h3>
                            <p class="text-sm text-gray-500 mt-1">Geben Sie die Kundeninformationen ein</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">Kundenname (optional)</label>
                                <input type="text" name="customer_name" id="customer_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 ease-in-out" value="{{ old('customer_name') }}">
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="commission_amount" class="block text-sm font-medium text-gray-700">Provision (€)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="commission_amount" id="commission_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 ease-in-out bg-gray-50" readonly>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">€</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 flex items-center">
                                    <svg class="mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Wird automatisch berechnet
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" onclick="prevStep(3)" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Zurück
                            </button>
                            <div class="flex space-x-3">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                    <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Speichern
                                </button>
                                <button type="button" onclick="resetForm()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                    <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Neuer Vertrag
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
        // Progress bar update function
        function updateProgressBars(activeStep) {
            const progressBars = document.querySelectorAll('[id^="progress-bar-"]');
            progressBars.forEach((bar, index) => {
                if (index < activeStep - 1) {
                    bar.style.width = '100%';
                } else if (index === activeStep - 1) {
                    bar.style.width = '50%';
                } else {
                    bar.style.width = '0%';
                }
            });
        }

        // Step indicator update function
        function updateStepIndicators(activeStep) {
            for (let i = 1; i <= 3; i++) {
                const indicator = document.getElementById(`step${i}-indicator`);
                const stepText = indicator.nextElementSibling.querySelector('p');
                
                if (i <= activeStep) {
                    indicator.classList.remove('bg-gray-200', 'text-gray-600');
                    indicator.classList.add('bg-red-600', 'text-white');
                    stepText.classList.remove('text-gray-500');
                    stepText.classList.add('text-gray-900');
                } else {
                    indicator.classList.remove('bg-red-600', 'text-white');
                    indicator.classList.add('bg-gray-200', 'text-gray-600');
                    stepText.classList.remove('text-gray-900');
                    stepText.classList.add('text-gray-500');
                }
            }
        }

        // Next step function
        function nextStep(currentStep) {
            if (!validateStep(currentStep)) {
                return;
            }

            const currentStepElement = document.getElementById(`step${currentStep}`);
            const nextStepElement = document.getElementById(`step${currentStep + 1}`);

            currentStepElement.classList.add('hidden', 'opacity-0', 'translate-x-full');
            nextStepElement.classList.remove('hidden');
            
            setTimeout(() => {
                nextStepElement.classList.remove('opacity-0', 'translate-x-full');
                updateStepIndicators(currentStep + 1);
                updateProgressBars(currentStep + 1);
            }, 50);
        }

        // Previous step function
        function prevStep(currentStep) {
            const currentStepElement = document.getElementById(`step${currentStep}`);
            const prevStepElement = document.getElementById(`step${currentStep - 1}`);

            currentStepElement.classList.add('opacity-0', '-translate-x-full');
            prevStepElement.classList.remove('hidden');
            
            setTimeout(() => {
                currentStepElement.classList.add('hidden');
                prevStepElement.classList.remove('opacity-0', '-translate-x-full');
                updateStepIndicators(currentStep - 1);
                updateProgressBars(currentStep - 1);
            }, 50);
        }

        // Step validation function
        function validateStep(step) {
            let isValid = true;
            let errorMessage = '';

            switch(step) {
                case 1:
                    const category = document.getElementById('category_id').value;
                    const subcategory = document.getElementById('subcategory_id').value;
                    if (!category || !subcategory) {
                        errorMessage = 'Bitte wählen Sie eine Hauptkategorie und einen Produktnamen aus.';
                        isValid = false;
                    }
                    break;

                case 2:
                    const date = document.getElementById('contract_date').value;
                    if (!date) {
                        errorMessage = 'Bitte geben Sie ein Vertragsdatum ein.';
                        isValid = false;
                    }
                    break;
            }

            if (!isValid) {
                showErrorToast(errorMessage);
            }

            return isValid;
        }

        // Error toast function
        function showErrorToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded-md shadow-lg transform transition-all duration-300 translate-y-full opacity-0';
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-y-full', 'opacity-0');
            }, 50);

            setTimeout(() => {
                toast.classList.add('translate-y-full', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Form reset function
        function resetForm() {
            document.getElementById('contractForm').reset();
            document.getElementById('subcategory_id').innerHTML = '<option value="">Erst Hauptkategorie wählen</option>';
            document.getElementById('commission_amount').value = '';
            
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            
            updateStepIndicators(1);
            updateProgressBars(1);
            
            document.getElementById('category_id').focus();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStepIndicators(1);
            updateProgressBars(1);
            document.getElementById('category_id').focus();
            
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const subcategorySearch = document.getElementById('subcategory_search');
            const commissionInput = document.getElementById('commission_amount');

            // Subcategory search functionality
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

            // Category change handler
            categorySelect.addEventListener('change', async function() {
                const categoryId = this.value;
                subcategorySelect.innerHTML = '<option value="">Produktname wählen</option>';
                subcategorySearch.value = '';
                commissionInput.value = '';

                if (!categoryId) return;

                try {
                    const response = await fetch(`/categories/${categoryId}/subcategories`);
                    const subcategories = await response.json();
                    
                    subcategories.sort((a, b) => b.usage_count - a.usage_count);
                    
                    subcategories.forEach(subcategory => {
                        const option = new Option(subcategory.name, subcategory.id);
                        option.dataset.baseCommission = subcategory.base_commission;
                        option.dataset.commissionRate = subcategory.commission_rate;
                        subcategorySelect.add(option);
                    });
                } catch (error) {
                    console.error('Error:', error);
                    showErrorToast('Fehler beim Laden der Produktnamen');
                }
            });

            // Subcategory change handler
            subcategorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    subcategorySearch.value = '';
                    const baseCommission = parseFloat(selectedOption.dataset.baseCommission);
                    const commissionRate = parseFloat(selectedOption.dataset.commissionRate);
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