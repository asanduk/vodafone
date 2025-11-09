<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('level')->default(1)->after('commission_rate');
            $table->decimal('level_bonus_percent', 5, 2)->default(0)->after('level');
            $table->timestamp('level_activated_at')->nullable()->after('level_bonus_percent');
        });

        Schema::table('app_settings', function (Blueprint $table) {
            $table->boolean('enable_category_levels')->default(false)->after('show_admin_category_earnings');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['level', 'level_bonus_percent', 'level_activated_at']);
        });

        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn(['enable_category_levels']);
        });
    }
};


