<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Jobify') }}</title>

        <!-- Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
                 <!-- Pickadate.js CSS -->
                 <link rel="stylesheet" href="{{ asset('pickadate/lib/themes/default.css') }}">
                 <link rel="stylesheet" href="{{ asset('pickadate/lib/themes/default.date.css') }}">
 
                 <!-- jQuery (Pickadate.js için gerekli) -->
                 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
                 <!-- Pickadate.js JS -->
                 <script src="{{ asset('pickadate/lib/picker.js') }}"></script>
                 <script src="{{ asset('pickadate/lib/picker.date.js') }}"></script>
 
                 <script>
                     $(document).ready(function() {
                         $('#applied_at').pickadate({
                             format: 'yyyy-mm-dd', // Tarih formatını ayarlayın
                             // Diğer ayarları buraya ekleyebilirsiniz
                         });
                     });
                 </script>
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <!-- Navigation Buttons -->
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex space-x-4">
                    <button onclick="window.history.back()" 
                            class="flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                    
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>

                <!-- Main Content -->
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
