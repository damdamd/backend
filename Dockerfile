FROM php:8.3-cli

# Install pcov for coverage
RUN pecl install pcov && docker-php-ext-enable pcov

RUN mkdir /var/app

ENTRYPOINT ["tail", "-f", "/dev/null"]