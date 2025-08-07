<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AboutUsContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
        'content',
        'is_visible',
    ];
}
