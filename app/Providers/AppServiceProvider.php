<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('navigation-menu', function ($view) {
            if (auth()->check()) {
                try {
                    $unseenCount = 0;
                    if (\Illuminate\Support\Facades\Storage::exists('announcements.json')) {
                        $announcements = json_decode(\Illuminate\Support\Facades\Storage::get('announcements.json'), true) ?? [];
                        $seenIds = auth()->user()->seen_announcements ?? [];
                        $unseen = array_filter($announcements, fn($a) => !in_array($a['id'], $seenIds));
                        $unseenCount = count($unseen);
                    }
                    $view->with('unseenAnnouncementsCount', $unseenCount);
                } catch (\Throwable $e) {
                    $view->with('unseenAnnouncementsCount', 0);
                }
            }
        });
    }
}
