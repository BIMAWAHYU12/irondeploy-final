# 1. Gunakan Image PHP + Apache
FROM php:8.2-apache

# 2. Update & Install msmtp (Klien SMTP Ringan)
RUN apt-get update && \
    apt-get install -y msmtp msmtp-mta && \
    apt-get clean

# 3. Konfigurasi msmtp supaya nyambung ke Postfix VPS
# (Ini kuncinya: Kita suruh dia nembak ke IP VPS kamu di Port 25)
RUN echo "defaults" > /etc/msmtprc && \
    echo "auth           off" >> /etc/msmtprc && \
    echo "tls            off" >> /etc/msmtprc && \
    echo "logfile        /var/log/msmtp.log" >> /etc/msmtprc && \
    echo "host           157.10.253.59" >> /etc/msmtprc && \
    echo "port           25" >> /etc/msmtprc && \
    echo "from           admin@irondeploy.my.id" >> /etc/msmtprc

# 4. Beri tahu PHP buat pakai msmtp sebagai kurir
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/sendmail.ini

# 5. Copy file website kamu
COPY . /var/www/html/

# 6. Buka Port
EXPOSE 80