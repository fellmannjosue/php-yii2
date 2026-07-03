# Runtime PHP con Apache para Yii2 (framework real).
FROM php:8.3-apache

RUN apt-get update && apt-get install -y libicu-dev unzip \
 && docker-php-ext-install intl pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# DocumentRoot -> web/ (front controller de Yii2) y permisos de escritura
RUN { \
      echo '<VirtualHost *:80>'; \
      echo '  DocumentRoot /var/www/html/web'; \
      echo '  <Directory /var/www/html/web>'; \
      echo '    AllowOverride All'; \
      echo '    Require all granted'; \
      echo '    DirectoryIndex index.php'; \
      echo '  </Directory>'; \
      echo '</VirtualHost>'; \
    } > /etc/apache2/sites-available/000-default.conf \
 && chmod -R 777 runtime web/assets

EXPOSE 80
