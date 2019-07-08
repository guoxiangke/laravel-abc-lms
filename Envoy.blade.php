@servers(['sfo2' => 'root@178.128.14.110', 'qq3' => 'root@154.8.216.229'])

#step1 CN备份用户相关表后，导出到sfo2，
#step2 从sfo2合并后的全部sql sync->CN.
@story('sync')
    syncStep1
    syncStep2
@endstory

@story('deploy')
    deploy01
    qq3disablequeue
@endstory

@story('backup')
    backupCN
    backupEN
@endstory

#step1 CN备份用户相关表后，导出到sfo2
@task('syncStep1', ['on' => 'qq3'])
    cd /var/www/html/lms-abc
    FILENAME=lms-abc.sync.$(date '+%Y%m%d%H%M%S').db.backup.sql
    docker-compose exec -T db mysqldump -uroot -proot laravel \
      users \
      roles \
      profiles \
      agencies \
      contacts \
      zooms \
      schools \
      students \
      teachers \
      books \
      pay_methods \
      permissions \
      role_has_permissions \
      model_has_permissions \
      model_has_roles \
      audits \
      products \
      bills > /tmp/$FILENAME \
    && echo 'backed some tables on qq3.' \
    && echo 'sync to sfo2 ...' \
    && scp /tmp/$FILENAME root@178.128.14.110:/tmp/ \
    && rm /tmp/$FILENAME \
    && echo 'synced to sfo2 & deleted on qq3.' \
    && ssh root@178.128.14.110<< EOF
      cd /var/www/html/lms-abc \
      && echo 'import to sfo2 ...' \
      && docker-compose exec -T db mysql -uroot -proot laravel < /tmp/$FILENAME \
      && rclone copy /tmp/$FILENAME 501:/backup/abc/db/sync -v \
      && rm /tmp/$FILENAME \
      && echo 'imported to sfo2 & deleted /tmp/$FILENAME on sfo2.'
    EOF
@endtask

#step2 从sfo2合并后的全部sql sync->CN.
@task('syncStep2', ['on' => 'sfo2'])
    cd /var/www/html/lms-abc
    FILENAME=lms-abc.synced.$(date '+%Y%m%d%H%M%S').db.backup.sql
    echo 'backup all tables on sfo2...'
    docker-compose exec -T db mysqldump -uroot -proot laravel  > /tmp/$FILENAME  \
    && echo 'backup all tables done & scp to qq3...' \
    && scp -v /tmp/$FILENAME root@154.8.216.229:/tmp/ \
    && rclone copy /tmp/$FILENAME 501:/backup/abc/db/synced -v \
    && rm /tmp/$FILENAME \
    && ssh root@154.8.216.229<< EOF
      cd /var/www/html/lms-abc \
      && echo 'synced to qq3 all ...' \
      && docker-compose exec -T db mysql -uroot -proot laravel < /tmp/$FILENAME \
      && rm /tmp/$FILENAME
    EOF
@endtask

#1.备份
#2.传输到sfo2上，备份到501drive
@task('backupCN', ['on' => 'qq3'])
    cd /var/www/html/lms-abc
    FILENAME=cn.lms.abc.$(date '+%Y%m%d%H%M%S').db.backup.sql

    echo "Backup On CN begin ..."
    echo "scp root@154.8.216.229:/tmp/$FILENAME /tmp/ && mysql -uroot abc < /tmp/$FILENAME"
    
    docker-compose exec -T db mysqldump -uroot -proot laravel > /tmp/$FILENAME \
    && echo 'sync to sfo2 ...' \
    && scp /tmp/$FILENAME root@178.128.14.110:/tmp/ \
    && echo 'deleted on qq3 ...' \
    && rm /tmp/$FILENAME -rf \
    && echo "Login in SFO2 begin to backup to 501..." \
    && ssh root@178.128.14.110<< EOF
      echo 'sync to 501 begin...' \
      && rclone copy /tmp/$FILENAME 501:/backup/abc/db/ -v \
      && rm /tmp/$FILENAME -rf \
      && echo 'synced to 501 & deleted backup on SFO2 SUCCESS'
    EOF
@endtask

@task('backupEN', ['on' => 'sfo2'])
    cd /var/www/html/lms-abc
    FILENAME=en.lms.abc.$(date '+%Y%m%d%H%M%S').db.backup.sql
    echo "scp root@154.8.216.229:/tmp/$FILENAME /tmp/ && mysql -uroot abc < /tmp/$FILENAME"
    # --extended-insert=FALSE --complete-insert=TRUE
    docker-compose exec -T db mysqldump -uroot -proot laravel  > /tmp/$FILENAME \
    && rclone copy /tmp/$FILENAME 501:/backup/abc/db/ -v \
    && rm /tmp/$FILENAME -rf \
    && echo 'synced to 501 & deleted backupEN SUCCESS'
@endtask

@task('deploy01', ['on' => ['sfo2','qq3'], 'parallel' => true])
    cd /var/www/html/lms-abc
    # git checkout . 
    git pull origin master 
    cat docker-compose.yml | grep lms
    docker-compose down
    # 更新代码
    docker volume rm lms-abc_code
    docker-compose up -d --build

    docker network connect bridge abc-webserver
    docker restart abc-webserver a-nginx a-nginx-gen

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
          && chown www-data:www-data storage/app/ -R"
@endtask

@task('qq3disablequeue', ['on' => ['qq3']])
 cd /var/www/html/lms-abc
 docker-compose stop scheduler queue
@endtask

@task('cg', ['on' => 'sfo2'])
    cd /var/www/html/lms-abc
    docker-compose exec -T app bash -c "
        php artisan classrecords:generate 1 --order=2
        "
@endtask


@task('backupCN4dev', ['on' => 'qq3'])
    cd /var/www/html/lms-abc
    FILENAME=cn.lms.abc.$(date '+%Y%m%d%H%M%S').db.backup.sql

    echo "Backup On CN begin ..."
    echo "scp root@154.8.216.229:/tmp/$FILENAME /tmp/ && mysql -uroot abc < /tmp/$FILENAME"
    
    docker-compose exec -T db mysqldump -uroot -proot laravel > /tmp/$FILENAME
@endtask
