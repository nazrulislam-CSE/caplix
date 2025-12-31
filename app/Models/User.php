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

    // Users I have referred
    public function referrals()
    {
        return $this->hasMany(User::class, 'refer_by');
    }

    // The user who referred me
    public function referredBy()
    {
        return $this->belongsTo(User::class, 'refer_by');
    }

    // The user income
    public function incomes()
    {
        return $this->hasMany(Income::class);
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

    public function totalIncome()
    {
        return $this->incomes()->sum('amount');
    }

    public function rank()
    {
        $income = $this->totalIncome();

        $ranks = collect(config('ranks'))
            ->sortByDesc('min');

        foreach ($ranks as $rank) {
            if ($income >= $rank['min']) {
                return $rank['name'];
            }
        }

        return 'Bronze';
    }
    
    public function rankIcon()
    {
        $rank = $this->rank();

        return match($rank) {
            'Bronze' => 'fa-bronze fa-medal',    // example, replace with FA icon
            'Silver' => 'fa-solid fa-medal text-secondary',
            'Gold' => 'fa-solid fa-star text-warning',
            'Platinum' => 'fa-solid fa-gem text-primary',
            'Diamond' => 'fa-solid fa-gem text-info',
            default => 'fa-solid fa-star',
        };
    }

     /**
     * Find user by email or phone
     */
    public function findForPassport($identifier)
    {
        return $this->orWhere('email', $identifier)
                    ->orWhere('phone', $identifier)
                    ->first();
    }

}
