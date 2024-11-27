FROM php:8.3.11-fpm
RUN echo "running php-fpm"
RUN apt update
RUN echo "installing mysqli"
RUN apt upgrade -y
RUN docker-php-ext-install mysqli
RUN echo "restarting php-fpm"
RUN kill -USR2 1
RUN echo "php-fpm restarted sucessfully"