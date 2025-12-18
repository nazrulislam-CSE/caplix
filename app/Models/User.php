<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'role',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function refer()
    {
        return $this->belongsTo(User::class, 'refer_by');
    }

    // Relations
    public function investorKyc()
    {
        return $this->hasOne(InvestorKyc::class);
    }

      public function businessKyc()
    {
        return $this->hasOne(BusinessKyc::class);
    }

    // Methods
    public function hasInvestorKyc()
    {
        return !is_null($this->investorKyc);
    }

    public function hasVerifiedInvestorKyc()
    {
        return $this->hasInvestorKyc() && $this->investorKyc->isVerified();
    }

    public function getKycStatus()
    {
        if ($this->hasInvestorKyc()) {
            return $this->investorKyc->status;
        }
        return 'no_kyc';
    }

     /**
     * Get all deposits for the user.
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get pending deposits for the user.
     */
    public function pendingDeposits()
    {
        return $this->deposits()->where('status', 'pending');
    }

    /**
     * Get approved deposits for the user.
     */
    public function approvedDeposits()
    {
        return $this->deposits()->where('status', 'approved');
    }

    /**
     * Get total deposited amount.
     */
    public function getTotalDepositedAttribute()
    {
        return $this->deposits()->where('status', 'approved')->sum('amount');
    }

    /**
     * Get total pending deposit amount.
     */
    public function getTotalPendingDepositAttribute()
    {
        return $this->deposits()->where('status', 'pending')->sum('amount');
    }
}
