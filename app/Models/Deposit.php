<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'payment_method',
        'amount',
        'transaction_id',
        'payment_slip',
        'status',
        'admin_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that made the deposit.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pending deposits.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved deposits.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected deposits.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if deposit is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if deposit is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if deposit is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the formatted amount with currency.
     */
    public function getFormattedAmountAttribute()
    {
        return '৳' . number_format($this->amount, 2);
    }

    /**
     * Get the payment slip URL.
     */
    public function getPaymentSlipUrlAttribute()
    {
        return $this->payment_slip ? asset('storage/' . $this->payment_slip) : null;
    }

    /**
     * Get the payment method with icon.
     */
    public function getPaymentMethodWithIconAttribute()
    {
        $icons = [
            'bKash' => '<i class="fas fa-mobile-alt text-primary"></i>',
            'Nagad' => '<i class="fas fa-wallet text-success"></i>',
            'Bank' => '<i class="fas fa-university text-info"></i>',
        ];

        return ($icons[$this->payment_method] ?? '') . ' ' . $this->payment_method;
    }
}