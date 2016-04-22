FROM php:5.5-apache

# install requirements
RUN apt-get update && apt-get install -y \
  imagemagick \
  curl \
  php5-curl \
  php5-mysql \
  php5-gd \
  php5-cli \
  php5-intl \
  php5-ldap \
  libldap2-dev \
  libcurl3-gnutls \
  libpng-dev \
  libicu-dev \
  php-apc \
  php5-memcached \
  libapache2-mod-xsendfile \
  software-properties-common \
  nano \
  zip \
  unzip

# install up to dat eversion of git for composer
RUN echo 'deb http://ftp.us.debian.org/debian testing main contrib non-free' >> /etc/apt/sources.list && \
    apt-get update && \
    apt-get install -y git/testing

# install app to html folder
RUN git clone https://github.com/humhub/humhub.git /var/www/html/.

# checkout stable repo
RUN (cd /var/www/html && git checkout tags/v1.0.0)

# install PHP composer
RUN (cd /usr/src && \
     curl -sS https://getcomposer.org/installer | \
     php -- --install-dir=/usr/local/bin --filename=composer)

# this is needed so that composer can authenticate against github
RUN composer config --global github-oauth.github.com 6e519571199f67a7c4dd04110ef7c21191ecd5b0

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

# install dependencies
RUN (cd /var/www/html && \
     composer global require "fxp/composer-asset-plugin:~1.1.0" && \
     composer update)

# https://github.com/docker-library/php/issues/75
RUN docker-php-ext-install mbstring gd exif zip intl pdo pdo_mysql

# enable pretty urls
RUN mv /var/www/html/.htaccess.dist /var/www/html/.htaccess

# enable apache mod rewrite
RUN a2enmod rewrite

# add customized files
#ADD index.php index.php
ADD common.php protected/humhub/config/common.php
ADD Application.php protected/humhub/components/Application.php
ADD crontab /etc/crontab

# do ownership
RUN chown -R www-data /var/www/html

# do write permissions
RUN (cd /var/www/html && \
     chmod -R 775 assets \
                  protected/config \
                  protected/modules \
                  protected/runtime \
                  uploads)

# do executable permissions
RUN (cd /var/www/html && \
     chmod +x protected/yii \
              protected/yii.bat)