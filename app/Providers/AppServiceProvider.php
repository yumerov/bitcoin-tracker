<?php

// phpcs:disable Squiz.Commenting.InlineComment.Empty

namespace App\Providers;

use App\Bitcoin\Bitfinex\Client;
use App\Bitcoin\Common\ClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(ClientInterface::class, Client::class);
    }

    public function boot(): void
    {
        //
    }
}
