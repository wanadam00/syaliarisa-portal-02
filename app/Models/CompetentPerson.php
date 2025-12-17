<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompetentPerson extends Model
{
    use HasFactory;
    /**
     * Explicit table name
     */
    protected $table = 'competent_people';

    protected $fillable = [
        'name',
        'dosh_numbers',
        'competencies',
        'employee_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Competencies as an array (stored as JSON or CSV in DB)
     */
    public function getCompetenciesArray(): array
    {
        if (!$this->competencies) return [];

        // Try to parse as JSON first
        $decoded = json_decode($this->competencies, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Otherwise split by comma
        return array_filter(array_map('trim', explode(',', $this->competencies)));
    }

    public function getDoshNumbersArray(): array
    {
        if (!$this->dosh_numbers) return [];

        $source = $this->dosh_numbers;
        $decoded = json_decode($source, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return array_filter(array_map('trim', explode(',', $source)));
    }

    /**
     * Relationship: CompetentPerson belongs to CompanyInfo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
