# Tutorial Instalasi Laravel di Ubuntu 22.04

Panduan ini khusus untuk Ubuntu 22.04 (Jammy) dengan komponen:

- Apache2
- MySQL Server
- PHP 8.4 FPM (via PPA Ondrej)
- Virtual Host Apache
- SSL gratis Let's Encrypt
- User MySQL khusus aplikasi

## 1) Update Sistem

~~~bash
sudo apt update && sudo apt upgrade -y
sudo timedatectl set-timezone Asia/Jakarta
~~~

Opsional firewall:

~~~bash
sudo apt install ufw -y
sudo ufw allow OpenSSH
sudo ufw allow "Apache Full"
sudo ufw enable
sudo ufw status
~~~

## 2) Instalasi Apache

~~~bash
sudo apt install apache2 -y
sudo systemctl enable --now apache2
~~~

Aktifkan modul penting:

~~~bash
sudo a2enmod rewrite headers ssl proxy_fcgi setenvif
sudo systemctl reload apache2
~~~

## 3) Instalasi MySQL Server

~~~bash
sudo apt install mysql-server -y
sudo systemctl enable --now mysql
~~~

Amankan instalasi:

~~~bash
sudo mysql_secure_installation
~~~

Rekomendasi jawaban:

- Remove anonymous users: Yes
- Disallow root login remotely: Yes
- Remove test database and access: Yes
- Reload privilege tables now: Yes

## 4) Instalasi PHP 8.4 FPM (Ubuntu 22.04)

Ubuntu 22.04 tidak menyediakan PHP 8.4 bawaan, jadi gunakan PPA Ondrej.

~~~bash
sudo apt install -y software-properties-common ca-certificates lsb-release apt-transport-https
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
~~~

Install PHP 8.4 + ekstensi umum Laravel:

~~~bash
sudo apt install -y \
  php8.4-fpm php8.4-cli php8.4-common php8.4-mysql \
  php8.4-curl php8.4-mbstring php8.4-xml php8.4-zip \
  php8.4-bcmath php8.4-intl php8.4-gd php8.4-soap
~~~

Aktifkan service:

~~~bash
sudo systemctl enable --now php8.4-fpm
sudo systemctl status php8.4-fpm
php -v
~~~

## 5) Struktur Folder Domain

Contoh domain: example.com

~~~text
/var/www/
  example.com/
    current/   # source code Laravel aktif
    shared/    # file bersama (opsional untuk strategi release)
    logs/      # log tambahan (opsional)
~~~

Setup minimal:

~~~bash
sudo mkdir -p /var/www/example.com/current
sudo chown -R www-data:www-data /var/www/example.com
sudo find /var/www/example.com -type d -exec chmod 755 {} \;
sudo find /var/www/example.com -type f -exec chmod 644 {} \;
~~~

Jika source Laravel sudah ada:

~~~bash
sudo chown -R www-data:www-data /var/www/example.com/current
sudo chmod -R ug+rwx /var/www/example.com/current/storage
sudo chmod -R ug+rwx /var/www/example.com/current/bootstrap/cache
~~~

## 6) Konfigurasi Virtual Host Apache

Buat file vhost:

~~~bash
sudo nano /etc/apache2/sites-available/example.com.conf
~~~

Isi konfigurasi:

~~~apache
<VirtualHost *:80>
    ServerName example.com
    ServerAlias www.example.com
    ServerAdmin webmaster@example.com

    DocumentRoot /var/www/example.com/current/public

    <Directory /var/www/example.com/current/public>
        AllowOverride All
        Require all granted
    </Directory>

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.4-fpm.sock|fcgi://localhost/"
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/example.com-error.log
    CustomLog ${APACHE_LOG_DIR}/example.com-access.log combined
</VirtualHost>
~~~

Aktifkan site:

~~~bash
sudo a2ensite example.com.conf
sudo a2dissite 000-default.conf
sudo apache2ctl configtest
sudo systemctl reload apache2
~~~

## 7) SSL Gratis Let's Encrypt

Pastikan DNS sudah mengarah ke IP server.

Install certbot:

~~~bash
sudo apt install certbot python3-certbot-apache -y
~~~

Generate dan pasang SSL:

~~~bash
sudo certbot --apache -d example.com -d www.example.com
~~~

Pilih redirect HTTP ke HTTPS saat diminta.

Verifikasi auto-renew:

~~~bash
sudo systemctl status certbot.timer
sudo certbot renew --dry-run
~~~

## 8) User MySQL Khusus Aplikasi

Masuk MySQL:

~~~bash
sudo mysql
~~~

Buat database + user khusus:

~~~sql
CREATE DATABASE rs_cepat_sembuh
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER 'rs_app'@'localhost' IDENTIFIED BY 'GantiDenganPasswordKuat!';
GRANT ALL PRIVILEGES ON rs_cepat_sembuh.* TO 'rs_app'@'localhost';
FLUSH PRIVILEGES;

SHOW GRANTS FOR 'rs_app'@'localhost';
EXIT;
~~~

Set .env Laravel:

~~~env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rs_cepat_sembuh
DB_USERNAME=rs_app
DB_PASSWORD=GantiDenganPasswordKuat!
~~~

## 9) Verifikasi Akhir

~~~bash
php -v
sudo systemctl status apache2
sudo systemctl status php8.4-fpm
sudo systemctl status mysql
curl -I https://example.com
~~~

Untuk Laravel:

~~~bash
cd /var/www/example.com/current
php artisan config:clear
php artisan migrate --force
~~~

## 10) Catatan Khusus Ubuntu 22.04

- Karena PHP 8.4 berasal dari PPA, lakukan update rutin dan pantau kompatibilitas ekstensi.
- Jika ada lebih dari satu versi PHP terpasang, pastikan Apache mengarah ke socket yang benar: /run/php/php8.4-fpm.sock.
- Cek modul Apache aktif: rewrite, proxy_fcgi, setenvif, ssl.
