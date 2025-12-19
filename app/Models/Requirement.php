<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'how_to_obtain',
    ];

    public function procedures(): BelongsToMany
    {
        return $this->belongsToMany(Procedure::class, 'procedure_requirement')
            ->withPivot(['is_mandatory'])
            ->withTimestamps();
    }
}
