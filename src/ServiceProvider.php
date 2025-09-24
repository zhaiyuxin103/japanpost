<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Override;

class ServiceProvider extends BaseServiceProvider
{
    #[Override]
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
        $this->app->singleton(Token::class, function ($app) {
            $token = new Token(
                config('services.japanpost.client_id'),
                config('services.japanpost.secret_key'),
                config('services.japanpost.base_uri'),
            );

            return $token;
        });

        $this->app->alias(Token::class, 'japanpost.token');
    }

    private function registerAddressZip()
    {
        $this->app->singleton(AddressZip::class, fn () => new AddressZip(
            config('services.japanpost.client_id'),
            config('services.japanpost.secret_key'),
            config('services.japanpost.base_uri')
        ));

        $this->app->alias(AddressZip::class, 'japanpost.address_zip');
    }

    private function registerSearchCode()
    {
        $this->app->singleton(SearchCode::class, fn () => new SearchCode(
            config('services.japanpost.client_id'),
            config('services.japanpost.secret_key'),
            config('services.japanpost.base_uri')
        ));

        $this->app->alias(SearchCode::class, 'japanpost.search_code');
    }
}
