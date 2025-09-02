<?php

declare(strict_types=1);

namespace Workbench\App\Http\Controllers;

use Yuxin\Japanpost\AddressZip;
use Yuxin\Japanpost\SearchCode;
use Yuxin\Japanpost\Token;

class JapanpostController
{
    public function __invoke()
    {
        $token = app(Token::class)->getToken();

        $searchCode = app(SearchCode::class)->search('1000004');

        $addressZip = app(AddressZip::class)->search([
            'zip_code'  => 1006906,
            'pref_code' => 13,
            'city_code' => 13101,
        ]);

        return compact('token', 'searchCode', 'addressZip');
    }
}
