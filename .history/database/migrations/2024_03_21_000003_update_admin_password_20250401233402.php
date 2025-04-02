<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        User::where('id', 1)->update([
            'password' => Hash::make('yeni_sifre123')
        ]);
    }

    public function down()
    {
        // Geri alma işlemi gerekmez
    }
}; 