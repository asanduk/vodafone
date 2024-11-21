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
        <style>
            .fixed-bottom-buttons {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 10px;
                z-index: 1000; /* Ensure it stays above other content */
            }
            .tooltip {
                position: absolute;
                background-color: #333;
                color: #fff;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
                display: none;
                z-index: 1001;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Fixed Navigation Buttons -->
            <div class="fixed-bottom-buttons">
                <div class="relative">
                    <button onclick="window.history.back()" 
                            class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-200 ease-in-out"
                            onmouseover="showTooltip(event, 'Go Back')" onmouseout="hideTooltip()">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </button>
                    <div class="tooltip" id="tooltip"></div>
                </div>

                <div class="relative">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-all duration-200 ease-in-out" 
                       title="Dashboard"
                       onmouseover="showTooltip(event, 'Dashboard')" onmouseout="hideTooltip()">
                        <i class="fas fa-home text-lg"></i>
                    </a>
                    <div class="tooltip" id="tooltip"></div>
                </div>

                <div class="relative">
                    <a href="{{ route('job-applications.create') }}" 
                       class="flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg transition-all duration-200 ease-in-out" 
                       title="Add New Application"
                       onmouseover="showTooltip(event, 'Add New Application')" onmouseout="hideTooltip()">
                        <i class="fas fa-plus text-lg"></i>
                    </a>
                    <div class="tooltip" id="tooltip"></div>
                </div>
            </div>
        </div>

        @stack('modals')
        @livewireScripts

        <script>
            function showTooltip(event, text) {
                const tooltip = document.getElementById('tooltip');
                tooltip.innerText = text;
                tooltip.style.display = 'block';
                tooltip.style.left = event.pageX + 'px';
                tooltip.style.top = (event.pageY - 30) + 'px'; // Tooltip'u biraz yukarıda göster
            }

            function hideTooltip() {
                const tooltip = document.getElementById('tooltip');
                tooltip.style.display = 'none';
            }
        </script>
    </body>
</html>
