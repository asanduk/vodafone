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
            const forms = document.querySelectorAll('.commission-form');
            console.log('Forms found:', forms.length);

            forms.forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    // Form verilerini hazırla
                    const formData = new FormData(form);
                    
                    try {
                        // Submit butonunu devre dışı bırak
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Wird erstellt...';

                        // AJAX isteği gönder
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
                            // Başarılı
                            showNotification('Vertrag wurde erfolgreich erstellt', 'success');
                            
                            // 1 saniye bekle ve yönlendir
                            setTimeout(() => {
                                window.location.href = data.redirect || '/contracts';
                            }, 1000);
                        } else {
                            // Hata durumu
                            throw new Error(data.message || 'Ein Fehler ist aufgetreten');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification(error.message, 'error');
                        
                        // Submit butonunu tekrar aktif et
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Vertrag erstellen';
                    }
                });
            });

            // Adım geçişlerinde validasyon
            nextBtn.addEventListener('click', () => {
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateSteps();
                    }
                }
            });

            // Adım validasyonu
            function validateCurrentStep() {
                switch(currentStep) {
                    case 1:
                        if (!categorySelect.value) {
                            showNotification('Bitte wählen Sie eine Kategorie', 'error');
                            return false;
                        }
                        return true;

                    case 2:
                        if (!subcategorySelect.value) {
                            showNotification('Bitte wählen Sie eine Unterkategorie', 'error');
                            return false;
                        }
                        return true;

                    case 3:
                        const contractDate = document.getElementById('contract_date');
                        if (!contractDate.value) {
                            showNotification('Bitte geben Sie das Vertragsdatum ein', 'error');
                            return false;
                        }
                        return true;

                    default:
                        return true;
                }
            }

            // Bildirim fonksiyonu güncellendi
            function showNotification(message, type) {
                // Varolan bildirimleri kaldır
                const existingNotifications = document.querySelectorAll('.notification');
                existingNotifications.forEach(n => n.remove());

                const notification = document.createElement('div');
                notification.className = `notification fixed top-4 right-4 px-4 py-3 rounded z-50 flex items-center ${
                    type === 'success' 
                        ? 'bg-green-100 text-green-700 border border-green-400' 
                        : 'bg-red-100 text-red-700 border border-red-400'
                }`;
                
                // İkon ekle
                const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
                notification.innerHTML = `
                    <i class="fas fa-${icon} mr-2"></i>
                    <span>${message}</span>
                `;
                
                document.body.appendChild(notification);
                
                // Animasyonlu giriş
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                notification.style.transition = 'all 0.3s ease-in-out';
                
                setTimeout(() => {
                    notification.style.opacity = '1';
                    notification.style.transform = 'translateY(0)';
                }, 100);

                // Animasyonlu çıkış
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        });
    </script>
    @endpush
</x-app-layout> 