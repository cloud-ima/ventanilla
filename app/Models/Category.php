<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        // Cuando se soft-delete una categoría, soft-delete también sus trámites
        static::deleting(function ($categories) {
            if (!$categories->isForceDeleting()) {
                // Soft delete todos los trámites de esta categoría
                // Esto disparará el evento de Tramite que limpia los requisitos
                $categories->procedures()->each(function ($categories) {
                    $categories->delete();
                });
            }
        });
    }

    protected $fillable = [
        'department_id',
        'name',
        'slug',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function procedures()
    {
        return $this->hasMany(Procedure::class)->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
