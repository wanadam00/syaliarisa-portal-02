<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardApplication extends Model
{

    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'logo',
        'link',
        'is_visible',
    ];
    //
}
