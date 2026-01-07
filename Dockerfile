# 1. Gunakan Image PHP + Apache (Wajib ada PHP-nya)
FROM php:8.2-apache

# 2. Update & Install msmtp (Kurir Email Ringan untuk Container)
RUN apt-get update && \
    apt-get install -y msmtp msmtp-mta && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# 3. Konfigurasi msmtp (PENTING: Setting Jalur ke Host)
# - host 172.17.0.1 : Adalah IP Gateway (Jalur Belakang) menuju VPS Host
# - port 25         : Port Postfix di VPS Host
# - auth/tls off    : Kita matikan login karena sudah whitelist IP di main.cf tadi
RUN echo "defaults" > /etc/msmtprc && \
    echo "account default" >> /etc/msmtprc && \
    echo "host 172.17.0.1" >> /etc/msmtprc && \
    echo "port 25" >> /etc/msmtprc && \
    echo "auth off" >> /etc/msmtprc && \
    echo "tls off" >> /etc/msmtprc && \
    echo "from admin@irondeploy.my.id" >> /etc/msmtprc && \
    echo "logfile /var/log/msmtp.log" >> /etc/msmtprc

# 4. Beri tahu PHP untuk pakai msmtp saat fungsi mail() dipanggil
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/sendmail.ini

# 5. Copy Semua File Website (index.html & kirim.php)
COPY . /var/www/html/

# 6. Pastikan permission benar agar Apache bisa baca
RUN chown -R www-data:www-data /var/www/html

# 7. Buka Port Web
EXPOSE 80