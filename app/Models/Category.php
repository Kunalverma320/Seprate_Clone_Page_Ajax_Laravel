<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'image', 'status'];
    protected $attributes = [
        'status' => '101', // Default status to Active
    ];

    public function setFirstNameAttribute($value)
{
    $this->attributes['name'] = strtolower($value);
}

    use HasFactory;
}
