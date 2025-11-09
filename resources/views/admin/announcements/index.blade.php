<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ankündigungen Verwaltung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Neue Ankündigung erstellen</h3>
                </div>
                <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titel</label>
                        <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Inhalt</label>
                        <textarea name="content" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Datum</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Typ</label>
                            <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="info">Info</option>
                                <option value="success">Erfolg</option>
                                <option value="warning">Warnung</option>
                                <option value="error">Fehler</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Erstellen
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Bestehende Ankündigungen</h3>
                    <div class="space-y-4">
                        @forelse($announcements as $announcement)
                            <div class="border rounded-lg p-4 {{ $announcement['type'] === 'info' ? 'bg-blue-50 border-blue-200' : ($announcement['type'] === 'success' ? 'bg-green-50 border-green-200' : ($announcement['type'] === 'warning' ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200')) }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="font-semibold text-gray-800">{{ $announcement['title'] }}</h4>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($announcement['date'])->format('d.m.Y') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $announcement['content'] }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement['id']) }}" class="ml-4" onsubmit="return confirm('Wirklich löschen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Keine Ankündigungen vorhanden.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

