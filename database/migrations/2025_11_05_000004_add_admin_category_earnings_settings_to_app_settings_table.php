<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->boolean('show_admin_category_earnings')->default(true)->after('show_admin_earnings');
            $table->unsignedInteger('admin_earnings_months_window')->default(12)->after('show_admin_category_earnings');
            $table->boolean('admin_earnings_show_subcategories')->default(true)->after('admin_earnings_months_window');
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn(['show_admin_category_earnings','admin_earnings_months_window','admin_earnings_show_subcategories']);
        });
    }
};


