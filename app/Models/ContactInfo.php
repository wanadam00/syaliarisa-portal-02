<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'phone',
        'email',
        'mobile_phone_1',
        'mobile_phone_2',
        'map_embed',
        'business_hours',
        'is_visible',
    ];
}
