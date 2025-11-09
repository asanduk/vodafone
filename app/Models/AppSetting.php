<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_ranking',
        'ranking_metrics',
        'include_admins_in_ranking',
        'show_admin_earnings',
        'show_admin_category_earnings',
        'enable_category_levels',
        'admin_earnings_months_window',
        'admin_earnings_show_subcategories',
    ];

    protected $casts = [
        'show_ranking' => 'boolean',
        'ranking_metrics' => 'array',
        'include_admins_in_ranking' => 'boolean',
        'show_admin_earnings' => 'boolean',
        'show_admin_category_earnings' => 'boolean',
        'enable_category_levels' => 'boolean',
        'admin_earnings_months_window' => 'integer',
        'admin_earnings_show_subcategories' => 'boolean',
    ];
}


