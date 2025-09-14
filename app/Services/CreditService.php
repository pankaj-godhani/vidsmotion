<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CreditService
{
    /**
     * Add credits to a user's account.
     *
     * @param User $user
     * @param int $credits
     * @param string $reason
     * @return bool
     */
    public function addCredits(User $user, int $credits, string $reason = 'Plan purchase'): bool
    {
        try {
            DB::beginTransaction();

            $user->increment('credits', $credits);
            $user->increment('total_credits_earned', $credits);

            Log::info('Credits added via CreditService', [
                'user_id' => $user->id,
                'credits_added' => $credits,
                'reason' => $reason,
                'new_balance' => $user->fresh()->credits,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add credits via CreditService', [
                'user_id' => $user->id,
                'credits' => $credits,
                'reason' => $reason,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Deduct credits from a user's account.
     *
     * @param User $user
     * @param int $credits
     * @param string $reason
     * @return bool
     */
    public function deductCredits(User $user, int $credits, string $reason = 'Video generation'): bool
    {
        try {
            DB::beginTransaction();

            if ($user->credits < $credits) {
                Log::warning('Insufficient credits for deduction', [
                    'user_id' => $user->id,
                    'requested_credits' => $credits,
                    'available_credits' => $user->credits,
                    'reason' => $reason,
                ]);
                DB::rollBack();
                return false;
            }

            $user->decrement('credits', $credits);
            $user->increment('total_credits_used', $credits);

            Log::info('Credits deducted via CreditService', [
                'user_id' => $user->id,
                'credits_deducted' => $credits,
                'reason' => $reason,
                'new_balance' => $user->fresh()->credits,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to deduct credits via CreditService', [
                'user_id' => $user->id,
                'credits' => $credits,
                'reason' => $reason,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check if user has sufficient credits.
     *
     * @param User $user
     * @param int $credits
     * @return bool
     */
    public function hasCredits(User $user, int $credits): bool
    {
        return $user->credits >= $credits;
    }

    /**
     * Get credits for a specific plan.
     *
     * @param string $planType
     * @return int
     */
    public function getCreditsForPlan(string $planType): int
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

    /**
     * Get user's credit summary.
     *
     * @param User $user
     * @return array
     */
    public function getCreditSummary(User $user): array
    {
        return [
            'current_credits' => $user->credits,
            'total_earned' => $user->total_credits_earned,
            'total_used' => $user->total_credits_used,
            'usage_percentage' => $user->total_credits_earned > 0
                ? round(($user->total_credits_used / $user->total_credits_earned) * 100, 2)
                : 0,
        ];
    }

    /**
     * Reset user's credits (for testing purposes).
     *
     * @param User $user
     * @return bool
     */
    public function resetCredits(User $user): bool
    {
        try {
            DB::beginTransaction();

            $user->update([
                'credits' => 0,
                'total_credits_earned' => 0,
                'total_credits_used' => 0,
            ]);

            Log::info('Credits reset via CreditService', [
                'user_id' => $user->id,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reset credits via CreditService', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Transfer credits between users (for admin purposes).
     *
     * @param User $fromUser
     * @param User $toUser
     * @param int $credits
     * @param string $reason
     * @return bool
     */
    public function transferCredits(User $fromUser, User $toUser, int $credits, string $reason = 'Credit transfer'): bool
    {
        try {
            DB::beginTransaction();

            if ($fromUser->credits < $credits) {
                Log::warning('Insufficient credits for transfer', [
                    'from_user_id' => $fromUser->id,
                    'to_user_id' => $toUser->id,
                    'requested_credits' => $credits,
                    'available_credits' => $fromUser->credits,
                ]);
                DB::rollBack();
                return false;
            }

            // Deduct from sender
            $fromUser->decrement('credits', $credits);
            $fromUser->increment('total_credits_used', $credits);

            // Add to receiver
            $toUser->increment('credits', $credits);
            $toUser->increment('total_credits_earned', $credits);

            Log::info('Credits transferred via CreditService', [
                'from_user_id' => $fromUser->id,
                'to_user_id' => $toUser->id,
                'credits_transferred' => $credits,
                'reason' => $reason,
                'from_user_new_balance' => $fromUser->fresh()->credits,
                'to_user_new_balance' => $toUser->fresh()->credits,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to transfer credits via CreditService', [
                'from_user_id' => $fromUser->id,
                'to_user_id' => $toUser->id,
                'credits' => $credits,
                'reason' => $reason,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
