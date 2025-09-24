<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\ResetPasswordMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'credits',
        'total_credits_earned',
        'total_credits_used',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar_url',
    ];

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

    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }

        // Generate a default avatar with user's initial
        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=8b5cf6&color=ffffff&size=200&bold=true";
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        Mail::to($this->getEmailForPasswordReset())->send(new ResetPasswordMail($url, $this));
    }

    /**
     * Add credits to the user's account.
     *
     * @param int $credits
     * @param string $reason
     * @return void
     */
    public function addCredits(int $credits, string $reason = 'Plan purchase')
    {
        $this->increment('credits', $credits);
        $this->increment('total_credits_earned', $credits);

        \Log::info('Credits added to user', [
            'user_id' => $this->id,
            'credits_added' => $credits,
            'reason' => $reason,
            'new_balance' => $this->fresh()->credits,
        ]);
    }

    /**
     * Deduct credits from the user's account.
     *
     * @param int $credits
     * @param string $reason
     * @return bool
     */
    public function deductCredits(int $credits, string $reason = 'Video generation')
    {
        if ($this->credits < $credits) {
            return false; // Insufficient credits
        }

        $this->decrement('credits', $credits);
        $this->increment('total_credits_used', $credits);

        \Log::info('Credits deducted from user', [
            'user_id' => $this->id,
            'credits_deducted' => $credits,
            'reason' => $reason,
            'new_balance' => $this->fresh()->credits,
        ]);

        return true;
    }

    /**
     * Check if user has sufficient credits.
     *
     * @param int $credits
     * @return bool
     */
    public function hasCredits(int $credits): bool
    {
        return $this->credits >= $credits;
    }

    /**
     * Get credits for a specific plan.
     *
     * @param string $planType
     * @return int
     */
    public static function getCreditsForPlan(string $planType): int
    {
        // Normalize the plan type to lowercase for consistent matching
        $planType = strtolower($planType);

        return match($planType) {
            'standard' => 200,    // 7 days plan
            'pro_monthly' => 500, // 1 month plan
            'pro_yearly' => 7500, // 1 year plan
            default => 0,
        };
    }
}
