<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Einstellungen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="border-b border-gray-200 px-6 pt-6">
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        <button type="button" class="settings-tab active text-red-600 border-red-600 whitespace-nowrap py-4 px-1 border-b-2 text-sm font-medium" data-target="tab-ranking">
                            {{ __('Ranking') }}
                        </button>
                        <button type="button" class="settings-tab text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 border-transparent text-sm font-medium" data-target="tab-general">
                            {{ __('Allgemein') }}
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <div id="tab-ranking" class="settings-panel">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">{{ __('Ranking Einstellungen') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Steuern Sie, ob die Ranking-Tabelle angezeigt wird und welche Kennzahlen sichtbar sind.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="section" value="ranking">

                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="show_ranking" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ old('show_ranking', $settings->show_ranking ?? false) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ __('Ranking Tabelle auf dem Dashboard anzeigen') }}</span>
                                </label>
                            </div>

                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="include_admins_in_ranking" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ old('include_admins_in_ranking', $settings->include_admins_in_ranking ?? false) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ __('Inhaber/Administratoren in Ranking einbeziehen') }}</span>
                                </label>
                            </div>

                            <div>
                                <p class="font-medium mb-2">{{ __('Anzuzeigende Kennzahlen') }}</p>
                                @php
                                    $metrics = old('ranking_metrics', $settings->ranking_metrics ?? ['total_contracts','total_commission','monthly_commission']);
                                @endphp
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="ranking_metrics[]" value="total_contracts" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ in_array('total_contracts', $metrics) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ __('Gesamtanzahl Verträge') }}</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="ranking_metrics[]" value="total_commission" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ in_array('total_commission', $metrics) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ __('Gesamtprovision') }}</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="ranking_metrics[]" value="monthly_commission" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ in_array('monthly_commission', $metrics) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ __('Provision (aktueller Monat)') }}</span>
                                    </label>
                                </div>
                                @error('ranking_metrics')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-button class="bg-red-600 hover:bg-red-700">
                                    {{ __('Speichern') }}
                                </x-button>
                            </div>
                        </form>
                    </div>

                    <div id="tab-general" class="settings-panel hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">{{ __('Allgemeine Einstellungen für Inhaber') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Einstellungen für die Anzeige von Provisionsdaten für Shop-Inhaber.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="section" value="general">

                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="show_admin_earnings" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ old('show_admin_earnings', $settings->show_admin_earnings ?? true) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ __('Inhaber sieht pro Vertrag die Provision (echter Betrag)') }}</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Wenn aktiv, sehen Shop-Inhaber pro Vertrag den vollen Provisionsbetrag (z. B. 100€).') }}</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">{{ __('Inhaber-Provisionen nach Kategorie (monatsbasiert)') }}</h4>
                                <p class="text-xs text-gray-500 mb-3">{{ __('Monatliche Gesamtsummen nach Kategorie anzeigen; optional mit Unterkategorien.') }}</p>

                                <div class="space-y-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="show_admin_category_earnings" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ old('show_admin_category_earnings', $settings->show_admin_category_earnings ?? true) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ __('Aktivieren') }}</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="enable_category_levels" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ old('enable_category_levels', $settings->enable_category_levels ?? false) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ __('Kategorie-Level Erhöhungen aktivieren') }}</span>
                                    </label>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Zeitraum (Monate)') }}</label>
                                        <input type="number" min="1" max="36" name="admin_earnings_months_window" value="{{ old('admin_earnings_months_window', $settings->admin_earnings_months_window ?? 12) }}" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    </div>

                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="admin_earnings_show_subcategories" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ old('admin_earnings_show_subcategories', $settings->admin_earnings_show_subcategories ?? true) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ __('Unterkategorien in der Aufschlüsselung anzeigen') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <x-button class="bg-red-600 hover:bg-red-700">
                                    {{ __('Speichern') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const tabs = document.querySelectorAll('.settings-tab');
                    const panels = document.querySelectorAll('.settings-panel');
                    tabs.forEach(tab => {
                        tab.addEventListener('click', () => {
                            tabs.forEach(t => t.classList.remove('active', 'text-red-600', 'border-red-600'));
                            tabs.forEach(t => t.classList.add('text-gray-500', 'hover:text-gray-700', 'border-transparent'));

                            tab.classList.add('active', 'text-red-600', 'border-red-600');

                            panels.forEach(p => p.classList.add('hidden'));
                            const target = document.getElementById(tab.dataset.target);
                            if (target) target.classList.remove('hidden');
                        });
                    });
                });
            </script>
        </div>
    </div>
</x-app-layout>


