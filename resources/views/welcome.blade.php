<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Jobify</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS- -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Custom Styles -->
        <style>
            .hero-bg {
                background-image: url('https://images.pexels.com/photos/1054218/pexels-photo-1054218.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
                background-position: center;
                background-size: cover;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Ana sayfa -->
        <div class="min-h-screen bg-gray-100 dark:bg-black dark:text-white">
            <!-- Hero Section -->
            <div class="hero-bg relative min-h-[60vh] flex items-center justify-center text-center text-white">
                <div class="absolute inset-0 bg-black opacity-50"></div>
                <div class="relative z-10">
                    <h1 class="text-5xl font-bold mb-4">Hoşgeldiniz!</h1>
                    <p class="text-xl mb-8">İş başvurularınızı kolayca takip edin ve yönetin.</p>

                    @if (Route::has('login'))
                        <div class="mt-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                    Dashboard'a Git
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                    Giriş Yap
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                                        Kayıt Ol
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bilgilendirme Bölümü -->
            <div class="py-12 bg-white dark:bg-gray-900">
                <div class="max-w-7xl mx-auto text-center">
                    <h2 class="text-3xl font-bold mb-6 dark:text-white">Neler Yapabilirsiniz?</h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                        Laravel ile oluşturulan bu uygulama, iş başvurularınızı organize etmenizi sağlar. İş başvurularınızı kolayca ekleyebilir, durumlarını takip edebilir ve yönetebilirsiniz.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Özellik 1 -->
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Kolay Yönetim</h3>
                            <p class="text-gray-600 dark:text-gray-400">Başvurularınızı ekleyin, düzenleyin ve silin. Hepsini tek bir yerden yönetin.</p>
                        </div>

                        <!-- Özellik 2 -->
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Durum Takibi</h3>
                            <p class="text-gray-600 dark:text-gray-400">Başvurularınızın durumunu takip edin. Beklemede, görüşmeye çağrıldı, reddedildi ya da teklif alındı gibi durumları görüntüleyin.</p>
                        </div>

                        <!-- Özellik 3 -->
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Excel Dışa Aktarım</h3>
                            <p class="text-gray-600 dark:text-gray-400">Başvurularınızı Excel formatında dışa aktararak kaydedin ve paylaşın.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="py-6 bg-gray-200 dark:bg-gray-800">
                <div class="max-w-7xl mx-auto text-center text-gray-500 dark:text-gray-400">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </footer>
        </div>
    </body>
</html>
