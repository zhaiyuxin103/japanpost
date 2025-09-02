<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/services.php', 'services'
        );

        $this->registerBindings();
    }

    private function registerBindings()
    {
        $this->registerToken();
        $this->registerAddressZip();
        $this->registerSearchCode();
    }

    private function registerToken()
    {
        $this->app->singleton(Token::class, function () {
            return new Token(config('services.japanpost.client_id'), config('services.japanpost.secret_key'));
        });

        $this->app->alias(Token::class, 'japanpost.token');
    }

    private function registerAddressZip()
    {
        $this->app->singleton(AddressZip::class, function () {
            return new AddressZip(config('services.japanpost.client_id'), config('services.japanpost.secret_key'));
        });

        $this->app->alias(AddressZip::class, 'japanpost.address_zip');
    }

    private function registerSearchCode()
    {
        $this->app->singleton(SearchCode::class, function () {
            return new SearchCode(config('services.japanpost.client_id'), config('services.japanpost.secret_key'));
        });

        $this->app->alias(SearchCode::class, 'japanpost.search_code');
    }
}
