<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerSlider extends Model
{
    use HasFactory;

    protected $table= 'banner_slider';

    protected $fillable = ['title','url_link','is_active','banner_type'];
    
    protected $hidden = [];

    protected $casts = [];
}
