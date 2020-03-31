# LMS 系统概述

## 新增功能

## 更改功能

### Model增删改查审计

#### 1.卸载之前的模块 owen-it/laravel-auditing
	php -d memory_limit=-1 composer remove owen-it/laravel-auditing
	Schema::drop('audits');

	php -d memory_limit=-1 composer require league/flysystem-aws-s3-v3   --ignore-platform-reqs

        "league/flysystem-aws-s3-v3": "^1.0",
        "league/oauth2-client": "^2.4",

        "owen-it/laravel-auditing": "^9.0",

        "nicolasbeauvais/flysystem-onedrive": "^1.0",

        "torann/laravel-repository": "^0.5.10",

        "consoletvs/charts": "^6.4",

        "sentry/sentry-laravel": "1.0.1",

        "nexmo/laravel-notification": "^0.2.1",
        
        "omnipay/paypal": "^3.0",
#### 2.安装新模块 LogsActivity
	php -d memory_limit=-1 composer require spatie/laravel-activitylog
	php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"
	php artisan migrate

	composer global require "hirak/prestissimo" # 并行下载插件
##### LogsActivity
	use Spatie\Activitylog\Traits\LogsActivity;

	use LogsActivity;
	protected static $logAttributes = ['*'];
	protected static $logAttributesToIgnore = [ 'none'];
	protected static $logOnlyDirty = true;

	composer require spatie/laravel-query-builder