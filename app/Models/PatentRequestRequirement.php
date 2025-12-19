<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatentRequestRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'patent_request_id',
        'patent_requirement_id',
        'observations',
        'attached_by',
    ];

    public function patentRequest()
    {
        return $this->belongsTo(PatentRequest::class);
    }

    public function patentRequirement()
    {
        return $this->belongsTo(PatentRequirement::class);
    }

    public function attachedBy()
    {
        return $this->belongsTo(User::class, 'attached_by');
    }
}
