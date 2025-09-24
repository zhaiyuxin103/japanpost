<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;

class Japanpost
{
    public function __construct(
        protected Application $app
    ) {
        //
    }

    public function __call($method, $parameters)
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$parameters);
        }

        throw new InvalidArgumentException("Method [{$method}] does not exist on Japanpost.");
    }

    public function token(): Token
    {
        return $this->app->make(Token::class);
    }

    public function addressZip(): AddressZip
    {
        return $this->app->make(AddressZip::class);
    }

    public function searchCode(): SearchCode
    {
        return $this->app->make(SearchCode::class);
    }
}
