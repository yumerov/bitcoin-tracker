<?php

namespace App\Services;

use App\Bitcoin\Common\ClientInterface;
use App\Events\PriceChangeEvent;
use App\Models\Price;
use Illuminate\Contracts\Events\Dispatcher;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Synchronizes the persisted price using the passed client
 */
class PriceSynchronizer
{

    public function __construct(
        private readonly ClientInterface $client,
        private readonly LoggerInterface $logger,
        private readonly Dispatcher $dispatcher
    )
    {
    }

    /**
     * Calls the client and persists the price. If there is an error, logs it.
     *
     * @return void
     */
    public function synchronize(): void
    {
        try {
            $response = $this->client->get();
            if ($response !== null) {
                $price = new Price();
                $price->price = $response->getPrice();
                // @phpstan-ignore-next-line
                $price->timestamp = $response->getTimestamp();
                $price->save();
                $this->logger->info('[PriceSynchronizer] Stored successfully', [
                    'id' => $price->id,
                    'price' => $price->price,
                    'timestamp' => $price->timestamp,
                ]);
                $this->dispatcher->dispatch(new PriceChangeEvent($price->price));
            } else {
                $this->logger->error('[PriceSynchronizer] Could not get response');
            }
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
        }
    }
}
