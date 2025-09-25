<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class HomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'top_details',
        'top_image',
        'bottom_details',
        'bottom_image',
    ];

    public function getTopImageUrlAttribute()
    {
        return $this->top_image ? Storage::url($this->top_image) : null;
    }
    public function getBottomImageUrlAttribute()
    {
        return $this->bottom_image ? Storage::url($this->bottom_image) : null;
    }
}
