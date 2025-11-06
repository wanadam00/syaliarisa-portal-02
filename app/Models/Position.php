<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'rank',
        'description',
        'is_visible',
    ];

    /**
     * Parent position (who this position reports to)
     */
    public function parent()
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    /**
     * Child positions (positions that report to this one)
     */
    public function children()
    {
        return $this->hasMany(Position::class, 'parent_id')->orderBy('rank', 'asc');
    }

    /**
     * Additional parents (many-to-many pivot) — other positions this position reports to
     */
    public function parentsPivot()
    {
        return $this->belongsToMany(Position::class, 'position_parents', 'position_id', 'parent_id');
    }

    /**
     * Children via pivot (positions that list this one as an additional parent)
     */
    public function childrenPivot()
    {
        return $this->belongsToMany(Position::class, 'position_parents', 'parent_id', 'position_id');
    }

    /**
     * Employees that hold this position
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'position_id');
    }
}
