<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'top_details',
        'top_image',
        'bottom_details',
        'bottom_image',
    ];
}
