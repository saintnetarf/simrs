# Tutorial Instalasi Server Aplikasi Laravel (Apache + MySQL + PHP 8.4 FPM)

Dokumen ini berisi panduan dari nol untuk menyiapkan server Linux (Ubuntu 24.04) dengan:

- Apache2
- MySQL Server
- PHP 8.4 FPM
- Virtual Host Apache
- SSL gratis Let's Encrypt
- User MySQL khusus aplikasi

## 1) Persiapan Awal Server

Update paket sistem terlebih dahulu.

~~~bash
sudo apt update && sudo apt upgrade -y
sudo timedatectl set-timezone Asia/Jakarta
~~~

Opsional tapi disarankan: aktifkan firewall dasar.

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
sudo systemctl status apache2
~~~

Aktifkan modul Apache yang dibutuhkan.

~~~bash
sudo a2enmod rewrite headers ssl proxy_fcgi setenvif
sudo systemctl reload apache2
~~~

## 3) Instalasi MySQL Server

~~~bash
sudo apt install mysql-server -y
sudo systemctl enable --now mysql
sudo systemctl status mysql
~~~

Amankan instalasi MySQL.

~~~bash
sudo mysql_secure_installation
~~~

Saran saat setup:

- Aktifkan validasi password: sesuai kebutuhan
- Hapus anonymous users: Yes
- Disable remote root login: Yes
- Hapus test database: Yes
- Reload privilege tables: Yes

## 4) Instalasi PHP 8.4 FPM

Pada Ubuntu, PHP 8.4 biasanya tersedia via PPA Ondrej.

~~~bash
sudo apt install software-properties-common ca-certificates lsb-release apt-transport-https -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
~~~

Install PHP 8.4 FPM dan ekstensi umum untuk Laravel.

~~~bash
sudo apt install -y \
  php8.4-fpm php8.4-cli php8.4-common php8.4-mysql \
  php8.4-curl php8.4-mbstring php8.4-xml php8.4-zip \
  php8.4-bcmath php8.4-intl php8.4-gd php8.4-soap
~~~

Aktifkan service PHP-FPM.

~~~bash
sudo systemctl enable --now php8.4-fpm
sudo systemctl status php8.4-fpm
~~~

## 5) Struktur Folder untuk Domain

Contoh domain: example.com

Gunakan struktur folder terpisah agar rapi dan aman.

~~~text
/var/www/
  example.com/
    current/          # source code aktif (Laravel)
    shared/           # file bersama (jika pakai release strategy)
    logs/             # log aplikasi tambahan (opsional)
~~~

Untuk setup sederhana Laravel, minimal:

~~~text
/var/www/example.com/current
~~~

Buat folder dan atur kepemilikan user web server.

~~~bash
sudo mkdir -p /var/www/example.com/current
sudo chown -R www-data:www-data /var/www/example.com
sudo find /var/www/example.com -type d -exec chmod 755 {} \;
sudo find /var/www/example.com -type f -exec chmod 644 {} \;
~~~

Catatan Laravel:

- DocumentRoot Apache harus mengarah ke folder public Laravel.
- Folder storage dan bootstrap/cache harus writable oleh www-data.

~~~bash
sudo chown -R www-data:www-data /var/www/example.com/current
sudo chmod -R ug+rwx /var/www/example.com/current/storage
sudo chmod -R ug+rwx /var/www/example.com/current/bootstrap/cache
~~~

## 6) Konfigurasi Virtual Host Apache

Buat file virtual host baru.

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

    # Jalankan PHP melalui PHP-FPM socket
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.4-fpm.sock|fcgi://localhost/"
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/example.com-error.log
    CustomLog ${APACHE_LOG_DIR}/example.com-access.log combined
</VirtualHost>
~~~

Aktifkan site dan nonaktifkan default site.

~~~bash
sudo a2ensite example.com.conf
sudo a2dissite 000-default.conf
sudo apache2ctl configtest
sudo systemctl reload apache2
~~~

## 7) SSL Gratis dari Let's Encrypt

Pastikan domain sudah mengarah ke IP server (A record) sebelum langkah ini.

Install Certbot untuk Apache.

~~~bash
sudo apt install certbot python3-certbot-apache -y
~~~

Minta dan pasang sertifikat SSL otomatis.

~~~bash
sudo certbot --apache -d example.com -d www.example.com
~~~

Saat prompt:

- Pilih redirect HTTP ke HTTPS: Redirect (recommended)

Cek auto-renewal.

~~~bash
sudo systemctl status certbot.timer
sudo certbot renew --dry-run
~~~

## 8) Pembuatan User MySQL Khusus Aplikasi

Masuk ke MySQL sebagai root.

~~~bash
sudo mysql
~~~

Buat database dan user khusus aplikasi.

~~~sql
CREATE DATABASE rs_cepat_sembuh
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER 'rs_app'@'localhost' IDENTIFIED BY 'GantiDenganPasswordKuat!';

GRANT ALL PRIVILEGES ON rs_cepat_sembuh.* TO 'rs_app'@'localhost';
FLUSH PRIVILEGES;
~~~

Verifikasi hak akses:

~~~sql
SHOW GRANTS FOR 'rs_app'@'localhost';
EXIT;
~~~

Gunakan kredensial ini di file .env Laravel.

~~~env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rs_cepat_sembuh
DB_USERNAME=rs_app
DB_PASSWORD=GantiDenganPasswordKuat!
~~~

## 9) Checklist Verifikasi

- Apache aktif dan vhost domain terbaca
- PHP-FPM 8.4 aktif
- MySQL aktif
- Domain bisa diakses via HTTPS valid
- Migrasi Laravel bisa jalan dengan user MySQL aplikasi

Perintah uji cepat:

~~~bash
php -v
sudo systemctl status apache2
sudo systemctl status php8.4-fpm
sudo systemctl status mysql
curl -I https://example.com
~~~

## 10) Catatan Keamanan Tambahan

- Gunakan password database yang kuat dan unik.
- Batasi akses SSH (non-root + key auth jika memungkinkan).
- Update sistem berkala: sudo apt update && sudo apt upgrade.
- Backup database dan file aplikasi secara rutin.
