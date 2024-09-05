<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'quantity', 'category_id'];


    // Product belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'category_id',
    ];
}