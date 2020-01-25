FROM php:fpm
RUN apt-get update && apt-get install -y git    
RUN git clone https://github.com/zavrelj/docker-tutorial/ /var/www/html/    
VOLUME ["/var/www/html/"] 
