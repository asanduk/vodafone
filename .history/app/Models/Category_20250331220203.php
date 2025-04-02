<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
} 