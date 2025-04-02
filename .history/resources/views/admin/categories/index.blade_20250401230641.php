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
                        
                        <!-- Main Category Commission Rate Form -->
                        <div class="mb-6">
                            <form id="main-category-form-{{ $mainCategory->id }}" 
                                  action="{{ route('admin.categories.update', $mainCategory) }}" 
                                  method="POST" 
                                  class="commission-form">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-4">
                                    <label class="font-semibold">Provisionssatz für {{ $mainCategory->name }} (%):</label>
                                    <input type="number" 
                                           name="commission_rate" 
                                           value="{{ $mainCategory->commission_rate }}" 
                                           class="w-32 rounded border-gray-300"
                                           step="0.01"
                                           min="0"
                                           max="100">
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Speichern
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Unterkategorie</th>
                                        <th class="px-4 py-2">Grundprovision</th>
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
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    console.log('Form submitted:', form.id);

                    // Form verilerini hazırla
                    const formData = new FormData(form);
                    formData.append('_method', 'PUT');

                    // Debug için form verilerini kontrol et
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    try {
                        // API isteği gönder
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        console.log('Response status:', response.status);
                        
                        // Response'u parse et
                        const data = await response.json();
                        console.log('Response data:', data);

                        if (data.success) {
                            // Form ID'sine göre satırı veya form container'ı bul
                            const formId = form.id;
                            let elementToHighlight;
                            let successMessage = '✓ ';
                            
                            if (formId.startsWith('main-category-form-')) {
                                // Ana kategori formu için container'ı bul
                                elementToHighlight = form.closest('.mt-4');
                                const categoryName = form.querySelector('label').textContent.split(' für ')[1].replace(' (%):', '');
                                successMessage += `Provisionssatz für ${categoryName} wurde aktualisiert`;
                            } else {
                                // Alt kategori formu için satırı bul
                                elementToHighlight = document.getElementById('row-' + formId.split('-')[1]);
                                const categoryName = elementToHighlight.querySelector('td:first-child').textContent;
                                successMessage += `Grundprovision für ${categoryName} wurde aktualisiert`;
                            }

                            if (elementToHighlight) {
                                elementToHighlight.classList.add('bg-green-100');
                                setTimeout(() => {
                                    elementToHighlight.classList.remove('bg-green-100');
                                }, 2000);
                            }

                            // Bildirim göster
                            showNotification(successMessage, 'success');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('✕ Fehler beim Speichern', 'error');
                    }
                });
            });
        });

        // Bildirim gösterme fonksiyonu
        function showNotification(message, type) {
            // Varolan bildirimleri kaldır
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            // Yeni bildirim oluştur
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 px-4 py-3 rounded z-50 ${
                type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 
                'bg-red-100 text-red-700 border border-red-400'
            }`;
            notification.textContent = message;
            
            // Bildirimi sayfaya ekle
            document.body.appendChild(notification);

            // 3 saniye sonra bildirimi kaldır
            setTimeout(() => notification.remove(), 3000);
        }
    </script>
    @endpush
</x-app-layout> 