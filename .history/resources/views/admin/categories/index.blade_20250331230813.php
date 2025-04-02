<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Provision Verwaltung') }}
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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hauptkategorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unterkategorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Basis Provision (€)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Provision Rate (%)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aktion
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($mainCategories as $mainCategory)
                                <!-- Ana kategori satırı -->
                                <tr class="bg-gray-50">
                                    <td colspan="5" class="px-6 py-4 font-semibold text-red-600">
                                        {{ $mainCategory->name }}
                                    </td>
                                </tr>
                                <!-- Alt kategoriler -->
                                @foreach($mainCategory->subcategories as $subcategory)
                                    <tr>
                                        <td class="px-6 py-4"></td>
                                        <td class="px-6 py-4">{{ $subcategory->name }}</td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('admin.categories.update', $subcategory) }}" 
                                                  method="POST" 
                                                  class="flex items-center space-x-2"
                                                  id="form-{{ $subcategory->id }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" 
                                                       name="base_commission" 
                                                       value="{{ $subcategory->base_commission }}" 
                                                       class="w-24 rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" 
                                                       step="0.01"
                                                       min="0">
                                        </td>
                                        <td class="px-6 py-4">
                                                <input type="number" 
                                                       name="commission_rate" 
                                                       value="{{ $subcategory->commission_rate }}" 
                                                       class="w-20 rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" 
                                                       step="0.01"
                                                       min="0"
                                                       max="100">
                                        </td>
                                        <td class="px-6 py-4">
                                                <button type="submit" 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                                                    <i class="fas fa-save mr-2"></i>Speichern
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript kısmını güncelleyin -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const submitButton = form.querySelector('button[type="submit"]');
                    submitButton.disabled = true; // Butonu devre dışı bırak
                    
                    try {
                        const formData = new FormData(form);
                        formData.append('_method', 'PUT'); // Laravel için PUT metodu

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            // Başarılı bildirim
                            const notification = document.createElement('div');
                            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                            notification.innerHTML = `
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>${data.message}</span>
                                </div>
                            `;
                            document.body.appendChild(notification);

                            // 3 saniye sonra bildirimi kaldır
                            setTimeout(() => {
                                notification.remove();
                            }, 3000);
                        } else {
                            throw new Error(data.message || 'Ein Fehler ist aufgetreten');
                        }
                    } catch (error) {
                        // Hata bildirimi
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
                        notification.innerHTML = `
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>${error.message}</span>
                            </div>
                        `;
                        document.body.appendChild(notification);

                        setTimeout(() => {
                            notification.remove();
                        }, 3000);
                    } finally {
                        submitButton.disabled = false; // Butonu tekrar aktif et
                    }
                });
            });
        });
    </script>
</x-app-layout> 