#!/bin/bash
# ps aux | grep "[/usr/local/bin/php] /var/www/html/artisan horizon"  > /dev/null
if (( `ps aux | grep "[/usr/local/bin/php] artisan horizon:work" | wc -l` > 2 )); then
  echo $(date) ": Process is running." >> /var/www/html/storage/logs/laravel.log
else
  echo $(date) ": ERROR: Process is not running." >> /var/www/html/storage/logs/laravel.log
  pgrep -f horizon | while read -r pid ; do
      kill -9 $pid
  done
  nohup /usr/local/bin/php /var/www/html/artisan horizon >> /var/www/html/storage/logs/laravel.log 2>&1 &
fi
# https://laravel-news.com/laravel-scheduler-queue-docker
# https://laracasts.com/discuss/channels/servers/run-the-scheduler-in-a-docker-image
# https://laravel-china.org/articles/7022/using-laravel-schedule-in-docker
# https://laravel-china.org/topics/2199/docker-container-configuration-plan-task-crontab-daocloud-docker-laravel-5
# * * * * * docker exec -i docker-wechat_laravel_1 sh /var/www/html/cron.sh
# * * * * * docker exec -i docker-wechat_laravel_1 php /var/www/[PROJECT_FOLDER]/artisan schedule:run >> /dev/null 2>&1
# todo queue
# php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=90
