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
                <h3>Admin Panel - Kategorien</h3>
                <!-- Test için basit bir içerik -->
                <ul>
                @foreach($mainCategories as $category)
                    <li>{{ $category->name }}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout> 