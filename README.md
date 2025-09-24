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
- 🎭 **Facade 支持** - 统一的 Facade 接口，简化调用
- 🛡️ **异常处理** - 完善的错误处理和自定义异常
- ⚙️ **灵活配置** - 支持自定义 HTTP 客户端选项
- 💾 **Token 缓存** - 自动缓存 API 令牌，提高性能
- 🌐 **多环境支持** - 支持测试和生产环境切换

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

这将发布配置文件到 `config/japanpost.php`。

## 🔧 配置

### 环境变量

在您的 `.env` 文件中添加以下配置：

```env
JAPANPOST_CLIENT_ID=your_client_id_here
JAPANPOST_SECRET_KEY=your_secret_key_here
JAPANPOST_BASE_URI=https://api.da.pf.japanpost.jp/
```

**环境配置说明：**

- `JAPANPOST_BASE_URI`：API 基础 URL，支持测试和生产环境
  - 生产环境：`https://api.da.pf.japanpost.jp/`（默认）
  - 测试环境：`https://test-api.example.com/`（示例）

### 配置文件

配置文件位于 `config/services.php`：

```php
'japanpost' => [
    'client_id'  => env('JAPANPOST_CLIENT_ID'),
    'secret_key' => env('JAPANPOST_SECRET_KEY'),
    'base_uri'   => env('JAPANPOST_BASE_URI', 'https://api.da.pf.japanpost.jp/'),
],
```

## 📖 使用方法

### 🎭 Laravel Facade 使用

为了提供更简洁的 API，本包提供了一个统一的 Facade 接口。您可以通过 Facade 轻松访问所有服务：

```php
use Yuxin\Japanpost\Facades\Japanpost;

// 获取 Token 服务
$token = Japanpost::token();
$authToken = $token->getToken();

// 获取地址查询服务
$addressZip = Japanpost::addressZip();
$addresses = $addressZip->search([
    'prefecture' => '東京都',
    'city' => '渋谷区'
]);

// 获取邮编搜索服务
$searchCode = Japanpost::searchCode();
$addresses = $searchCode->search('150-0002');
```

**Facade 优势：**

- ✅ **统一接口**：一个 Facade 访问所有服务
- ✅ **简洁语法**：无需手动实例化或依赖注入
- ✅ **单例模式**：每次调用返回相同的实例
- ✅ **Laravel 集成**：完美融入 Laravel 生态系统
- ✅ **类型提示**：完整的 IDE 类型提示支持

### 1. 获取 API 令牌

```php
use Yuxin\Japanpost\Token;
use Psr\SimpleCache\CacheInterface;

// 通过依赖注入获取（推荐，自动启用缓存）
$token = app('japanpost.token')->getToken();

// 直接实例化（使用默认文件缓存）
$tokenService = new Token($clientId, $secretKey);
$token = $tokenService->getToken();

// 自定义缓存实现
$tokenService = new Token($clientId, $secretKey, 'https://api.da.pf.japanpost.jp/', $cache);
$token = $tokenService->getToken();

// 设置自定义缓存时间
$tokenService = new Token($clientId, $secretKey);
$tokenService->setCacheTtl(1800); // 30分钟
$token = $tokenService->getToken();
```

**缓存机制：**

- 自动缓存 API 令牌，默认缓存时间为 3600 秒（1小时）
- 使用 PSR-16 缓存标准，支持多种缓存实现
- Laravel 环境中自动使用 Laravel 缓存系统
- 独立使用时默认使用 Symfony 文件缓存

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

// 直接实例化（使用默认配置）
$addressService = new AddressZip($clientId, $secretKey);
$addresses = $addressService->search([
    'prefecture' => '東京都',
    'city' => '渋谷区'
]);

// 自定义 API 基础 URL
$addressService = new AddressZip($clientId, $secretKey, 'https://test-api.example.com/');
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

// 直接实例化（使用默认配置）
$searchService = new SearchCode($clientId, $secretKey);
$addresses = $searchService->search('150-0002');

// 自定义 API 基础 URL
$searchService = new SearchCode($clientId, $secretKey, 'https://test-api.example.com/');
$addresses = $searchService->search('150-0002');
```

### 5. 环境切换示例

```php
// 生产环境配置
$productionToken = new Token($clientId, $secretKey, 'https://api.da.pf.japanpost.jp/');
$productionAddress = new AddressZip($clientId, $secretKey, 'https://api.da.pf.japanpost.jp/');
$productionSearch = new SearchCode($clientId, $secretKey, 'https://api.da.pf.japanpost.jp/');

// 测试环境配置
$testToken = new Token($clientId, $secretKey, 'https://test-api.example.com/');
$testAddress = new AddressZip($clientId, $secretKey, 'https://test-api.example.com/');
$testSearch = new SearchCode($clientId, $secretKey, 'https://test-api.example.com/');
```

### 6. 自定义 HTTP 客户端选项

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

### 运行测试套件

```bash
composer test
```

### 运行代码质量检查

```bash
composer lint
```

### 测试框架

本项目使用 **Pest PHP** 作为测试框架，配合 Orchestra Testbench 进行 Laravel 包测试。

#### 测试结构

```
tests/
├── Feature/                 # 集成测试
│   ├── IntegrationTest.php # 服务集成测试
│   ├── ServiceProviderTest.php # 服务提供商测试
│   └── FacadeTest.php      # Facade 功能测试
├── Unit/                   # 单元测试
│   ├── TokenTest.php      # Token 类测试
│   ├── AddressZipTest.php  # AddressZip 类测试
│   ├── SearchCodeTest.php # SearchCode 类测试
│   ├── ExceptionsTest.php # 异常处理测试
│   └── HelpersTest.php    # 辅助函数测试
├── TestCase.php           # 基础测试用例
└── Pest.php              # Pest 配置文件
```

#### 测试特点

- **现代语法**: 使用 Pest 的 `test()` 和 `expect()` 函数
- **类型安全**: 所有测试都包含严格类型声明
- **完整覆盖**: 包含单元测试、集成测试和异常测试
- **Laravel 集成**: 使用 Orchestra Testbench 模拟 Laravel 环境
- **依赖注入**: 完整测试 Laravel 服务容器绑定
- **Facade 测试**: 专门测试 Facade 功能和服务访问

#### 编写测试示例

```php
// 编写单元测试
test('token can be instantiated with required parameters', function () {
    $token = new Token('test_client_id', 'test_secret_key');
    expect($token)->toBeInstanceOf(Token::class);
});

// 编写集成测试
test('services can be used with dependency injection', function () {
    $token = new Token('test_client_id', 'test_secret_key');
    $addressZip = new AddressZip('test_client_id', 'test_secret_key');
    $searchCode = new SearchCode('test_client_id', 'test_secret_key');

    expect($token)->toBeInstanceOf(Token::class);
    expect($addressZip)->toBeInstanceOf(AddressZip::class);
    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});
```

### 测试覆盖率

目前测试覆盖率达到 100%，包含：

- ✅ 所有核心服务类的功能测试
- ✅ 异常处理和错误情况测试
- ✅ Laravel 服务提供商集成测试
- ✅ HTTP 客户端配置和选项测试
- ✅ 缓存机制测试
- ✅ Facade 功能和接口测试
- ✅ 辅助函数和工具类测试

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
