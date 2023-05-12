FROM ubuntu:22.04

RUN apt update -y
RUN DEBIAN_FRONTEND=noninteractive TZ=America/New_York apt-get -y install tzdata
RUN apt install -y apache2 php libapache2-mod-php php-mysql php-mbstring php-xml php-curl composer cron
RUN mkdir /var/www/Intranet-Home-Page
COPY . /var/www/Intranet-Home-Page
RUN cp /var/www/Intranet-Home-Page/000-default.conf /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www/Intranet-Home-Page/ThirdParty
RUN composer install
RUN a2enmod rewrite
RUN mkdir /var/spool/crontabs
RUN crontab -l | { cat; echo "*/5 * * * * cd /var/www/Intranet-Home-Page/app && php cron.php"; } | crontab -
RUN chmod +x /var/www/Intranet-Home-Page/entrypoint.sh
ENTRYPOINT ["bash", "/var/www/Intranet-Home-Page/entrypoint.sh"]
