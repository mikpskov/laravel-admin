<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use Psr\Log\LoggerInterface;

final class UserObserver
{
    private const EXCLUDED_ATTRIBUTES = [
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    /**
     * Handle events after all transactions are committed.
     */
    public bool $afterCommit = true;

    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function created(User $user): void
    {
        $this->log('created', $user->attributesToArray());
    }

    public function updated(User $user): void
    {
        $this->log('updated', $this->getChanges($user));
    }

    public function deleted(User $user): void
    {
        $this->log('deleted', $user->attributesToArray());
    }

    private function log(string $event, array $data): void
    {
        $data = collect($data)->except(self::EXCLUDED_ATTRIBUTES)->toArray();

        $this->logger->info(json_encode(compact('event', 'data')));
    }

    private function getChanges(User $user): array
    {
        $changes = [];
        $original = $user->getOriginal();
        foreach ($user->getChanges() as $attribute => $value) {
            if (in_array($attribute, self::EXCLUDED_ATTRIBUTES, true)) {
                continue;
            }

            $changes[$attribute] = [$original[$attribute], $value];
        }

        return $changes;
    }
}
