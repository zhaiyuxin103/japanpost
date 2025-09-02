# JapanPost API Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zhaiyuxin/japanpost?style=for-the-badge)](https://packagist.org/packages/zhaiyuxin/japanpost)
[![Total Downloads on Packagist](https://img.shields.io/packagist/dt/zhaiyuxin/japanpost?style=for-the-badge)](https://packagist.org/packages/zhaiyuxin/japanpost)
[![Build Status](https://img.shields.io/github/actions/workflow/status/zhaiyuxin103/japanpost/tests.yml?style=for-the-badge)](https://github.com/zhaiyuxin103/japanpost/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/zhaiyuxin103/japanpost?style=for-the-badge)](https://codecov.io/gh/zhaiyuxin103/japanpost)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg?style=for-the-badge)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg?style=for-the-badge)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=for-the-badge)](LICENSE)

一个用于与日本邮政 API 交互的 PHP 包，提供地址查询、邮编搜索等功能。

## ✨ 特性

- 🔐 **身份验证管理** - 自动处理 API 令牌获取和刷新
- 📍 **地址查询** - 根据各种条件搜索日本地址信息
- 🏷️ **邮编搜索** - 通过邮编代码查找对应的地址信息
- 🚀 **Laravel 集成** - 原生 Laravel 服务提供者支持
- 🛡️ **异常处理** - 完善的错误处理和自定义异常
- ⚙️ **灵活配置** - 支持自定义 HTTP 客户端选项

## 📋 系统要求

- PHP >= 8.3
- Laravel >= 10.0

## 🚀 快速开始

### 通过 Composer 安装

```bash
composer require zhaiyuxin/japanpost
```

### 发布配置文件（可选）

```bash
php artisan vendor:publish --provider="Yuxin\Japanpost\ServiceProvider"
```

## 🔧 配置

### 环境变量

在您的 `.env` 文件中添加以下配置：

```env
JAPANPOST_CLIENT_ID=your_client_id_here
JAPANPOST_SECRET_KEY=your_secret_key_here
```

### 配置文件

配置文件位于 `config/services.php`：

```php
'japanpost' => [
    'client_id'  => env('JAPANPOST_CLIENT_ID'),
    'secret_key' => env('JAPANPOST_SECRET_KEY'),
],
```

## 📖 使用方法

### 1. 获取 API 令牌

```php
use Yuxin\Japanpost\Token;

// 通过依赖注入获取
$token = app('japanpost.token')->getToken();

// 或直接实例化
$tokenService = new Token($clientId, $secretKey);
$token = $tokenService->getToken();
```

### 2. 地址查询

```php
use Yuxin\Japanpost\AddressZip;

// 通过依赖注入获取
$addressService = app('japanpost.address_zip');

// 搜索地址
$addresses = $addressService->search([
    'prefecture' => '東京都',
    'city' => '渋谷区',
    'street' => '渋谷'
], 1, 100);

// 或直接实例化
$addressService = new AddressZip($clientId, $secretKey);
$addresses = $addressService->search([
    'prefecture' => '東京都',
    'city' => '渋谷区'
]);
```

### 3. 邮编搜索

```php
use Yuxin\Japanpost\SearchCode;

// 通过依赖注入获取
$searchService = app('japanpost.search_code');

// 通过邮编搜索地址
$addresses = $searchService->search('150-0002', 1, 100);

// 或直接实例化
$searchService = new SearchCode($clientId, $secretKey);
$addresses = $searchService->search('150-0002');
```

### 4. 自定义 HTTP 客户端选项

```php
$addressService = app('japanpost.address_zip');

// 设置自定义 Guzzle 选项
$addressService->setGuzzleOptions([
    'timeout' => 30,
    'verify' => false,
    'headers' => [
        'User-Agent' => 'MyApp/1.0'
    ]
]);
```

## 🐛 异常处理

包提供了以下自定义异常：

- `Yuxin\Japanpost\Exceptions\HttpException` - HTTP 请求异常
- `Yuxin\Japanpost\Exceptions\AddressesNotFoundException` - 地址未找到异常
- `Yuxin\Japanpost\Exceptions\Exception` - 通用异常

```php
use Yuxin\Japanpost\Exceptions\AddressesNotFoundException;

try {
    $addresses = $addressService->search(['prefecture' => '東京都']);
} catch (AddressesNotFoundException $e) {
    // 处理地址未找到的情况
    Log::warning('No addresses found for Tokyo');
} catch (HttpException $e) {
    // 处理 HTTP 错误
    Log::error('HTTP error: ' . $e->getMessage());
}
```

## 🧪 测试

运行测试套件：

```bash
composer test
```

运行代码质量检查：

```bash
composer lint
```

## 🔧 开发

### 构建工作台

```bash
composer build
```

### 启动开发服务器

```bash
composer serve
```

## 🤝 贡献指南

欢迎提交 Issue 和 Pull Request！

1. Fork 本项目
2. 创建特性分支 (`git checkout -b feature/xxx`)
3. 提交更改 (`git commit -m 'Add some xxx'`)
4. 推送到分支 (`git push origin feature/xxx`)
5. 开启 Pull Request

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 📞 联系方式

- 项目主页: [GitHub Repository](https://github.com/zhaiyuxin103/japanpost)
- 问题反馈: [Issues](https://github.com/zhaiyuxin103/japanpost/issues)
- 技术讨论: [Discussions](https://github.com/zhaiyuxin103/japanpost/discussions)
