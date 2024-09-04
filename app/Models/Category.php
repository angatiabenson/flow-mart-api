<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id'];

    // Category belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Category has many Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}