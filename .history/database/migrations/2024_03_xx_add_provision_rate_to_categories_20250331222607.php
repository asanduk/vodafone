<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('base_commission', 10, 2)->default(0); // Temel komisyon tutarı
            $table->decimal('commission_rate', 5, 2)->default(10); // Komisyon oranı (%)
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['base_commission', 'commission_rate']);
        });
    }
}; 