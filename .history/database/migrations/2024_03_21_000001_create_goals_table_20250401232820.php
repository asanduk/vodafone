<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // monthly_contracts, commission
            $table->decimal('target', 10, 2);
            $table->string('month'); // YYYY-MM format
            $table->timestamps();

            $table->unique(['user_id', 'type', 'month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('goals');
    }
}; 