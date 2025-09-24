<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;


class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'summary',
        'details',
        'image',
        'link',
        'is_visible',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class);
    }
}
