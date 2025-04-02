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

                    <div class="flex justify-end mt-6 space-x-4">
                        <x-button type="button" class="bg-gray-500 hover:bg-gray-700 px-6" onclick="window.history.back()">
                            <i class="fas fa-times mr-2"></i>{{ __('Abbrechen') }}
                        </x-button>
                        <x-button class="bg-red-600 hover:bg-red-700 px-6">
                            <i class="fas fa-check mr-2"></i>{{ __('Vertrag Erstellen') }}
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
                    
                    // Hesaplanan değeri göster
                    showNotification(`Provision automatisch berechnet: ${calculatedCommission}€`, 'success');
                    
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