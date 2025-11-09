<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            $settings = AppSetting::first();
        } catch (\Throwable $e) {
            $settings = null;
        }
        if (!$settings) {
            $settings = new AppSetting([
                'show_ranking' => false,
                'ranking_metrics' => ['total_contracts', 'total_commission', 'monthly_commission'],
            ]);
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'show_ranking' => 'nullable|boolean',
            'ranking_metrics' => 'nullable|array',
            'ranking_metrics.*' => 'in:total_contracts,total_commission,monthly_commission',
            'include_admins_in_ranking' => 'nullable|boolean',
            'show_admin_earnings' => 'nullable|boolean',
            'show_admin_category_earnings' => 'nullable|boolean',
            'enable_category_levels' => 'nullable|boolean',
            'admin_earnings_months_window' => 'nullable|integer|min:1|max:36',
            'admin_earnings_show_subcategories' => 'nullable|boolean',
            'section' => 'nullable|string|in:ranking,general',
        ]);

        $settings = AppSetting::first();
        if (!$settings) {
            $settings = new AppSetting();
        }

        $section = $request->input('section');

        if ($section === 'ranking') {
            $settings->show_ranking = $request->boolean('show_ranking');
            if ($request->has('ranking_metrics')) {
                $settings->ranking_metrics = array_values($validated['ranking_metrics'] ?? []);
            }
            $settings->include_admins_in_ranking = $request->boolean('include_admins_in_ranking');
        } elseif ($section === 'general') {
            $settings->show_admin_earnings = $request->boolean('show_admin_earnings');
            $settings->show_admin_category_earnings = $request->boolean('show_admin_category_earnings');
            $settings->enable_category_levels = $request->boolean('enable_category_levels');
            if ($request->has('admin_earnings_months_window')) {
                $settings->admin_earnings_months_window = (int) ($validated['admin_earnings_months_window'] ?? $settings->admin_earnings_months_window);
            }
            $settings->admin_earnings_show_subcategories = $request->boolean('admin_earnings_show_subcategories');
        } else {
            // Fallback: update only provided keys
            if ($request->has('show_ranking')) $settings->show_ranking = $request->boolean('show_ranking');
            if ($request->has('ranking_metrics')) $settings->ranking_metrics = array_values($validated['ranking_metrics'] ?? []);
            if ($request->has('include_admins_in_ranking')) $settings->include_admins_in_ranking = $request->boolean('include_admins_in_ranking');
            if ($request->has('show_admin_earnings')) $settings->show_admin_earnings = $request->boolean('show_admin_earnings');
            if ($request->has('show_admin_category_earnings')) $settings->show_admin_category_earnings = $request->boolean('show_admin_category_earnings');
            if ($request->has('enable_category_levels')) $settings->enable_category_levels = $request->boolean('enable_category_levels');
            if ($request->has('admin_earnings_months_window')) $settings->admin_earnings_months_window = (int) ($validated['admin_earnings_months_window'] ?? $settings->admin_earnings_months_window);
            if ($request->has('admin_earnings_show_subcategories')) $settings->admin_earnings_show_subcategories = $request->boolean('admin_earnings_show_subcategories');
        }

        $settings->save();

        return redirect()->route('admin.settings.index')->with('success', 'Einstellungen gespeichert.');
    }
}


