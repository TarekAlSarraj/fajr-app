<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
protected $fillable = ['name', 'category', 'price_in_gems', 'is_unique', 'lock_days', 'image_path'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
