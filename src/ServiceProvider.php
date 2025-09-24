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
        $this->registerHttpClient();
        $this->registerJapanpost();
        $this->registerToken();
        $this->registerAddressZip();
        $this->registerSearchCode();
    }

    private function registerHttpClient()
    {
        $this->app->singleton(HttpClient::class, fn () => new HttpClient(
            baseUri: config('services.japanpost.base_uri')
        ));
    }

    private function registerJapanpost()
    {
        $this->app->singleton('japanpost', fn ($app) => new Japanpost($app));
    }

    private function registerToken()
    {
        $this->app->singleton(Token::class, function ($app) {
            $token = new Token(
                config('services.japanpost.client_id'),
                config('services.japanpost.secret_key'),
                config('services.japanpost.base_uri'),
                $app->make('cache.store'),
                $app->make(HttpClient::class)
            );

            return $token;
        });

        $this->app->alias(Token::class, 'japanpost.token');
    }

    private function registerAddressZip()
    {
        $this->app->singleton(AddressZip::class, fn ($app) => new AddressZip(
            config('services.japanpost.client_id'),
            config('services.japanpost.secret_key'),
            config('services.japanpost.base_uri'),
            null,
            $app->make(HttpClient::class)
        ));

        $this->app->alias(AddressZip::class, 'japanpost.address_zip');
    }

    private function registerSearchCode()
    {
        $this->app->singleton(SearchCode::class, fn ($app) => new SearchCode(
            config('services.japanpost.client_id'),
            config('services.japanpost.secret_key'),
            config('services.japanpost.base_uri'),
            null,
            $app->make(HttpClient::class)
        ));

        $this->app->alias(SearchCode::class, 'japanpost.search_code');
    }
}
