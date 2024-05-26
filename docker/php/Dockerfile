FROM php:8.3-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# تثبيت الحزم والامتدادات اللازمة
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع
COPY . /var/www

# ضبط دليل العمل
WORKDIR /var/www

# تثبيت مكتبات Laravel
RUN composer install

# إعطاء الأذونات الصحيحة
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
