<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Vodafone Vertragsverwaltung</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Custom Styles -->
        <style>
            .hero-bg {
                background-image: url('https://images.pexels.com/photos/3183183/pexels-photo-3183183.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
                background-position: center;
                background-size: cover;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-black dark:text-white">
            <!-- Hero Section -->
            <div class="hero-bg relative min-h-[60vh] flex items-center justify-center text-center text-white">
                <div class="absolute inset-0 bg-black opacity-50"></div>
                <div class="relative z-10">
                    <h1 class="text-5xl font-bold mb-4">Willkommen bei Vodafone Vertragsverwaltung</h1>
                    <p class="text-xl mb-8">Verwalten Sie Ihre Verträge und Provisionen ganz einfach.</p>

                    @if (Route::has('login'))
                        <div class="mt-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                                    Zum Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                                    Anmelden
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                                        Registrieren
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informationen -->
            <div class="py-12 bg-white dark:bg-gray-900">
                <div class="max-w-7xl mx-auto text-center">
                    <h2 class="text-3xl font-bold mb-6 dark:text-white">Ihre Vorteile</h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                        Mit unserem Vertragsverwaltungssystem können Sie Ihre täglichen Vertragsabschlüsse einfach erfassen und Ihre Provisionen in Echtzeit verfolgen.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Vertragskategorien</h3>
                            <p class="text-gray-600 dark:text-gray-400">Wählen Sie aus 5 Hauptkategorien und den entsprechenden Produktnamen für Ihre Verträge.</p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Provisionsberechnung</h3>
                            <p class="text-gray-600 dark:text-gray-400">Sehen Sie Ihre Provisionen in Echtzeit und verfolgen Sie Ihre Einnahmen.</p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Statistiken & Auswertungen</h3>
                            <p class="text-gray-600 dark:text-gray-400">Detaillierte Übersicht Ihrer Leistungen und Vertragsabschlüsse.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="py-6 bg-gray-200 dark:bg-gray-800">
                <div class="max-w-7xl mx-auto text-center text-gray-500 dark:text-gray-400">
                    Vodafone Vertragsverwaltung | Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </footer>
        </div>
    </body>
</html>
