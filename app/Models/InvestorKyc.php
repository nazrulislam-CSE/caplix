<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorKyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name_bn',
        'full_name_en',
        'nid',
        'date_of_birth',
        'phone',
        'email',
        'permanent_address',
        'admin_notes',
        'bank_name',
        'bank_account_no',
        'investment_range',
        'occupation',
        'nid_front',
        'nid_back',
        'passport',
        'nominees',
        'otp',
        'otp_expires_at',
        'owner_verified',
        'status',
        'rejection_reason',
        'verified_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'otp_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'owner_verified' => 'boolean',
        'nominees' => 'array'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isVerified()
    {
        return $this->status === 'verified';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getNomineesArray()
    {
        return $this->nominees ?? [];
    }

    public function getTotalNomineeShare()
    {
        $nominees = $this->getNomineesArray();
        return collect($nominees)->sum('share_percentage');
    }
}
