# SIMRS Implementation Checklist

Status: **Documentation Complete ✅** → **Implementation Phase**

Panduan lengkap untuk mengimplementasikan API endpoints sesuai dokumentasi Scramble.

---

## 🎯 Phase 1: Database Setup

### ✅ Create Migrations from Schema Files

**For each service, convert SQL schema to Laravel migrations:**

#### Auth Center (7 tables)
```bash
# Run this for each table from database/schema/auth_center_schema.sql
php artisan make:migration create_users_table
php artisan make:migration create_user_service_access_table
php artisan make:migration create_tokens_table
php artisan make:migration create_login_logs_table
php artisan make:migration create_api_access_logs_table
php artisan make:migration create_role_permissions_table
php artisan make:migration create_user_sessions_table

# Run migrations
php artisan migrate
```

#### Registration & Queue (6 tables)
```bash
php artisan make:migration create_patients_table
php artisan make:migration create_registrations_table
php artisan make:migration create_polis_table
php artisan make:migration create_examination_types_table
php artisan make:migration create_daily_queues_table
php artisan make:migration create_registration_status_histories_table
```

#### Examination & Prescription (8 tables)
```bash
php artisan make:migration create_examinations_table
php artisan make:migration create_doctors_table
php artisan make:migration create_prescriptions_table
php artisan make:migration create_prescription_items_table
php artisan make:migration create_medicines_table
php artisan make:migration create_diagnoses_table
php artisan make:migration create_examination_histories_table
php artisan make:migration create_prescription_histories_table
```

#### Billing & Pharmacy (11 tables)
```bash
php artisan make:migration create_billings_table
php artisan make:migration create_billing_items_table
php artisan make:migration create_payments_table
php artisan make:migration create_refunds_table
php artisan make:migration create_pharmacy_queues_table
php artisan make:migration create_pharmacy_dispensing_items_table
php artisan make:migration create_pharmacy_inventory_table
php artisan make:migration create_pharmacy_inventory_histories_table
php artisan make:migration create_pharmacy_schedules_table
php artisan make:migration create_service_fees_table
```

---

## 🎯 Phase 2: Create Models

### Auth Center Service

```bash
php artisan make:model User
php artisan make:model UserServiceAccess
php artisan make:model Token
php artisan make:model LoginLog
php artisan make:model ApiAccessLog
php artisan make:model RolePermission
php artisan make:model UserSession
```

**Edit Models:**
- Add fillable, casts, relationships
- Add custom methods (isAdmin(), canAccessService(), etc.)
- Setup model factories for testing

### Registration & Queue Service

```bash
php artisan make:model Patient
php artisan make:model Registration
php artisan make:model Poli
php artisan make:model ExaminationType
php artisan make:model DailyQueue
php artisan make:model RegistrationStatusHistory
```

### Examination & Prescription Service

```bash
php artisan make:model Examination
php artisan make:model Doctor
php artisan make:model Prescription
php artisan make:model PrescriptionItem
php artisan make:model Medicine
php artisan make:model Diagnosis
php artisan make:model ExaminationHistory
php artisan make:model PrescriptionHistory
```

### Billing & Pharmacy Service

```bash
php artisan make:model Billing
php artisan make:model BillingItem
php artisan make:model Payment
php artisan make:model Refund
php artisan make:model PharmacyQueue
php artisan make:model PharmacyDispensingItem
php artisan make:model PharmacyInventory
php artisan make:model PharmacyInventoryHistory
php artisan make:model PharmacySchedule
php artisan make:model ServiceFee
```

---

## 🎯 Phase 3: Create Controllers

### Priority Order

#### 1. Auth Center Service (Critical - others depend on this)

```bash
php artisan make:controller AuthController
```

**Implement endpoints:**
- [x] POST /auth/login → AuthController@login
- [x] POST /auth/introspect → AuthController@introspect
- [x] POST /auth/logout → AuthController@logout
- [x] GET /auth/refresh-token → AuthController@refreshToken
- [x] POST /users → AuthController@createUser
- [x] GET /users → AuthController@listUsers
- [x] PUT /users/{id} → AuthController@updateUser
- [x] PUT /users/{id}/change-password → AuthController@changePassword

#### 2. Registration & Queue Service

```bash
php artisan make:controller RegistrationController
php artisan make:controller QueueController
```

**Implement endpoints:**
- POST /registrations → RegistrationController@store
- POST /registrations/{id}/validate → RegistrationController@validate
- GET /registrations/{id} → RegistrationController@show
- GET /queues/poli → QueueController@listQueues
- PUT /queues/poli/{number}/call → QueueController@callPatient
- GET /registrations/patient/{id} → RegistrationController@getPatientHistory
- PUT /registrations/{id}/status → RegistrationController@updateStatus

