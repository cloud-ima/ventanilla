<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class PatentRequest extends Model
{
    use SoftDeletes;

    // Constantes
    public const MAX_PENDING_REQUESTS = 5;

    protected $fillable = [
        'user_id',
        'code',
        'rut',
        'business_address',
        'business_activity',
        'contact_email',
        'state',
        'reviewed_by',
        'reviewed_at',
        'observations',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Generar código de seguimiento único
     * Formato: PAT-YYYY-NNNNNN (ej: PAT-2025-000001)
     */
    public static function generateCode(): string
    {
        $year = date('Y');
        $lastRequest = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastRequest ? ((int) substr($lastRequest->code, -6)) + 1 : 1;

        return sprintf('PAT-%s-%06d', $year, $number);
    }

    /**
     * Eventos del modelo
     */
    protected static function booted(): void
    {
        // Generar código de seguimiento antes de crear
        static::creating(function ($solicitud) {
            if (empty($solicitud->code)) {
                $solicitud->code = self::generateCode();
            }
        });

        // Al crear - Email al contribuyente
        static::created(function ($solicitud) {
            Mail::to($solicitud->user->email)
                ->send(new \App\Mail\CreatedRequest($solicitud));
        });

        // Al cambiar estado - Email al contribuyente
        static::updated(function ($request) {
            if ($request->isDirty('state')) {
                switch ($request->state) {
                    case 'approved':
                        Mail::to($request->user->email)
                            ->send(new \App\Mail\ApprovedRequest($request));
                        break;

                    case 'rejected':
                        Mail::to($request->user->email)
                            ->send(new \App\Mail\RejectedRequest($request));
                        break;
                    case 'rejected_with_observations':
                        Mail::to($request->user->email)
                            ->send(new \App\Mail\RejectedWithObservationsRequest($request));
                        break;
                }
            }
        });
    }

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function history()
    {
        return $this->hasMany(PatentRequestHistory::class)->orderBy('created_at', 'desc');
    }

    public function forms()
    {
        return $this->hasMany(PatentRequestForm::class);
    }

    public function requirements()
    {
        return $this->hasMany(PatentRequestRequirement::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('state', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('state', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('state', 'rejected');
    }

    public function scopeRejectedWithObservations($query)
    {
        return $query->where('state', 'rejected_with_observations');
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->state === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->state === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isRejectedWithObservations(): bool
    {
        return $this->status === 'rejected_with_observations';
    }

}
