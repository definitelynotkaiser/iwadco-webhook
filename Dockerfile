# ✅ Base image na may PHP + Apache
FROM php:8.2-apache

# ✅ Install mysqli at curl para sa PayMongo API
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# ✅ Copy project files to Apache directory
COPY . /var/www/html/

# ✅ Set working directory
WORKDIR /var/www/html/

# ✅ Allow logs
RUN touch /var/www/html/webhook_log.txt && chmod 666 /var/www/html/webhook_log.txt

# ✅ Expose port 80 (Render requirement)
EXPOSE 80

# ✅ Start Apache
CMD ["apache2-foreground"]
