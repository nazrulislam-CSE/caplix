<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'investment_id',
        'user_id',
        'transaction_type',
        'amount',
        'balance_after',
        'reference_id',
        'status',
        'notes',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'processed_at' => 'datetime'
    ];

    // Relationships
    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    // Methods
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now()
        ]);
    }

    public function generateReferenceId(): string
    {
        return 'INV-' . time() . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}