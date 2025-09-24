<?php

declare(strict_types=1);

namespace Workbench\App\Http\Controllers;

use Yuxin\Japanpost\Facades\Japanpost;

class JapanpostController
{
    public function __invoke()
    {
        // 尝试获取真实的 API 令牌
        $token = Japanpost::token()->getToken();

        // 尝试搜索邮编
        $searchCode = Japanpost::searchCode()->search('1000004');

        // 尝试地址搜索
        $addressZip = Japanpost::addressZip()->search([
            'zip_code'  => 1006906,
            'pref_code' => 13,
            'city_code' => 13101,
        ]);

        return response()->json([
            'status'  => 'success',
            'data'    => compact('token', 'searchCode', 'addressZip'),
            'message' => 'API calls successful',
        ]);
    }
}
