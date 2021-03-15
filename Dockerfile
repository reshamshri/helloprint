FROM php:7.4-fpm

RUN apt-get update -yqq \
    && apt-get install -y --no-install-recommends \
       apt-utils \
       curl \
       libz-dev \
       libpq-dev \
       libssl-dev \
       libmcrypt-dev \
       librdkafka-dev \
       libzip-dev \
       libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN  docker-php-ext-configure zip 

RUN pecl channel-update pecl.php.net

# DOCKER PHP EXTS
RUN docker-php-ext-install  bcmath  
RUN docker-php-ext-install  fileinfo 
RUN docker-php-ext-install  iconv
RUN docker-php-ext-install  pcntl
RUN docker-php-ext-install  pdo
RUN docker-php-ext-install  pdo_pgsql
RUN docker-php-ext-install  tokenizer
RUN docker-php-ext-install  xml
RUN docker-php-ext-install  zip
RUN docker-php-ext-install  intl

# PECL
RUN pecl install rdkafka


# ENABLING EXTENSIONS
RUN docker-php-ext-enable pcntl bcmath tokenizer zip pdo_pgsql rdkafka



# CLEANUP
RUN rm -rf /tmp/pear \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

WORKDIR /var/www

CMD ["php-fpm"]