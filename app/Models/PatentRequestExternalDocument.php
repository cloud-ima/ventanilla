<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatentRequestExternalDocument extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'patent_request_id',
        'patent_external_document_id',
        'observations',
        'attached_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function patentRequest(): BelongsTo
    {
        return $this->belongsTo(PatentRequest::class);
    }

    public function patentExternalDocument(): BelongsTo
    {
        return $this->belongsTo(PatentExternalDocument::class);
    }

    public function attachedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attached_by');
    }
}