#### 3. Examination & Prescription Service

```bash
php artisan make:controller ExaminationController
php artisan make:controller PrescriptionController
```

**Implement endpoints:**
- POST /examinations → ExaminationController@store
- GET /examinations/{id} → ExaminationController@show
- POST /prescriptions → PrescriptionController@store
- GET /prescriptions/{id} → PrescriptionController@show
- PUT /prescriptions/{id} → PrescriptionController@update
- GET /examinations/registration/{id} → ExaminationController@getByRegistration
- POST /examinations/{id}/finalize → ExaminationController@finalize

#### 4. Billing & Pharmacy Service

```bash
php artisan make:controller BillingController
php artisan make:controller PaymentController
php artisan make:controller PharmacyController
```

**Implement endpoints:**
- POST /billings → BillingController@store
- GET /billings/{id} → BillingController@show
- POST /payments → PaymentController@store
- GET /payments/{id} → PaymentController@show
- PUT /billings/{id}/discount → BillingController@applyDiscount
- POST /pharmacy/queues → PharmacyController@createQueue
- GET /pharmacy/queues → PharmacyController@listQueues
- PUT /pharmacy/queues/{id}/dispense → PharmacyController@dispenseMedicine
- GET /pharmacy/queues/{id} → PharmacyController@showQueue
- GET /billings/registration/{id} → BillingController@getByRegistration

---

## 🎯 Phase 4: Create Services & Repositories

### Service Classes (Business Logic)

```bash
# Auth Center
php artisan make:service AuthService
php artisan make:service TokenService

# Registration
php artisan make:service RegistrationService
php artisan make:service QueueService

# Examination
php artisan make:service ExaminationService
php artisan make:service PrescriptionService

# Billing & Pharmacy
php artisan make:service BillingService
php artisan make:service PaymentService
php artisan make:service PharmacyService
```

### Repository Pattern (Optional but Recommended)

```bash
php artisan make:repository UserRepository
php artisan make:repository RegistrationRepository
php artisan make:repository ExaminationRepository
php artisan make:repository BillingRepository
```

---

## 🎯 Phase 5: Create Form Requests & Validation

### Auth Center

```bash
php artisan make:request LoginRequest
php artisan make:request CreateUserRequest
php artisan make:request UpdateUserRequest
php artisan make:request ChangePasswordRequest
```

### Registration & Queue

```bash
php artisan make:request StoreRegistrationRequest
php artisan make:request ValidateRegistrationRequest
php artisan make:request CallPatientRequest
php artisan make:request UpdateRegistrationStatusRequest
```

### Examination & Prescription

```bash
php artisan make:request StoreExaminationRequest
php artisan make:request StorePrescriptionRequest
php artisan make:request UpdatePrescriptionRequest
php artisan make:request FinalizeExaminationRequest
```

### Billing & Pharmacy

```bash
php artisan make:request StoreBillingRequest
php artisan make:request StorePaymentRequest
php artisan make:request ApplyDiscountRequest
php artisan make:request CreatePharmacyQueueRequest
php artisan make:request DispenseMedicineRequest
```

---

## 🎯 Phase 6: Create Events & Listeners

```bash
# Auth Center
php artisan make:event UserLoggedIn
php artisan make:event UserLoggedOut
php artisan make:listener SendLoginNotification

# Registration
php artisan make:event PatientRegistered
php artisan make:event RegistrationValidated
php artisan make:listener SendRegistrationConfirmation

# Examination
php artisan make:event ExaminationCompleted
php artisan make:event PrescriptionCreated

# Billing
php artisan make:event BillingCreated
php artisan make:event PaymentProcessed
php artisan make:listener UpdatePharmacyQueue
```

---

## 🎯 Phase 7: Create Seeders

```bash
php artisan make:seeder UserSeeder
php artisan make:seeder PoliSeeder
php artisan make:seeder MedicineSeeder
php artisan make:seeder ServiceFeeSeeder
php artisan make:seeder DatabaseSeeder
```

**In DatabaseSeeder:**
```php
public function run(): void
{
    $this->call([
        UserSeeder::class,
        PoliSeeder::class,
        MedicineSeeder::class,
        ServiceFeeSeeder::class,
    ]);
}
```

---

## 🎯 Phase 8: Implement Middleware

