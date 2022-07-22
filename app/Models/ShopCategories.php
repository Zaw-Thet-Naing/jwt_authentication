<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCategories extends Model
{
    use HasFactory;
    public $table = 'shop_categories';
    protected $fillable = ['name', 'dir_category', 'is_active'];
}
