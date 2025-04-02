<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategorien verwalten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @foreach($mainCategories as $mainCategory)
                    <div class="mb-8">
                        <h3 class="text-lg font-bold mb-4">{{ $mainCategory->name }}</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Unterkategorie</th>
                                        <th class="px-4 py-2">Grundprovision</th>
                                        <th class="px-4 py-2">Provisionssatz (%)</th>
                                        <th class="px-4 py-2">Aktion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mainCategory->subcategories as $subcategory)
                                        <tr id="row-{{ $subcategory->id }}" class="transition-colors duration-500">
                                            <td class="border px-4 py-2">{{ $subcategory->name }}</td>
                                            <form id="form-{{ $subcategory->id }}" 
                                                  action="{{ route('admin.categories.update', $subcategory) }}" 
                                                  method="POST" 
                                                  class="commission-form">
                                                @csrf
                                                @method('PUT')
                                                <td class="border px-4 py-2">
                                                    <input type="number" 
                                                           name="base_commission" 
                                                           value="{{ $subcategory->base_commission }}" 
                                                           class="w-full rounded border-gray-300"
                                                           step="0.01"
                                                           min="0">
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <input type="number" 
                                                           name="commission_rate" 
                                                           value="{{ $subcategory->commission_rate }}" 
                                                           class="w-full rounded border-gray-300"
                                                           step="0.01"
                                                           min="0"
                                                           max="100">
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <button type="submit" 
                                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                        Speichern
                                                    </button>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

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
                    // Özeti güncelle
                    updateSummary();
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }

            function updateSummary() {
                const categorySelect = document.getElementById('category_id');
                const subcategorySelect = document.getElementById('subcategory_id');
                const summary = document.getElementById('summary');

                const selectedCategory = categorySelect.options[categorySelect.selectedIndex].text;
                const selectedSubcategory = subcategorySelect.options[subcategorySelect.selectedIndex].text;
                const contractDate = document.getElementById('contract_date').value;
                const contractNumber = document.getElementById('contract_number').value || '-';
                const customerName = document.getElementById('customer_name').value || '-';
                const commission = document.getElementById('commission_amount').value;

                summary.innerHTML = `
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div class="font-semibold text-gray-600">Kategorie:</div>
                        <div>${selectedCategory}</div>
                        
                        <div class="font-semibold text-gray-600">Unterkategorie:</div>
                        <div>${selectedSubcategory}</div>
                        
                        <div class="font-semibold text-gray-600">Vertragsdatum:</div>
                        <div>${new Date(contractDate).toLocaleDateString('de-DE')}</div>
                        
                        <div class="font-semibold text-gray-600">Vertragsnummer:</div>
                        <div>${contractNumber}</div>
                        
                        <div class="font-semibold text-gray-600">Kundenname:</div>
                        <div>${customerName}</div>
                        
                        <div class="font-semibold text-gray-600">Provision:</div>
                        <div class="text-red-600 font-bold">${commission} €</div>
                    </div>
                `;
            }

            // Next butonu için event listener
            nextBtn.addEventListener('click', () => {
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateSteps();
                    }
                }
            });

            // Previous butonu için event listener
            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateSteps();
                }
            });

            // Form submit
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                if (!validateCurrentStep()) {
                    return;
                }

                const formData = new FormData(form);
                
                try {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Wird erstellt...';

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

            // İlk adımı göster
            updateSteps();
        });
    </script>
    @endpush
</x-app-layout> 