# API Documentation - Auth Center Service (Kelompok 1)

## Base URL
```
http://auth-center:8001/api
```

## Authentication Header
```
Authorization: Bearer {access_token}
```

---

## 1. POST /auth/login
**Endpoint publik untuk login user dari semua service.**

### Request
```json
{
  "username": "kasir001",
  "password": "password123",
  "client_service": "billing_pharmacy"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "Bearer",
    "expires_in": 3600,
    "user": {
      "id": "usr_001",
      "username": "kasir001",
      "full_name": "Budi Kasir",
      "email": "kasir@hospital.local",
      "role": "kasir",
      "service_access": ["billing_pharmacy"]
    }
  },
  "meta": {
    "request_id": "req_123456"
  }
}
```

### Response Error (401)
```json
{
  "success": false,
  "message": "Kredensial tidak valid",
  "data": null,
  "errors": {
    "code": "INVALID_CREDENTIALS",
    "detail": "Username atau password salah"
  },
  "meta": {
    "request_id": "req_123457"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| INVALID_CREDENTIALS | 401 | Username/password salah |
| ACCOUNT_INACTIVE | 403 | Akun tidak aktif |
| ACCOUNT_LOCKED | 403 | Akun terkunci (login failed > 5x) |
| INVALID_CLIENT_SERVICE | 400 | client_service tidak dikenali |

---

## 2. POST /auth/introspect
**Endpoint untuk verifikasi token (digunakan oleh service lain).**

### Request
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "active": true,
    "user_id": "usr_001",
    "username": "kasir001",
    "role": "kasir",
    "email": "kasir@hospital.local",
    "exp": 1684156800,
    "iat": 1684153200,
    "service_access": ["billing_pharmacy"]
  },
  "meta": {
    "request_id": "req_123458"
  }
}
```

### Response Invalid Token (200)
```json
{
  "success": true,
  "data": {
    "active": false
  },
  "meta": {
    "request_id": "req_123459"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| INVALID_TOKEN | 400 | Token tidak valid |
| TOKEN_EXPIRED | 400 | Token sudah kadaluarsa |

---

## 3. POST /auth/logout
**Logout user dan invalidate token.**

### Request
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Logout berhasil",
  "data": null,
  "meta": {
    "request_id": "req_123460"
  }
}
```

---

## 4. GET /auth/refresh-token
**Refresh access token dengan Bearer token yang masih valid.**

### Request Header
```
Authorization: Bearer {access_token}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Token berhasil diperbarui",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "Bearer",
    "expires_in": 3600
  },
  "meta": {
    "request_id": "req_123461"
  }
}
```

---

## 5. POST /users
**Create user baru (admin only).**

### Request
```json
{
  "username": "dokter001",
  "password": "password123",
  "email": "dokter001@hospital.local",
  "full_name": "Dr. Ahmad",
  "role": "dokter",
  "service_access": ["examination_prescription"],
  "is_active": true
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "User berhasil dibuat",
  "data": {
    "id": "usr_002",
    "username": "dokter001",
    "email": "dokter001@hospital.local",
    "full_name": "Dr. Ahmad",
    "role": "dokter",
    "service_access": ["examination_prescription"],
    "is_active": true,
    "created_at": "2026-05-13T10:00:00Z"
  },
  "meta": {
    "request_id": "req_123462"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| USERNAME_ALREADY_EXISTS | 409 | Username sudah ada |
| EMAIL_ALREADY_EXISTS | 409 | Email sudah ada |
| INVALID_ROLE | 400 | Role tidak dikenali |

---

## 6. GET /users
**Get list user (admin only).**

### Query Parameters
- `page` (int): halaman (default 1)
- `per_page` (int): item per halaman (default 20)
- `role` (string): filter by role
- `is_active` (boolean): filter by aktif/tidak

### Response Success (200)
```json
{
  "success": true,
  "data": [
    {
      "id": "usr_001",
      "username": "kasir001",
      "email": "kasir@hospital.local",
      "full_name": "Budi Kasir",
      "role": "kasir",
      "is_active": true,
      "last_login_at": "2026-05-13T09:30:00Z"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 45,
    "total_pages": 3,
    "request_id": "req_123463"
  }
}
```

---

## 7. PUT /users/{user_id}
**Update user (admin only).**

### Request
```json
{
  "email": "dokter001_new@hospital.local",
  "full_name": "Dr. Ahmad Muttaqin",
  "is_active": true,
  "service_access": ["examination_prescription", "registration_queue"]
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "User berhasil diupdate",
  "data": {
    "id": "usr_002",
    "username": "dokter001",
    "email": "dokter001_new@hospital.local",
    "full_name": "Dr. Ahmad Muttaqin",
    "role": "dokter",
    "is_active": true,
    "service_access": ["examination_prescription", "registration_queue"],
    "updated_at": "2026-05-13T10:05:00Z"
  },
  "meta": {
    "request_id": "req_123464"
  }
}
```

---

## 8. PUT /users/{user_id}/change-password
**Change password (user sendiri atau admin).**

### Request
```json
{
  "old_password": "password123",
  "new_password": "newpassword456"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Password berhasil diubah",
  "data": null,
  "meta": {
    "request_id": "req_123465"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| INVALID_OLD_PASSWORD | 400 | Password lama tidak cocok |
| PASSWORD_SAME_AS_OLD | 400 | Password baru sama dengan lama |
| WEAK_PASSWORD | 400 | Password terlalu lemah |

---

## Standard Headers (All Requests)
```
Content-Type: application/json
Accept: application/json
X-Request-ID: {unique_request_id}
```

---

## JWT Token Structure
```
Header:
{
  "alg": "HS256",
  "typ": "JWT"
}

Payload:
{
  "user_id": "usr_001",
  "username": "kasir001",
  "role": "kasir",
  "email": "kasir@hospital.local",
  "service_access": ["billing_pharmacy"],
  "iat": 1684153200,
  "exp": 1684156800,
  "iss": "auth-center",
  "sub": "usr_001"
}
```

---

## Rate Limiting
- Login endpoint: 5 requests per 15 minutes per IP
- Other endpoints: 100 requests per minute per user

## Security
- All endpoints use HTTPS
- Password stored with Argon2
- Token expires in 1 hour
- Login failures > 5x → Account locked 30 minutes
