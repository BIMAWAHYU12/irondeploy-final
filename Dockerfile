# Gunakan image yang sudah ada PHP dan Apache
FROM php:8.2-apache

# Copy semua file (index.html & kirim.php) ke folder default Apache
COPY . /var/www/html/

# Buka port 80
EXPOSE 80