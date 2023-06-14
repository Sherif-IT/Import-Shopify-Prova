<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shopify\Context;
use App\Utils\ShopifySessionStorage;
class ShopifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Context::initialize(
                config('shopify.api.key'),
                config('shopify.api.token'),
                [ '*' ],
                config('shopify.store_url'),
                new ShopifySessionStorage(),
                config('shopify.api.version'),
                false,
                true
        );
    }
}
