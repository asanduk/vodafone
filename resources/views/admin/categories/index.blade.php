<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategorien verwalten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notification Container -->
            <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Kategori Ekleme Bölümü -->
                <div class="mb-8 border border-gray-200 rounded-lg bg-blue-50">
                    <button type="button" 
                            class="toggle-add-section w-full text-left p-6 bg-blue-50 hover:bg-blue-100 transition-colors duration-200"
                            style="border: none; cursor: pointer;">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Neuen Bereich hinzufügen</h3>
                            <svg class="w-6 h-6 toggle-add-icon text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    
                    <!-- Formlar (Başlangıçta gizli) -->
                    <div id="add-section-forms" class="hidden px-6 pb-6">
                    
                    <!-- Ana Kategori Ekleme Formu -->
                    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
                        <h4 class="font-semibold text-gray-700 mb-3">Hauptbereich hinzufügen</h4>
                        <form id="add-main-category-form" action="{{ route('admin.categories.store') }}" method="POST" class="add-category-form">
                            @csrf
                            <div class="flex items-center gap-4">
                                <input type="text" 
                                       name="name" 
                                       placeholder="Hauptbereich Name" 
                                       class="flex-1 rounded border-gray-300"
                                       required>
                                <input type="number" 
                                       name="commission_rate" 
                                       placeholder="Provisionssatz (%)" 
                                       class="w-32 rounded border-gray-300"
                                       step="0.01"
                                       min="0"
                                       max="100">
                                <button type="submit" 
                                        class="bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                        style="background-color: #2563eb; color: white; border: none; cursor: pointer;">
                                    Hauptbereich hinzufügen
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Alt Kategori Ekleme Formu -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h4 class="font-semibold text-gray-700 mb-3">Unterbereich hinzufügen</h4>
                        <form id="add-subcategory-form" action="{{ route('admin.categories.store') }}" method="POST" class="add-category-form">
                            @csrf
                            <div class="flex items-center gap-4">
                                <select name="parent_id" 
                                        class="w-48 rounded border-gray-300"
                                        required>
                                    <option value="">Hauptbereich wählen</option>
                                    @foreach($mainCategories as $mainCategory)
                                        <option value="{{ $mainCategory->id }}">{{ $mainCategory->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" 
                                       name="name" 
                                       placeholder="Unterbereich Name" 
                                       class="flex-1 rounded border-gray-300"
                                       required>
                                <input type="number" 
                                       name="base_commission" 
                                       placeholder="Grundprovision (€)" 
                                       class="w-32 rounded border-gray-300"
                                       step="0.01"
                                       min="0">
                                <button type="submit" 
                                        class="bg-green-600 text-white font-bold py-2 px-4 rounded"
                                        style="background-color: #16a34a; color: white; border: none; cursor: pointer;">
                                    Unterbereich hinzufügen
                                </button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>

                @foreach($mainCategories as $mainCategory)
                    <div class="mb-8 border border-gray-200 rounded-lg p-6 bg-gray-50">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">{{ $mainCategory->name }}</h3>
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        class="delete-category bg-red-600 text-white font-semibold py-2 px-4 rounded flex items-center gap-2"
                                        data-category-id="{{ $mainCategory->id }}"
                                        data-category-name="{{ $mainCategory->name }}"
                                        data-is-main="true"
                                        style="background-color: #dc2626; color: white; border: none; cursor: pointer;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Löschen
                                </button>
                                <button type="button" 
                                        class="toggle-subcategories bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded flex items-center gap-2"
                                        data-category-id="{{ $mainCategory->id }}">
                                    <span class="toggle-text">Produktname anzeigen</span>
                                    <svg class="w-5 h-5 toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Main Category Commission Rate Form -->
                        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm" id="main-category-container-{{ $mainCategory->id }}">
                            <form id="main-category-form-{{ $mainCategory->id }}" 
                                  action="{{ route('admin.categories.update', $mainCategory) }}" 
                                  method="POST" 
                                  class="commission-form">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-4">
                                    <label class="font-semibold text-gray-700">Provisionssatz für {{ $mainCategory->name }} (%):</label>
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
                        
                        <!-- Subcategories Table (Initially Hidden) -->
                        <div id="subcategories-{{ $mainCategory->id }}" class="overflow-x-auto bg-white rounded-lg shadow-sm hidden">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Produktname</th>
                                        <th class="px-4 py-2">Grundprovision (€)</th>
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
                                                    <div class="flex items-center justify-center gap-2">
                                                        <button type="submit" 
                                                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                            Speichern
                                                        </button>
                                                        <button type="button" 
                                                                class="delete-category bg-red-800 text-white font-bold py-2 px-3 rounded flex items-center gap-1"
                                                                data-category-id="{{ $subcategory->id }}"
                                                                data-category-name="{{ $subcategory->name }}"
                                                                data-is-main="false"
                                                                style="background-color: #991b1b; color: white; border: none; cursor: pointer;">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
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

    <!-- Silme Onay Modalı -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4" id="modalTitle">Bereich löschen</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modalMessage">
                        Sind Sie sicher, dass Sie diesen Bereich löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDelete" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300"
                            style="background-color: #dc2626; color: white; border: none; cursor: pointer;">
                        Ja, löschen
                    </button>
                    <button id="cancelDelete" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            style="background-color: #6b7280; color: white; border: none; cursor: pointer;">
                        Abbrechen
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle functionality for subcategories
            document.querySelectorAll('.toggle-subcategories').forEach(button => {
                button.addEventListener('click', function() {
                    const categoryId = this.dataset.categoryId;
                    const subcategoriesDiv = document.getElementById(`subcategories-${categoryId}`);
                    const toggleIcon = this.querySelector('.toggle-icon');
                    const toggleText = this.querySelector('.toggle-text');
                    
                    if (subcategoriesDiv.classList.contains('hidden')) {
                        subcategoriesDiv.classList.remove('hidden');
                        toggleIcon.style.transform = 'rotate(180deg)';
                        toggleText.textContent = 'Produktname ausblenden';
                    } else {
                        subcategoriesDiv.classList.add('hidden');
                        toggleIcon.style.transform = 'rotate(0deg)';
                        toggleText.textContent = 'Produktname anzeigen';
                    }
                });
            });

            // Toggle functionality for add section
            document.querySelector('.toggle-add-section').addEventListener('click', function() {
                const formsDiv = document.getElementById('add-section-forms');
                const toggleIcon = this.querySelector('.toggle-add-icon');
                
                if (formsDiv.classList.contains('hidden')) {
                    formsDiv.classList.remove('hidden');
                    toggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    formsDiv.classList.add('hidden');
                    toggleIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Kategori ekleme formları için event listener'lar
            const addCategoryForms = document.querySelectorAll('.add-category-form');
            addCategoryForms.forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    console.log('Add category form submitted:', form.id);

                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();
                        console.log('Add category response:', data);

                        if (data.success) {
                            // Form'u temizle
                            form.reset();
                            
                            // Başarı mesajını göster
                            showNotification(data.message, 'success');
                            
                            // Sayfayı yenile (yeni kategorileri göstermek için)
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showNotification(data.message || 'Fehler beim Hinzufügen des Bereichs', 'error');
                        }
                    } catch (error) {
                        console.error('Add category error:', error);
                        showNotification('Fehler beim Hinzufügen des Bereichs', 'error');
                    }
                });
            });

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
                            let successMessage;
                            
                            if (formId.startsWith('main-category-form-')) {
                                // Ana kategori formu için container'ı bul
                                const categoryId = formId.split('-')[3];
                                elementToHighlight = document.getElementById(`main-category-container-${categoryId}`);
                                if (elementToHighlight) {
                                    // Ana kategori için yeşil highlight efekti
                                    elementToHighlight.style.backgroundColor = '#dcfce7'; // bg-green-100
                                    elementToHighlight.style.transition = 'background-color 0.5s';
                                    
                                    // 2 saniye sonra yeşil rengi kaldır
                                    setTimeout(() => {
                                        elementToHighlight.style.backgroundColor = '#ffffff'; // bg-white
                                    }, 2000);
                                }
                                successMessage = '✓ Provisionssatz wurde erfolgreich aktualisiert';
                            } else {
                                // Alt kategori formu için satırı bul
                                elementToHighlight = document.getElementById('row-' + formId.split('-')[1]);
                                if (elementToHighlight) {
                                    // Alt kategori için yeşil highlight efekti
                                    elementToHighlight.classList.add('bg-green-100');
                                    
                                    // 2 saniye sonra yeşil rengi kaldır
                                    setTimeout(() => {
                                        elementToHighlight.classList.remove('bg-green-100');
                                    }, 2000);
                                }
                                successMessage = '✓ Grundprovision wurde erfolgreich aktualisiert';
                            }

                            // Başarı mesajını göster
                            showNotification(successMessage, 'success');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('✕ Fehler beim Speichern', 'error');
                    }
                });
            });
        });

        // Silme fonksiyonalitesi
        let categoryToDelete = null;
        
        // Silme butonları için event listener
        document.querySelectorAll('.delete-category').forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.dataset.categoryId;
                const categoryName = this.dataset.categoryName;
                const isMain = this.dataset.isMain === 'true';
                
                categoryToDelete = { id: categoryId, name: categoryName, isMain: isMain };
                
                // Modal mesajını güncelle
                const modalTitle = document.getElementById('modalTitle');
                const modalMessage = document.getElementById('modalMessage');
                
                if (isMain) {
                    modalTitle.textContent = 'Hauptbereich löschen';
                    modalMessage.textContent = `Sind Sie sicher, dass Sie den Hauptbereich "${categoryName}" und alle seine Unterbereiche löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.`;
                } else {
                    modalTitle.textContent = 'Unterbereich löschen';
                    modalMessage.textContent = `Sind Sie sicher, dass Sie den Unterbereich "${categoryName}" löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.`;
                }
                
                // Modal'ı göster
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });
        
        // Onay butonu
        document.getElementById('confirmDelete').addEventListener('click', async function() {
            if (!categoryToDelete) return;
            
            try {
                const response = await fetch(`/admin/categories/${categoryToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Modal'ı kapat
                    document.getElementById('deleteModal').classList.add('hidden');
                    // Sayfayı yenile
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message || 'Fehler beim Löschen', 'error');
                }
            } catch (error) {
                console.error('Delete error:', error);
                showNotification('Fehler beim Löschen', 'error');
            }
            
            categoryToDelete = null;
        });
        
        // İptal butonu
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            categoryToDelete = null;
        });
        
        // Modal dışına tıklayınca kapat
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                categoryToDelete = null;
            }
        });

        // Bildirim gösterme fonksiyonu
        function showNotification(message, type) {
            // Varolan bildirimleri kaldır
            const container = document.getElementById('notification-container');
            container.innerHTML = '';

            // Yeni bildirim oluştur
            const notification = document.createElement('div');
            notification.className = `px-6 py-4 rounded-lg shadow-lg mb-4 ${
                type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 
                'bg-red-100 text-red-700 border border-red-400'
            }`;
            
            // İkon ve mesaj ekle
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2 text-lg">${type === 'success' ? '✓' : '✕'}</span>
                    <span>${message}</span>
                </div>
            `;
            
            // Bildirimi container'a ekle
            container.appendChild(notification);

            // 3 saniye sonra bildirimi kaldır
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.5s ease-out';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }
    </script>
    @endpush
</x-app-layout> 