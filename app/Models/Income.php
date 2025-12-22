<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Income belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Active incomes (optional future use)
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
