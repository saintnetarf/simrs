# Microservice SIMRS - Documentation installation
Instalasi (singkat):

1. Copy repository ini ke server / development.
2. Buat file `.env` dan set `DB_*` ke MySQL.
3. Install dependencies:

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

4. Jalankan dev server:

```bash
php artisan serve
```

5. Dokumentasi API dengan Laravel Scramble tersedia di:

```text
/docs/api
/docs/api.json
```
6. login backend
http://127.0.0.1:8000/login
username = ADM-001
password = password123

username = DR-UMUM-001
password = password123


