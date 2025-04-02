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

    <!-- JavaScript ile form gönderimi sonrası bildirim -->
    <script>
        // Form gönderimi başarılı olduğunda bildirim göster
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                    
                    if (response.ok) {
                        // Başarılı güncelleme bildirimi
                        const successDiv = document.createElement('div');
                        successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded';
                        successDiv.textContent = 'Provision wurde erfolgreich aktualisiert';
                        document.body.appendChild(successDiv);
                        
                        // 3 saniye sonra bildirimi kaldır
                        setTimeout(() => {
                            successDiv.remove();
                        }, 3000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
</x-app-layout> 