<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $guarded = [];

    /**
     * Relationship with Entrepreneur (User)
     */
    public function entrepreneur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entrepreneur_id');
    }

    /**
     * Relationship with Investments
     */
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    /**
     * Scope for approved projects
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Scope for active projects (approved and not fully funded)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Approved')
                    ->whereRaw('capital_raised < capital_required');
    }

    /**
     * Scope for featured projects
     */
    public function scopeFeatured($query)
    {
        return $query->where('status', 'Approved')
                    ->where('is_featured', true);
    }

    /**
     * Scope for trending projects (based on score)
     */
    public function scopeTrending($query)
    {
        return $query->where('status', 'Approved')
                    ->where('score', '>=', 8.0)
                    ->orderBy('score', 'desc');
    }

    /**
     * Scope for high-profit projects
     */
    public function scopeHighProfit($query)
    {
        return $query->where('status', 'Approved')
                    ->where('roi', '>=', 15.00);
    }

    /**
     * Check if project is fully funded
     */
    public function isFullyFunded(): bool
    {
        return $this->capital_raised >= $this->capital_required;
    }

    /**
     * Calculate funding percentage
     */
    public function getFundingPercentageAttribute(): float
    {
        if ($this->capital_required == 0) {
            return 0;
        }
        return ($this->capital_raised / $this->capital_required) * 100;
    }

    /**
     * Get remaining capital needed
     */
    public function getRemainingCapitalAttribute(): float
    {
        return max(0, $this->capital_required - $this->capital_raised);
    }

    /**
     * Get risk level based on project criteria
     */
    public function getRiskLevelAttribute(): string
    {
        if ($this->roi >= 20) return 'high';
        if ($this->roi >= 10) return 'medium';
        return 'low';
    }

    /**
     * Get investment type label
     */
    public function getInvestmentTypeLabelAttribute(): string
    {
        return match($this->investment_type) {
            'short' => 'Short-term',
            'regular' => 'Regular Investment',
            'fdi' => 'Fixed Deposit',
            default => 'Unknown'
        };
    }

    /**
     * Get project status color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Approved' => 'success',
            'Pending' => 'warning',
            'Issued' => 'info',
            'At Risk' => 'danger',
            default => 'secondary'
        };
    }
}