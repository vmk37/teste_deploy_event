<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentProcessor;
use App\Services\StripePaymentProcessor;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentProcessor::class, StripePaymentProcessor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}