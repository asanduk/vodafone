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
            
            forms.forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const submitButton = e.target.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        
                        try {
                            const formData = new FormData(form);
                            formData.append('_method', 'PUT');

                            const response = await fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });

                            const data = await response.json();
                            
                            // Bildirim göster
                            const notification = document.createElement('div');
                            if (response.ok) {
                                notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                                notification.innerHTML = `
                                    <div class="flex items-center">
                                        <span>${data.message}</span>
                                    </div>
                                `;
                            } else {
                                notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
                                notification.innerHTML = `
                                    <div class="flex items-center">
                                        <span>${data.message}</span>
                                    </div>
                                `;
                            }
                            
                            document.body.appendChild(notification);
                            setTimeout(() => notification.remove(), 3000);
                            
                        } catch (error) {
                            console.error('Error:', error);
                            const notification = document.createElement('div');
                            notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
                            notification.innerHTML = `
                                <div class="flex items-center">
                                    <span>Ein Fehler ist aufgetreten</span>
                                </div>
                            `;
                            document.body.appendChild(notification);
                            setTimeout(() => notification.remove(), 3000);
                        } finally {
                            submitButton.disabled = false;
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 