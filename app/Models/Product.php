<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = ['product_name', 'product_year', 'product_price', 'category_id', 'user_id', ];

    public function productdetail()
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function retailers()
    {
        return $this->belongsToMany(Retailer::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
