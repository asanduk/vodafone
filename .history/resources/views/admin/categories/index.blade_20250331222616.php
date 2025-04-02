<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Provision Verwaltung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unterkategorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Basis Provision (€)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provision Rate (%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categories as $category)
                                <tr>
                                    <td class="px-6 py-4">{{ $category->name }}</td>
                                    <td class="px-6 py-4">
                                        @foreach($category->subcategories as $subcategory)
                                            <div class="mb-2">
                                                <form action="{{ route('admin.categories.update', $subcategory) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <span>{{ $subcategory->name }}</span>
                                                    <input type="number" name="base_commission" value="{{ $subcategory->base_commission }}" 
                                                           class="w-24 rounded-md border-gray-300" step="0.01">
                                                    <input type="number" name="commission_rate" value="{{ $subcategory->commission_rate }}" 
                                                           class="w-20 rounded-md border-gray-300" step="0.01">
                                                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 