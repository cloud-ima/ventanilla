<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatentRequestForm extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'patent_request_id',
        'patent_form_id',
        'file',
        'file_name',
        'attached_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function patentRequest(): BelongsTo
    {
        return $this->belongsTo(PatentRequest::class);
    }

    public function patentForm(): BelongsTo
    {
        return $this->belongsTo(PatentForm::class);
    }

    public function attachedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attached_by');
    }
}
