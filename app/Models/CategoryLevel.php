<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'level',
        'bonus_percent',
        'activated_at',
    ];

    protected $casts = [
        'level' => 'integer',
        'bonus_percent' => 'float',
        'activated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}


