<?php

namespace App\Services\Credit;

use App\Models\User;
use App\Models\Credit;
use Illuminate\Support\Collection;

class CreditService
{
    /**
     * Grant credit to a user.
     */
    public function grant(
        User $user,
        float $amount,
        string $type,
        string $description,
        ?\DateTimeInterface $expiresAt = null
    ): Credit {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be greater than zero.');
        }

        return Credit::create([
            'user_id' => $user->id,
            'type' => $type,
            'description' => $description,
            'amount' => $amount,
            'balance' => $amount,
            'expires_at' => $expiresAt,
            'used' => false,
        ]);
    }

    /**
     * Get total available (non-expired) credit balance.
     */
    public function getAvailableBalance(User $user): float
    {
        return (float) $user->credits()
            ->where('used', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->sum('balance');
    }

    /**
     * Consume credits (FIFO).
     */
    public function consume(User $user, float $amount): float
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount to consume must be greater than zero.');
        }

        $available = $this->getAvailableBalance($user);

        if ($available < $amount) {
            throw new \DomainException('Insufficient credit balance.');
        }

        $remaining = $amount;

        /** @var Collection|Credit[] $credits */
        $credits = $user->credits()
            ->where('used', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at')
            ->lockForUpdate()
            ->get();

        foreach ($credits as $credit) {
            if ($remaining <= 0) {
                break;
            }

            if ($credit->balance <= $remaining) {
                // Fully consume this credit
                $remaining -= $credit->balance;

                $credit->update([
                    'balance' => 0,
                    'used' => true,
                ]);
            } else {
                // Partially consume this credit
                $credit->update([
                    'balance' => $credit->balance - $remaining,
                ]);

                $remaining = 0;
            }
        }

        return $amount;
    }

    /**
     * Expire credits manually (admin or job).
     */
    public function expire(Credit $credit): Credit
    {
        if ($credit->used) {
            return $credit;
        }

        $credit->update([
            'balance' => 0,
            'used' => true,
        ]);

        return $credit;
    }
}
