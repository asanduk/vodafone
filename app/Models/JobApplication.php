<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'position', 
        'company_name', 
        'applied_at', 
        'status', 
        'notes', 
        'job_listing_url', // İş ilanı linki
        'company_website_url', // Firma web sitesi
    ];
    

}


