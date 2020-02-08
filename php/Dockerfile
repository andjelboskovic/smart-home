FROM php:7.2-fpm
RUN docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get install -y gnupg2
RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E1DD270288B4E6030699E45FA1715D88E1DF1F24
RUN su -c "echo 'deb http://ppa.launchpad.net/git-core/ppa/ubuntu trusty main' > /etc/apt/sources.list.d/git.list"
RUN apt-get -y install git
#RUN php /var/www/html/composer.phar update //todo inspect how to update composer