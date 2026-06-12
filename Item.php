<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'quantity',
        'expiry_date',
        'price',
        'image'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute()
    {
        $days = now()->diffInDays($this->expiry_date, false);
        if ($days < 0) return 'expired';
        if ($days <= 3) return 'expiring';
        return 'fresh';
    }
}