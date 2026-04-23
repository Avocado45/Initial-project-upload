<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    protected $fillable = ['name', 'url'];

    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
