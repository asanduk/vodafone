<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Benutzer bearbeiten') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zurück
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                E-Mail-Adresse <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                   required>
                        </div>

                        <!-- Branch -->
                        <div>
                            <label for="branch" class="block text-sm font-medium text-gray-700 mb-2">
                                Filiale <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="branch" 
                                   id="branch" 
                                   value="{{ old('branch', $user->branch) }}"
                                   placeholder="z.B. Berlin Zentrum"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                   required>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Telefonnummer
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+49 30 12345678"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>

                        <!-- Is Admin -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_admin" 
                                       value="1"
                                       {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Administrator-Berechtigung</span>
                            </label>
                        </div>

                        <!-- Is Active -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Aktiver Benutzer</span>
                            </label>
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse
                        </label>
                        <textarea name="address" 
                                  id="address" 
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Straße, Hausnummer, PLZ, Stadt">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition-colors duration-200">
                            Abbrechen
                        </a>
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition-colors duration-200">
                            Änderungen speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
