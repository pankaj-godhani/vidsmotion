<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'user_email',
        'user_name',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'razorpay_subscription_id',
        'plan_name',
        'plan_type',
        'amount',
        'currency',
        'status',
        'payment_date',
        'receipt_id',
        'razorpay_response',
        'ip_address',
        'user_agent',
        'subscription_start',
        'subscription_end',
        'is_active',
        'auto_renew',
    ];

    protected $casts = [
        'razorpay_response' => 'array',
        'payment_date' => 'datetime',
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'auto_renew' => 'boolean',
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan duration in days.
     */
    public function getPlanDurationInDays(): int
    {
        return match ($this->plan_type) {
            'Standard' => 7,
            'Pro Monthly' => 30,
            'Premier Yearly' => 365,
            default => 0,
        };
    }

    /**
     * Calculate subscription end date based on plan type.
     */
    public function calculateSubscriptionEnd(): Carbon
    {
        $startDate = $this->subscription_start ?? now();
        return $startDate->addDays($this->getPlanDurationInDays());
    }

    /**
     * Check if subscription is currently active.
     */
    public function isSubscriptionActive(): bool
    {
        if (!$this->is_active || $this->status !== 'completed') {
            return false;
        }

        $endDate = $this->subscription_end ?? $this->calculateSubscriptionEnd();
        return now()->isBefore($endDate);
    }

    /**
     * Scope for active payments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'completed');
    }

    /**
     * Scope for completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for user payments.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
