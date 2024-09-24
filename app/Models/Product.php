<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'Product';
    protected $fillable = [
        'productname',
        'categoryid',
        'productprice',
        'productsku',
        'description',
        'image',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryid');
    }
    use HasFactory;
}
