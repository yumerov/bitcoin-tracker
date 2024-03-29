<?php

// phpcs:disable Squiz.Strings.DoubleQuoteUsage.ContainsVar
// phpcs:disable Squiz.Objects.ObjectInstantiation.NotAssigned

namespace App\Services;

use App\DTO\PriceNotificationDTO;
use App\Models\PriceSubscription;
use Exception;
use Psr\Log\LoggerInterface;

class PriceSubscriptionService
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) { }

    public function subscribe(PriceNotificationDTO $dto): bool
    {
        $this->logger->info("Subscribing email '{$dto->email}' for price '{$dto->price}'");
        try {
            (new PriceSubscription([
                'email' => $dto->email,
                'price' => $dto->price,
            ]))->save();
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());

            return false;
        }

        $this->logger->info("Subscribed email '{$dto->email}' for price '{$dto->price}'");

        return true;
    }
}
