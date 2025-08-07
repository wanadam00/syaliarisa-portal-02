<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CompanyInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'background',
        'vision',
        'mission',
        'organization_structure',
        'is_visible',
    ];

    protected $casts = [
        'organization_structure' => 'array',
    ];
}
