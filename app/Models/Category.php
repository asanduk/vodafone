<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'base_commission',
        'commission_rate',
        'level',
        'level_bonus_percent',
        'level_activated_at'
    ];

    protected $casts = [
        'level' => 'integer',
        'level_bonus_percent' => 'float',
        'level_activated_at' => 'datetime',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function levels()
    {
        return $this->hasMany(CategoryLevel::class)->orderBy('activated_at');
    }
} 