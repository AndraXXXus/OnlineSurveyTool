FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 0

# Install node and npm for Vite
RUN apk add --update nodejs npm

# # Install NPM dependencies
RUN npm install

# # Build Vite assets
RUN npm run build

RUN composer install --working-dir=/var/www/html

CMD ["/start.sh"]

# COPY . .

# RUN apk update

# # Install the `npm` package
# RUN apk add --no-cache npm

# # Install NPM dependencies
# RUN npm install

# # Build Vite assets
# RUN npm run build

# CMD ["/start.sh"]
