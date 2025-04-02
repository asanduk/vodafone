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