<?php

declare(strict_types=1);

namespace Yuxin\Japanpost\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Yuxin\Japanpost\Token token()
 * @method static \Yuxin\Japanpost\AddressZip addressZip()
 * @method static \Yuxin\Japanpost\SearchCode searchCode()
 */
class Japanpost extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'japanpost';
    }
}
