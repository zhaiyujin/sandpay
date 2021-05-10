<?php

namespace zhaiyujin\sandpay\Provider;

use Illuminate\Support\ServiceProvider;
use zhaiyujin\sandpay\PreCreate\sandPayData;
use zhaiyujin\sandpay\PreCreate\SandPayRequest;
use zhaiyujin\sandpay\SimpleType\ProductId;
use zhayujin\sandpay\Facade\SandPay;

class SandPayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(SandPayRequest::class, function () {
            return new SandPayRequest();
        });
        $this->app->bind(sandPayData::class, function () {
            return new sandPayData();
        });
        $this->app->singleton(ProductId::class, function () {
            return new ProductId();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__ . '/../config/sandpay.php' => config_path('sciener.php'),
        ]);
    }
}
