<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatentRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'where_to_obtain',
        'obtain_address',
        'obtain_phone',
        'info_url',
        'validity_days',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'validity_days' => 'integer',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(PatentRequestRequirement::class, 'patent_requirement_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope para filtrar por categoría
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope para obtener solo activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
