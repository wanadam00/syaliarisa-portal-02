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
        'is_visible',
    ];

    /**
     * Relationship: CompanyInfo has many CompetentPersons
     */
    public function competentPersons()
    {
        return $this->hasMany(CompetentPerson::class);
    }
}
