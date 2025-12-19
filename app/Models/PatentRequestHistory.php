<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PatentRequestHistory extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'patent_request_id',
        'user_id',
        'action',
        'observations',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relaciones
     */
    public function request()
    {
        return $this->belongsTo(PatentRequest::class, 'patent_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helpers
     */
    public function isSystemAction(): bool
    {
        return in_array($this->action, [
            'created',
            'approved',
            'rejected',
            'rejected_with_observations'
        ]);
    }

    public function hasObservations(): bool
    {
        return !empty($this->observations);
    }
}
