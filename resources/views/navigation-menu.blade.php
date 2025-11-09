<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-2 text-red-700 hover:bg-red-50 rounded-md transition-colors duration-200 relative">
                        <i class="fas fa-home text-red-500 mr-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                        @if(isset($unseenAnnouncementsCount) && $unseenAnnouncementsCount > 0)
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1 -translate-y-1 bg-red-600 rounded-full">{{ $unseenAnnouncementsCount }}</span>
                        @endif
                    </x-nav-link>
                    <x-nav-link href="{{ route('contracts.create') }}" :active="request()->routeIs('contracts.create')" class="flex items-center px-4 py-2 text-green-700 hover:bg-green-50 rounded-md transition-colors duration-200">
                        <i class="fas fa-plus text-green-500 mr-2"></i>
                        <span>{{ __('Neuer Vertrag') }}</span>
                    </x-nav-link>
                    <x-nav-link href="{{ route('contracts.index') }}" :active="request()->routeIs('contracts.index')" class="flex items-center px-4 py-2 text-blue-700 hover:bg-blue-50 rounded-md transition-colors duration-200">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        <span>{{ __('Alle Verträge') }}</span>
                    </x-nav-link>
                </div>

                <!-- Admin Navigation (Dropdown) -->
                @if(auth()->user()->is_admin)
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div x-data="{ openAdmin: false }" class="relative">
                            <button @click="openAdmin = !openAdmin" class="inline-flex items-center px-4 py-2 text-purple-700 hover:bg-purple-50 rounded-md transition-colors duration-200">
                                <i class="fas fa-shield-alt text-purple-500 mr-2"></i>
                                <span>Admin</span>
                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15l3.75 3.75L15.75 15m-7.5-6l3.75-3.75L15.75 9" />
                                </svg>
                            </button>
                            <div x-cloak x-show="openAdmin" @click.outside="openAdmin = false" class="absolute z-50 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg">
                                <div class="py-1">
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-users text-blue-500 mr-2"></i> Benutzer
                                    </a>
                                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-sitemap text-purple-500 mr-2"></i> Kategorien / Provisionen
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-sliders-h text-gray-500 mr-2"></i> Einstellungen
                                    </a>
                                    <a href="{{ route('admin.announcements.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-bullhorn text-orange-500 mr-2"></i> Ankündigungen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-2 text-red-700 hover:bg-red-50">
                <i class="fas fa-home text-red-500 mr-2"></i>
                <span>{{ __('Dashboard') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('contracts.create') }}" :active="request()->routeIs('contracts.create')" class="flex items-center px-4 py-2 text-green-700 hover:bg-green-50">
                <i class="fas fa-plus text-green-500 mr-2"></i>
                <span>{{ __('Neuer Vertrag') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('contracts.index') }}" :active="request()->routeIs('contracts.index')" class="flex items-center px-4 py-2 text-blue-700 hover:bg-blue-50">
                <i class="fas fa-list text-blue-500 mr-2"></i>
                <span>{{ __('Alle Verträge') }}</span>
            </x-responsive-nav-link>
            @if(auth()->user()->is_admin)
                <div class="border-t border-gray-200"></div>
                <div class="px-4 py-2 text-xs text-gray-400">Admin</div>
                <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                    <i class="fas fa-users mr-2"></i> Benutzer
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                    <i class="fas fa-sitemap mr-2"></i> Kategorien / Provisionen
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">
                    <i class="fas fa-sliders-h mr-2"></i> Einstellungen
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.announcements.index') }}" :active="request()->routeIs('admin.announcements.*')">
                    <i class="fas fa-bullhorn mr-2"></i> Ankündigungen
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
