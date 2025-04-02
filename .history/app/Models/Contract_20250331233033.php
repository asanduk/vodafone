<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'contract_number',
        'contract_date',
        'customer_name',
        'commission_amount',
        'status'
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
        'contract_date' => 'date'
    ];

    protected $dates = [
        'contract_date',
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }
} 