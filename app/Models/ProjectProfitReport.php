<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProfitReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'entrepreneur_id',
        'year',
        'month',
        'total_profit',
        'admin_share',
        'investor_share',
        'referral_share',
        'status',
        'remarks',
        'audited_by',
        'audited_at'
    ];

    protected $casts = [
        'total_profit' => 'decimal:2',
        'admin_share' => 'decimal:2',
        'investor_share' => 'decimal:2',
        'referral_share' => 'decimal:2',
        'audited_at' => 'datetime'
    ];

    /**
     * Get the project that owns the profit report.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the entrepreneur who submitted the report.
     */
    public function entrepreneur()
    {
        return $this->belongsTo(User::class, 'entrepreneur_id');
    }

    /**
     * Get the auditor who audited the report.
     */
    public function auditor()
    {
        return $this->belongsTo(User::class, 'audited_by');
    }

    /**
     * Get the profit distributions for this report.
     */
    public function distributions()
    {
        return $this->hasMany(ProfitDistribution::class);
    }

    /**
     * Scope for pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for submitted reports.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope for approved reports.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for reports of a specific project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope for reports of a specific year and month.
     */
    public function scopeForPeriod($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }
}