Already completed:
- ✅ [VerifyAuthCenterToken.php](../app/Http/Middleware/VerifyAuthCenterToken.php)
- ✅ [VerifyUserRole.php](../app/Http/Middleware/VerifyUserRole.php)
- ✅ [LogApiRequest.php](../app/Http/Middleware/LogApiRequest.php)

**Register in bootstrap/app.php:**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->api([
        \App\Http\Middleware\LogApiRequest::class,
        \App\Http\Middleware\VerifyAuthCenterToken::class,
    ]);
})
```

---

## 🎯 Phase 9: Testing

### Unit Tests

```bash
php artisan make:test Unit/AuthServiceTest
php artisan make:test Unit/RegistrationServiceTest
php artisan make:test Unit/BillingServiceTest
```

### Feature Tests

```bash
php artisan make:test Feature/AuthControllerTest
php artisan make:test Feature/RegistrationControllerTest
php artisan make:test Feature/BillingControllerTest
```

### Run Tests

```bash
# All tests
php artisan test

# Specific file
php artisan test tests/Feature/AuthControllerTest.php

# With coverage
php artisan test --coverage
```

---

## 🎯 Phase 10: Integration & End-to-End Testing

### Manual Testing

1. **Test Auth Center**
   - Login endpoint
   - Token validation
   - Role checking

2. **Test Registration Flow**
   - Create registration
   - Validate & assign queue
   - Update status

3. **Test Examination Flow**
   - Create examination
   - Add prescription
   - Finalize

4. **Test Billing Flow**
   - Create billing
   - Process payment
   - Track pharmacy queue

### Postman/CURL Testing

Use Postman collection generated from OpenAPI:

```bash
# Import from Scramble
https://localhost:8000/api.json
```

---

## 📋 Task Breakdown by Team

### Team 1 (Auth Center - Kelompok 1)
- [ ] Create & migrate database tables
- [ ] Create models
- [ ] Create AuthController
- [ ] Implement JWT token generation & validation
- [ ] Create seeders for test users
- [ ] Write tests
- [ ] Document any custom logic

### Team 2 (Registration & Queue - Kelompok 2)
- [ ] Create & migrate database tables
- [ ] Create models
- [ ] Create RegistrationController & QueueController
- [ ] Implement queue management logic
- [ ] Create seeders for polis & examination types
- [ ] Call Auth Center for token validation
- [ ] Write tests
- [ ] Test integration with Team 3 & 4

### Team 3 (Examination & Prescription - Kelompok 3)
- [ ] Create & migrate database tables
- [ ] Create models
- [ ] Create ExaminationController & PrescriptionController
- [ ] Implement examination & prescription logic
- [ ] Create medicine & diagnosis seeders
- [ ] Call Auth Center for token validation
- [ ] Call Team 2 API for registration updates
- [ ] Write tests
- [ ] Test integration with Team 2 & 4

### Team 4 (Billing & Pharmacy - Kelompok 4)
- [ ] Create & migrate database tables
- [ ] Create models
- [ ] Create BillingController & PharmacyController
- [ ] Implement billing & payment logic
- [ ] Create service fee & inventory seeders
- [ ] Call Auth Center for token validation
- [ ] Call Team 2 API for registration updates
- [ ] Call Team 3 API for prescription retrieval
- [ ] Write tests
- [ ] Test integration with Team 2 & 3

---

## ⏱️ Estimated Timeline

- **Phase 1-2: Database & Models** → 2-3 days
- **Phase 3: Controllers** → 3-4 days
- **Phase 4-5: Services & Validation** → 2-3 days
- **Phase 6-7: Events & Seeders** → 1-2 days
- **Phase 8-9: Middleware & Testing** → 2-3 days
- **Phase 10: Integration Testing** → 2-3 days

**Total: ~2-3 weeks** (with parallel team work)

---

## 📚 Next Steps After Implementation

1. ✅ Deploy to staging
2. ✅ Performance testing & optimization
3. ✅ Security audit & penetration testing
4. ✅ Production deployment
5. ✅ Monitoring & alerting setup
6. ✅ Team handoff & documentation

---

## 📞 Support Resources

- 📖 [Scramble Setup Guide](./SCRAMBLE_SETUP.md)
- 📖 [Quick Reference](./SCRAMBLE_QUICK_REFERENCE.md)
- 📖 [API Documentation](./API_AUTH_CENTER.md)
- 📖 [Patient Journey Flow](./SEQUENCE_FLOW_PATIENT_JOURNEY.md)
- 📖 [Middleware Guide](./MIDDLEWARE_SETUP_GUIDE.md)

---

**Last Updated:** May 13, 2026
**Project:** SIMRS Microservice
**Status:** Ready for Implementation
