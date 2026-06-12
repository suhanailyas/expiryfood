<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model {
    protected $fillable = [
        'user_id',
        'item_name',
        'purchased'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}