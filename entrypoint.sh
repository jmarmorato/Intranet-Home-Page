php /var/www/Intranet-Home-Page/app/entrypoint.php
service cron start
php /var/www/Intranet-Home-Page/app/cron.php
/usr/sbin/apache2ctl -DFOREGROUND
