# How to run

- 1. git clone 
- 2. cp .env.example .env
- 3. composer install --ignore-platform-reqs
    composer config -g repo.packagist composer https://packagist.phpcomposer.com
    composer global require hirak/prestissimo
    composer config -g --unset repos.packagist
    https://stackoverflow.com/questions/48577465/how-can-i-solve-laravel-horizon-v1-1-0-requires-ext-pcntl-the-requested-ph
    composer global require laravel/envoy
- 4. php artisan key:generate
- 5. php composer dump-autoload
- 6. set ENV
    APP_URL=http://lms.localhost

- 7. MAMP mysql + MBP PHP 7.3.11 (cli) (built: Dec 13 2019 19:21:21) ( NTS )
    但是还是不行 最后发现了一个地方。。。如下勾选上就可以了。。: Allow network access to MYSQL
        vi /etc/php.ini.default
            pdo_mysql.default_socket=/Applications/MAMP/tmp/mysql/mysql.sock
- 8. npm install -g cross-env
    npm install

Deprecation Notice: Class Twilio\TwiML\Voice\Echo_ located in ./vendor/twilio/sdk/src/Twilio/TwiML/Voice/Echo.php does not comply with psr-4 autoloading standard. It will not autoload anymore in Composer v2.0. in phar:///usr/local/bin/composer/src/Composer/Autoload/ClassMapGenerator.php:201