<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Benutzer Übersicht') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2 transition-colors duration-200"
                   style="background-color: #dc2626; color: white; border: none; cursor: pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Neuer Benutzer
                </a>
                <a href="{{ route('admin.users.export') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2 transition-colors duration-200"
                   style="background-color: #16a34a; color: white; border: none; cursor: pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Excel Export
                </a>
                <a href="{{ route('admin.users.archived') }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2 transition-colors duration-200"
                   style="background-color: #d97706; color: white; border: none; cursor: pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6"></path>
                    </svg>
                    Archivierte Benutzer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                    Name
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                    Email
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Verträge
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Monatliche Provision
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jährliche Provision
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gesamtprovision
                                </th>
                                <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Export
                                </th>
                                <th class="px-1 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aktionen
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 hover:shadow-sm transition-all duration-200 border-b border-gray-200 hover:border-gray-300">
                                    <td class="px-2 py-4 whitespace-nowrap border-r border-gray-100">
                                        <div class="flex items-center">
                                            <div>
                                                <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-gray-900 hover:text-red-600 hover:bg-red-50 hover:px-2 hover:py-1 hover:rounded-md hover:shadow-sm transition-all duration-200 ease-in-out border-b border-transparent hover:border-red-200 flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-gray-400 hover:text-red-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    {{ $user->name }}
                                                </a>
                                                @if($user->is_admin)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        Admin
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap border-r border-gray-100">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap border-r border-gray-100">
                                        <div class="text-sm text-gray-900">{{ $user->contracts->count() }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap border-r border-gray-100">
                                        <div class="text-sm text-gray-900">{{ number_format($user->monthly_commission, 2) }}€</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap border-r border-gray-100">
                                        <div class="text-sm text-gray-900">{{ number_format($user->yearly_commission, 2) }}€</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap border-r border-gray-100">
                                        <div class="text-sm font-bold text-gray-900">{{ number_format($user->total_commission, 2) }}€</div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.users.export.single', $user) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-2 rounded text-xs flex items-center gap-1 transition-colors duration-200"
                                           style="background-color: #16a34a; color: white; border: none; cursor: pointer;">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Export
                                        </a>
                                    </td>
                                    <td class="px-1 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center justify-center space-y-1">
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-2 rounded text-xs flex items-center gap-1 transition-colors duration-200"
                                               style="background-color: #2563eb; color: white; border: none; cursor: pointer;">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <button type="button" 
                                                        class="archive-user bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded text-xs flex items-center gap-1 transition-colors duration-200"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        style="background-color: #d97706; color: white; border: none; cursor: pointer;">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6"></path>
                                                    </svg>
                                                    Archiv
                                                </button>
                                                <button type="button" 
                                                        class="delete-user bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded text-xs flex items-center gap-1 transition-colors duration-200"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        style="background-color: #dc2626; color: white; border: none; cursor: pointer;">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-500 px-1">Eigen</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4" id="modalTitle">Benutzer löschen</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modalMessage">
                        Sind Sie sicher, dass Sie diesen Benutzer löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.
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

    <!-- Archive Confirmation Modal -->
    <div id="archiveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4" id="archiveModalTitle">Benutzer archivieren</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="archiveModalMessage">
                        Sind Sie sicher, dass Sie diesen Benutzer archivieren möchten? Die Verträge bleiben erhalten.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmArchive" 
                            class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                            style="background-color: #d97706; color: white; border: none; cursor: pointer;">
                        Ja, archivieren
                    </button>
                    <button id="cancelArchive" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            style="background-color: #6b7280; color: white; border: none; cursor: pointer;">
                        Abbrechen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Delete functionality
        let userToDelete = null;
        
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                
                userToDelete = { id: userId, name: userName };
                
                document.getElementById('modalTitle').textContent = 'Benutzer löschen';
                document.getElementById('modalMessage').textContent = 
                    `Sind Sie sicher, dass Sie den Benutzer "${userName}" und alle seine Daten vollständig löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.`;
                
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        // Archive functionality
        let userToArchive = null;
        
        document.querySelectorAll('.archive-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                
                userToArchive = { id: userId, name: userName };
                
                document.getElementById('archiveModalTitle').textContent = 'Benutzer archivieren';
                document.getElementById('archiveModalMessage').textContent = 
                    `Sind Sie sicher, dass Sie den Benutzer "${userName}" archivieren möchten? Die Verträge bleiben erhalten.`;
                
                document.getElementById('archiveModal').classList.remove('hidden');
            });
        });

        // Confirm delete
        document.getElementById('confirmDelete').addEventListener('click', async function() {
            if (!userToDelete) return;
            
            try {
                const response = await fetch(`/admin/users/${userToDelete.id}/force`, {
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
                    document.getElementById('deleteModal').classList.add('hidden');
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
            
            userToDelete = null;
        });

        // Confirm archive
        document.getElementById('confirmArchive').addEventListener('click', async function() {
            if (!userToArchive) return;
            
            try {
                const response = await fetch(`/admin/users/${userToArchive.id}`, {
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
                    document.getElementById('archiveModal').classList.add('hidden');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message || 'Fehler beim Archivieren', 'error');
                }
            } catch (error) {
                console.error('Archive error:', error);
                showNotification('Fehler beim Archivieren', 'error');
            }
            
            userToArchive = null;
        });

        // Cancel buttons
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            userToDelete = null;
        });

        document.getElementById('cancelArchive').addEventListener('click', function() {
            document.getElementById('archiveModal').classList.add('hidden');
            userToArchive = null;
        });

        // Modal close on outside click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                userToDelete = null;
            }
        });

        document.getElementById('archiveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                userToArchive = null;
            }
        });
    </script>
</x-app-layout> 