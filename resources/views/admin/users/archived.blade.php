<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archivierte Benutzer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Archivierte Benutzer</h3>
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2 transition-colors duration-200"
                           style="background-color: #2563eb; color: white; border: none; cursor: pointer;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Zurück zu Aktiven Benutzern
                        </a>
                    </div>
                </div>

                @if($archivedUsers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Filiale
                                    </th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Verträge
                                    </th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Archiviert am
                                    </th>
                                    <th class="px-1 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aktionen
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($archivedUsers as $user)
                                    <tr class="bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                        {{ $user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->branch ?? 'Nicht angegeben' }}</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->contracts->count() }}</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->deleted_at->format('d.m.Y H:i') }}</div>
                                        </td>
                                        <td class="px-1 py-4 whitespace-nowrap text-center">
                                            <div class="flex flex-col items-center justify-center space-y-1">
                                                <button type="button" 
                                                        class="restore-user bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-2 rounded text-xs flex items-center gap-1 transition-colors duration-200"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        style="background-color: #16a34a; color: white; border: none; cursor: pointer;">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Wiederherstellen
                                                </button>
                                                <button type="button" 
                                                        class="force-delete-user bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded text-xs flex items-center gap-1 transition-colors duration-200"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        style="background-color: #dc2626; color: white; border: none; cursor: pointer;">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Endgültig Löschen
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keine archivierten Benutzer</h3>
                        <p class="mt-1 text-sm text-gray-500">Es wurden noch keine Benutzer archiviert.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Wiederherstellen Bestätigungsmodal -->
    <div id="restoreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">Benutzer Wiederherstellen</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Sind Sie sicher, dass Sie den Benutzer <span id="restoreUserName"></span> wiederherstellen möchten?
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmRestore" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Ja
                    </button>
                    <button id="cancelRestore" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Nein
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Endgültig Löschen Bestätigungsmodal -->
    <div id="forceDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">Benutzer Endgültig Löschen</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Sind Sie sicher, dass Sie den Benutzer <span id="forceDeleteUserName"></span> endgültig löschen möchten? 
                        <strong>Diese Aktion kann nicht rückgängig gemacht werden!</strong>
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmForceDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Ja
                    </button>
                    <button id="cancelForceDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Nein
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Wiederherstellen Modal
        let restoreUserId = null;
        document.querySelectorAll('.restore-user').forEach(button => {
            button.addEventListener('click', function() {
                restoreUserId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                document.getElementById('restoreUserName').textContent = userName;
                document.getElementById('restoreModal').classList.remove('hidden');
            });
        });

        document.getElementById('confirmRestore').addEventListener('click', function() {
            if (restoreUserId) {
                fetch(`/admin/users/${restoreUserId}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Fehler beim Wiederherstellen des Benutzers: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Fehler beim Wiederherstellen des Benutzers: ' + error.message);
                });
            }
            document.getElementById('restoreModal').classList.add('hidden');
        });

        document.getElementById('cancelRestore').addEventListener('click', function() {
            document.getElementById('restoreModal').classList.add('hidden');
        });

        // Endgültig Löschen Modal
        let forceDeleteUserId = null;
        document.querySelectorAll('.force-delete-user').forEach(button => {
            button.addEventListener('click', function() {
                forceDeleteUserId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                document.getElementById('forceDeleteUserName').textContent = userName;
                document.getElementById('forceDeleteModal').classList.remove('hidden');
            });
        });

        document.getElementById('confirmForceDelete').addEventListener('click', function() {
            if (forceDeleteUserId) {
                fetch(`/admin/users/${forceDeleteUserId}/force`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Fehler beim endgültigen Löschen des Benutzers: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Fehler beim endgültigen Löschen des Benutzers: ' + error.message);
                });
            }
            document.getElementById('forceDeleteModal').classList.add('hidden');
        });

        document.getElementById('cancelForceDelete').addEventListener('click', function() {
            document.getElementById('forceDeleteModal').classList.add('hidden');
        });
    </script>
</x-app-layout>
