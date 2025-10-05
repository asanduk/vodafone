<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('branch')->nullable()->comment('Şube');
            $table->string('position')->nullable()->comment('Pozisyon');
            $table->string('phone')->nullable()->comment('Telefon');
            $table->text('address')->nullable()->comment('Adres');
            $table->boolean('is_active')->default(true)->comment('Aktif mi');
            $table->timestamp('last_login_at')->nullable()->comment('Son giriş tarihi');
            $table->softDeletes(); // Soft delete için
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['branch', 'position', 'phone', 'address', 'is_active', 'last_login_at']);
            $table->dropSoftDeletes();
        });
    }
};
