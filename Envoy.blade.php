@servers(['sfo2' => ['root@134.209.0.164']])

@task('backup', ['on' => 'sfo2'])
    cd /root/html/lms-abc

    FILENAME=lms-abc.$(date '+%Y%m%d%H%M%S').db.backup.sql

    echo "scp root@134.209.0.164:/tmp/$FILENAME /tmp/ && mysql -uroot abc < /tmp/$FILENAME"

    docker-compose exec -T db mysqldump -ularavel -plaravel laravel --extended-insert=FALSE --complete-insert=TRUE > /tmp/$FILENAME
@endtask

@task('deploy', ['on' => 'sfo2'])
    cd /root/html/lms-abc
    git checkout . 
    git pull origin master 
    docker-compose up -d --build

    docker-compose exec -T app bash -c "
        php composer dump-autoload \
          && php artisan clear-compiled \
          && php artisan config:clear \
          && php artisan config:cache \
          && php artisan cache:clear \
          && php artisan route:clear \
          && php artisan view:clear \
          && php artisan view:cache \
          && rm -rf public/storage \
          && php artisan storage:link \
          && chown www-data:www-data storage/app/"
@endtask

