<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function getLogoUrlAttribute()
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }
}
