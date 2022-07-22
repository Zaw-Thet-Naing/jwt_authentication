<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $table = 'ads';

    protected $fillable = [ 'title' ,'image' , 'url_link' , 'is_active' , 'ads_type' ];
    
    protected $hidden = [];
    protected $casts = [];
}
