<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Jobifys') }}</title>

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
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
