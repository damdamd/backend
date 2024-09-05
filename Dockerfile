FROM php:8.3-cli
ARG UID

# install php extensions
RUN apt-get update
RUN apt-get install -y libzip-dev libpq-dev
RUN docker-php-ext-install zip pgsql pdo_pgsql pdo
RUN pecl install pcov
RUN docker-php-ext-enable pcov

RUN mkdir /var/app

# Install symfony cli
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli -y


# Create user with local uid
RUN adduser application --uid ${UID}

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENTRYPOINT ["tail", "-f", "/dev/null"]