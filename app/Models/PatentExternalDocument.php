<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatentExternalDocument extends Model
{
    protected $fillable = [
        'name',
        'description',
        'where_to_obtain',
        'obtain_address',
        'how_to_obtain',
        'info_url',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(PatentRequestExternalDocument::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
