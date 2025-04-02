                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provision (%)</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unterkategorien</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($mainCategories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $category->commission_rate }}%</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @foreach($category->subcategories as $subcategory)
                                            <div class="mb-1">{{ $subcategory->name }}</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->commission_rate }})" class="text-red-600 hover:text-red-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteCategory({{ $category->id }})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

    <script>
        function editCategory(id, name, commissionRate) {
            document.getElementById('edit_category_id').value = id;
            document.getElementById('edit_category_name').value = name;
            document.getElementById('edit_commission_rate').value = commissionRate;
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }

        function deleteCategory(id) {
            if (confirm('Möchten Sie diese Kategorie wirklich löschen?')) {
                document.getElementById('delete_category_id').value = id;
                document.getElementById('deleteCategoryForm').submit();
            }
        }

        function closeEditModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
        }
    </script>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Kategorie bearbeiten</h3>
                <form action="{{ route('categories.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_category_id" name="id">
                    
                    <div class="mb-4">
                        <label for="edit_category_name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="edit_category_name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>

                    <div class="mb-4">
                        <label for="edit_commission_rate" class="block text-sm font-medium text-gray-700">Provision (%)</label>
                        <input type="number" step="0.01" id="edit_commission_rate" name="commission_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Abbrechen
                        </button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                            Speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> 