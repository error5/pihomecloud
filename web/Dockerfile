FROM debian:buster

MAINTAINER Errors :D

RUN apt update -q && apt install -y bzip2 php-fpm \
    php-gd php-json php-mysql php-curl \
    php-intl php-imagick \
    php-zip php-xml php-mbstring php-redis php-apcu \
    wget

RUN mkdir -p /var/www/pihomecloud/
RUN wget -O /var/www/pihomecloud/latest-17.tar.bz2 https://download.nextcloud.com/server/releases/latest-17.tar.bz2
RUN cd /var/www/pihomecloud && tar --strip-components=1 -xvf latest-17.tar.bz2

ADD www.conf /etc/php/7.3/fpm/pool.d/www.conf
ADD apc.php /var/www/pihomecloud/apc.php
#ADD config.php /var/www/pihomecloud/config/config.php this was for already configured install 

RUN ln -sf /dev/stdout /var/log/php7.3-fpm.log

RUN chown -R www-data:www-data /var/www/pihomecloud

RUN mkdir /run/php

VOLUME ["/var/www/pihomecloud"]

EXPOSE 9000

STOPSIGNAL SIGTERM

# Define default command.
CMD ["/usr/sbin/php-fpm7.3","-F"]

# nginx + php was used with socket config
##CMD ["/usr/bin/php-fpm","-D;","nginx", "-g", "daemon off;"]
