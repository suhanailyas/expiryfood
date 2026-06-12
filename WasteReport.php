<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model {
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'total_expired',
        'total_loss'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}