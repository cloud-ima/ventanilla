<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedure extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        // Cuando se soft-delete un trámite (Procedure), eliminar sus relaciones en el pivot
        static::deleting(function ($request) {
            // Solo si es soft delete (no force delete)
            if (!$request->isForceDeleting()) {
                // Eliminar las relaciones en tramite_requisito
                $request->requirements()->detach();
            }
        });
    }

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'modality',
        'cost',
        'duration',
        'user_requirements',
        'instructions',
        'legal_framework',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'user_requirements' => 'array',
        'instructions' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class, 'procedure_requirement')
            ->withPivot(['is_mandatory'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    //Trámites destacados (mas recientes), solo 5
    public function scopeFeatured($query)
    {
        return $query->orderBy('created_at', 'desc')->take(5);
    }

}
