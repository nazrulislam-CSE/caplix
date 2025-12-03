<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessKyc extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'registration_no',
        'trade_license_no',
        'business_type',
        'tin_bin',
        'establishment_year',
        'number_of_employees',
        'last_turnover',
        'business_address',
        'website',
        'owner_name',
        'owner_phone',
        'owner_email',
        'owner_nid_passport',
        'owner_role',
        'shareholders',
        'doc_registration',
        'doc_trade_license',
        'doc_tin',
        'doc_bank_statement',
        'doc_financials',
        'nominee_name',
        'nominee_relation',
        'nominee_nid',
        'owner_otp',
        'owner_otp_expires_at',
        'owner_verified',
        'status',
        'rejection_reason',
        'verified_at',
    ];

    protected $casts = [
        'shareholders' => 'array',
        'owner_verified' => 'boolean',
        'last_turnover' => 'decimal:2',
        'establishment_year' => 'integer',
        'number_of_employees' => 'integer',
        'owner_otp_expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessor for shareholders
    public function getShareholdersAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
    
    // Mutator for shareholders
    public function setShareholdersAttribute($value)
    {
        $this->attributes['shareholders'] = json_encode($value);
    }
}
