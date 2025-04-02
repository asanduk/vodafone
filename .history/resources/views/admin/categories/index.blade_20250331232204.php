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
                                        <tr>
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
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    // Form satırını bul
                    const formRow = form.closest('tr');
                    const inputs = form.querySelectorAll('input[type="number"]');
                    const submitButton = form.querySelector('button[type="submit"]');
                    
                    try {
                        submitButton.disabled = true;
                        const formData = new FormData(form);

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Başarılı güncelleme animasyonu
                            formRow.style.transition = 'background-color 0.5s';
                            formRow.style.backgroundColor = '#f0fdf4'; // Açık yeşil
                            
                            // Input alanlarını yeşil yap
                            inputs.forEach(input => {
                                input.style.transition = 'border-color 0.5s';
                                input.style.borderColor = '#22c55e'; // Yeşil
                            });

                            // 2 saniye sonra normal renge dön
                            setTimeout(() => {
                                formRow.style.backgroundColor = '';
                                inputs.forEach(input => {
                                    input.style.borderColor = '';
                                });
                            }, 2000);

                            // Başarılı bildirim
                            const notification = document.createElement('div');
                            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50 flex items-center';
                            notification.innerHTML = `
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>${data.message}</span>
                            `;
                            document.body.appendChild(notification);

                            // 3 saniye sonra bildirimi kaldır
                            setTimeout(() => {
                                notification.style.transition = 'opacity 0.5s';
                                notification.style.opacity = '0';
                                setTimeout(() => notification.remove(), 500);
                            }, 3000);

                        } else {
                            throw new Error(data.message || 'Ein Fehler ist aufgetreten');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        
                        // Hata bildirimi
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 flex items-center';
                        notification.innerHTML = `
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Ein Fehler ist aufgetreten</span>
                        `;
                        document.body.appendChild(notification);

                        // 3 saniye sonra bildirimi kaldır
                        setTimeout(() => {
                            notification.style.transition = 'opacity 0.5s';
                            notification.style.opacity = '0';
                            setTimeout(() => notification.remove(), 500);
                        }, 3000);
                    } finally {
                        submitButton.disabled = false;
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 