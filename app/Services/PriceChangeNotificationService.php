<?php

// phpcs:disable Squiz.WhiteSpace.ObjectOperatorSpacing.Before
// phpcs:disable Squiz.Objects.ObjectInstantiation.NotAssigned
// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

namespace App\Services;

use App\Mail\PriceChangeMail;
use App\Models\PriceSubscription;
use Illuminate\Contracts\Mail\Mailer;
use Psr\Log\LoggerInterface;

class PriceChangeNotificationService
{

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Mailer $mailer
    )
    {
    }

    public function notifySubscribers(float $price): void
    {
        $this->logger->info('Notifying users for new price '.$price);
        $subscriptions = PriceSubscription::where('price', '<=', $price)
            ->where('active', true)
            ->get();
        $reactivatedSubscriptions = PriceSubscription::where('price', '>', $price)
            ->where('active', false)
            ->get();

        $subscriptions->each(function (PriceSubscription $subscription) {
            $this->mailer
                ->to($subscription->email)
                ->send(new PriceChangeMail($subscription->price));
        });

        $reactivatedSubscriptions->each(function (PriceSubscription $subscription) {
            $subscription->active = true;
            $subscription->deactivated_at = null;
            $subscription->save();
        });
        $subscriptions->each(function (PriceSubscription $subscription) use ($price) {
            $subscription->active = false;
            $subscription->deactivated_at = $price;
            $subscription->save();
        });
        $this->logger->info('Notified users for new price '.$price);
    }
}
