echo "Taking a 25 second break to make sure MySQL is fully ready"
sleep 25
php /var/www/Intranet-Home-Page/app/entrypoint.php
service cron start
php /var/www/Intranet-Home-Page/app/cron.php
/usr/sbin/apache2ctl -DFOREGROUND
