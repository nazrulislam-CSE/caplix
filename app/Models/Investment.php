<?php
// app/Models/Investment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'investment_amount',
        'current_value',
        'profit_loss',
        'profit_loss_percentage',
        'status',
        'type',
        'risk_level',
        'investment_date',
        'maturity_date'
    ];

    protected $casts = [
        'investment_amount' => 'decimal:2',
        'current_value' => 'decimal:2',
        'profit_loss' => 'decimal:2',
        'profit_loss_percentage' => 'decimal:2',
        'investment_date' => 'datetime',
        'maturity_date' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InvestmentTransaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeManaged($query)
    {
        return $query->where('status', 'managed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWithProfit($query)
    {
        return $query->where('profit_loss', '>', 0);
    }

    // Methods
    public function calculateProfitLoss(): void
    {
        $this->profit_loss = $this->current_value - $this->investment_amount;
        $this->profit_loss_percentage = ($this->profit_loss / $this->investment_amount) * 100;
        $this->save();
    }

    public function canInvestMore(): bool
    {
        return in_array($this->status, ['active', 'managed']);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<ACTIVE>',
            'managed' => '<MANAGED>',
            'completed' => '<COMPLETED>',
            'cancelled' => '<CANCELLED>',
            default => '<PENDING>'
        };
    }
}