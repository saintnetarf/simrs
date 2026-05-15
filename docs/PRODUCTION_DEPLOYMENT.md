# Production Deployment Checklist for Scramble API Documentation

## ✅ Pre-Deployment

### 1. Security Review
- [ ] Remove debug information from API responses
- [ ] Validate all error messages don't expose system details
- [ ] Review all OpenAPI documentation for sensitive data
- [ ] Test authentication & authorization

### 2. Disable Documentation Access
```env
# .env production
SCRAMBLE_EXPOSE_DOCS=false
APP_DEBUG=false
```

### 3. Export OpenAPI Specification
```bash
php artisan scramble:export api.json
```

Store the exported JSON for:
- Client SDK generation
- External documentation sites
- API gateway configuration

---

## 📦 Deployment Steps

### 1. Build Assets
```bash
npm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
```

### 2. Database Migrations
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 3. Start Application
```bash
php artisan serve --host=0.0.0.0 --port=80
# Or use Supervisor/Systemd for process management
```

### 4. Verify API is Working
```bash
curl https://your-api.example.com/api/auth/login \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"username":"test","password":"test"}'
```

---

## 🔐 Production Security

### 1. Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
SCRAMBLE_EXPOSE_DOCS=false  # Hide documentation
APP_KEY=your-generated-key
AUTH_CENTER_JWT_SECRET=your-long-random-secret
```

### 2. Rate Limiting
```php
// app/Http/Middleware/
protected function limit($request)
{
    return 60;  // 60 requests per minute
}
```

### 3. CORS Configuration
```php
// config/cors.php
'allowed_origins' => ['https://yourdomain.com'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['Authorization', 'Content-Type'],
```

### 4. HTTPS Enforcement
```php
// In routes or middleware
if (app()->environment('production')) {
    \URL::forceScheme('https');
}
```

### 5. API Key Management
- Store JWT_SECRET in secure vault (AWS Secrets Manager, HashiCorp Vault)
- Rotate keys regularly
- Log all authentication attempts

---

## 🚀 Deployment Options

### Option 1: Traditional Server
1. SSH into production server
2. Clone repository
3. Install dependencies: `composer install --no-dev`
4. Run migrations: `php artisan migrate --force`
5. Cache configuration: `php artisan config:cache`
6. Start with Supervisor:
```ini
[program:laravel-api]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/app/artisan serve --host=127.0.0.1 --port=8001
numprocs=1
autostart=true
autorestart=true
```

### Option 2: Docker Container
```dockerfile
FROM php:8.3-fpm

WORKDIR /app

COPY . /app/

RUN composer install --no-dev

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
```

```bash
docker build -t simrs-api:latest .
docker run -d -p 8000:8000 \
  -e APP_ENV=production \
  -e SCRAMBLE_EXPOSE_DOCS=false \
  simrs-api:latest
```

### Option 3: Cloud Platform (AWS, GCP, Azure)
- **AWS AppRunner** - No container expertise needed
- **Google Cloud Run** - Serverless containerized apps
- **Azure Container Instances** - On-demand containerized apps
- **AWS Elastic Beanstalk** - Managed PHP hosting

---

## 📊 Monitoring

### 1. Application Logging
```bash
# Monitor real-time logs
tail -f /var/log/laravel.log
# Or use logging service (ELK, Datadog, New Relic)
```

### 2. Error Tracking
```php
// Register error tracking service
// config/services.php
'sentry' => [
    'dsn' => env('SENTRY_DSN'),
],
```

### 3. Performance Monitoring
- Monitor response times
- Track database query performance
- Monitor API usage per endpoint
- Alert on error rates

### 4. Health Check Endpoint
```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'database' => DB::connection()->getPDO() ? 'connected' : 'disconnected',
    ]);
});
```

---

## 📝 Documentation Sharing

After disabling Scramble docs in production:

### Option 1: Separate Documentation Site
Host OpenAPI spec on separate domain:
```
https://docs.yourdomain.com
```

Use tools like:
- **Redocly** - Beautiful static documentation
- **Swagger UI** - Interactive online documentation
- **Postman** - Publish public workspace

### Option 2: Postman Public Workspace
1. Export: `php artisan scramble:export api.json`
2. Import to Postman
3. Publish workspace publicly
4. Share link with clients

### Option 3: Internal Documentation Server
Keep internal server with docs enabled:
```
https://internal-docs.yourdomain.com  (requires VPN)
```

---

## 🔄 CI/CD Pipeline

### GitHub Actions Example
```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Install dependencies
        run: composer install --no-dev
      
      - name: Run tests
        run: php artisan test
      
      - name: Deploy to production
        run: |
          ssh user@server 'cd /var/www/app && git pull && composer install --no-dev && php artisan migrate --force'
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| 500 error | Check error logs in `storage/logs/` |
| Slow API | Check database queries, add caching |
| Authentication fails | Verify JWT_SECRET matches across services |
| High error rate | Check for rate limiting, connection issues |
| Memory issues | Increase PHP memory_limit, optimize code |

---

## ✅ Post-Deployment Verification

- [ ] API endpoints responding correctly
- [ ] Authentication & token validation working
- [ ] Database migrations completed
- [ ] All services can communicate
- [ ] Logging & monitoring active
- [ ] Error handling working properly
- [ ] Rate limiting active
- [ ] HTTPS enforced
- [ ] CORS configured correctly
- [ ] Health check responding

---

## 📞 Support & Maintenance

### Regular Tasks
- [ ] Monitor error logs daily
- [ ] Review performance metrics weekly
- [ ] Update dependencies monthly
- [ ] Security patches immediately
- [ ] Backup database daily

### On-Call Support
- Set up alerts for critical errors
- Document escalation procedures
- Have rollback plan ready

---

## 📚 Related Documentation

- [Scramble Setup Guide](./SCRAMBLE_SETUP.md)
- [Scramble Quick Reference](./SCRAMBLE_QUICK_REFERENCE.md)
- [Middleware Setup Guide](./MIDDLEWARE_SETUP_GUIDE.md)
- [Patient Journey Flow](./SEQUENCE_FLOW_PATIENT_JOURNEY.md)

---

**Last Updated:** May 13, 2026
**Framework:** Laravel 13
**Scramble Version:** 0.13.22
