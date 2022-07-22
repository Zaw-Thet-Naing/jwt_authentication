<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    public $table = 'shop';
    protected $fillable = ['shop_category_id', 'name', 'address', 'phone', 'website', 'email', 'image', 'is_active'];
}
