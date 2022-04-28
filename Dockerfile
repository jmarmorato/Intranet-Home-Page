FROM ubuntu:20.04

RUN apt update -y
RUN DEBIAN_FRONTEND=noninteractive TZ=America/New_York apt-get -y install tzdata
RUN apt install -y apache2 php libapache2-mod-php php-mysql php-mbstring php-xml php-curl
RUN mkdir /var/www/Intranet-Home-Page
COPY . /var/www/Intranet-Home-Page
RUN cp /var/www/Intranet-Home-Page/000-default.conf /etc/apache2/sites-available/000-default.conf
