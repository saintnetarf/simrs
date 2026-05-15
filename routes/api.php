<?php

use Illuminate\Support\Facades\Route;
use OpenApi\Attributes as OA;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * ============================================================================
 * SIMRS MICROSERVICE API - SCRAMBLE DOCUMENTATION
 * ============================================================================
 *
 * Dokumentasi API untuk Sistem Informasi Rumah Sakit (SIMRS) Microservice
 * 4 Service terintegrasi: Auth Center, Registration & Queue, Examination & Prescription, Billing & Pharmacy
 *
 * Base URL: http://localhost:8000/api
 * Authentication: Bearer Token (JWT dari Auth Center)
 * ============================================================================
 */

// ============================================================================
// AUTH CENTER ENDPOINTS (Service Kelompok 1)
// ============================================================================

#[OA\Post(
    path: '/auth/login',
    tags: ['Auth Center'],
    summary: 'Login user dan dapatkan access token',
    description: 'Endpoint publik untuk login. User dari semua service bisa login di sini.',
    requestBody: new OA\RequestBody(
        required: true,
        description: 'Kredensial login',
        content: new OA\JsonContent(
            required: ['username', 'password', 'client_service'],
            properties: [
                new OA\Property(property: 'username', type: 'string', example: 'kasir001'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                new OA\Property(property: 'client_service', type: 'string', example: 'billing_pharmacy', description: 'Nama service yang login'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Login berhasil',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Login berhasil'),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'access_token', type: 'string', example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                        new OA\Property(property: 'user', properties: [
                            new OA\Property(property: 'id', type: 'string', example: 'usr_001'),
                            new OA\Property(property: 'username', type: 'string', example: 'kasir001'),
                            new OA\Property(property: 'full_name', type: 'string', example: 'Budi Kasir'),
                            new OA\Property(property: 'role', type: 'string', example: 'kasir', enum: ['admin', 'perawat', 'dokter', 'kasir', 'admin_apotik', 'pasien']),
                        ])
                    ])
                ]
            )
        ),
        new OA\Response(
            response: 401,
            description: 'Kredensial tidak valid'
        ),
    ]
)]
Route::post('/auth/login', function () {
    // Login endpoint implementation
});

#[OA\Post(
    path: '/auth/introspect',
    tags: ['Auth Center'],
    summary: 'Verifikasi dan ambil info token',
    description: 'Endpoint untuk verifikasi token JWT. Digunakan oleh service lain untuk validasi token.',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['token'],
            properties: [
                new OA\Property(property: 'token', type: 'string', description: 'JWT token untuk diverifikasi'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Token valid',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean'),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'active', type: 'boolean'),
                        new OA\Property(property: 'user_id', type: 'string'),
                        new OA\Property(property: 'role', type: 'string'),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/auth/introspect', function () {
    // Introspect endpoint implementation
});

#[OA\Post(
    path: '/auth/logout',
    tags: ['Auth Center'],
    summary: 'Logout user',
    description: 'Invalidate token dan logout user dari sistem.',
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(response: 200, description: 'Logout berhasil'),
    ]
)]
Route::post('/auth/logout', function () {
    // Logout endpoint implementation
})->middleware('auth:sanctum');

#[OA\Get(
    path: '/auth/refresh-token',
    tags: ['Auth Center'],
    summary: 'Refresh access token',
    description: 'Dapatkan access token baru menggunakan token yang masih berlaku.',
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Token berhasil diperbarui',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean'),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'access_token', type: 'string'),
                        new OA\Property(property: 'expires_in', type: 'integer'),
                    ])
                ]
            )
        ),
    ]
)]
Route::get('/auth/refresh-token', function () {
    // Refresh token endpoint
})->middleware('auth:sanctum');

// ============================================================================
// REGISTRATION & QUEUE ENDPOINTS (Service Kelompok 2)
// ============================================================================

#[OA\Post(
    path: '/registrations',
    tags: ['Registration & Queue'],
    summary: 'Daftar pasien online',
    description: 'Endpoint publik untuk pasien mendaftar online sebelum datang ke poli.',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['full_name', 'phone', 'visit_date', 'poli_preference'],
            properties: [
                new OA\Property(property: 'full_name', type: 'string', example: 'Budi Pasien'),
                new OA\Property(property: 'phone', type: 'string', example: '08123456789'),
                new OA\Property(property: 'email', type: 'string', example: 'budi@email.com'),
                new OA\Property(property: 'visit_date', type: 'string', format: 'date', example: '2026-05-20'),
                new OA\Property(property: 'poli_preference', type: 'string', example: 'poli_umum'),
                new OA\Property(property: 'complaint', type: 'string', example: 'Sakit kepala dan demam'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Pendaftaran berhasil dibuat',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'registration_id', type: 'string', example: 'reg_2026051300001'),
                        new OA\Property(property: 'status', type: 'string', example: 'ONLINE_REGISTERED'),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/registrations', function () {
    // Create registration endpoint
});

#[OA\Post(
    path: '/registrations/{id}/validate',
    tags: ['Registration & Queue'],
    summary: 'Validasi pendaftaran pasien',
    description: 'Admin/Perawat validasi data pasien dan assign nomor antrian.',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['poli_final', 'examination_type', 'validator_id'],
            properties: [
                new OA\Property(property: 'poli_final', type: 'string', example: 'poli_umum'),
                new OA\Property(property: 'examination_type', type: 'string', example: 'pemeriksaan_umum'),
                new OA\Property(property: 'validator_id', type: 'string', example: 'emp_0001'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Validasi berhasil',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'VALIDATED'),
                        new OA\Property(property: 'queue_number', type: 'string', example: '001'),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/registrations/{id}/validate', function () {
    // Validate registration endpoint
})->middleware(['auth:sanctum']);

#[OA\Get(
    path: '/registrations/{id}',
    tags: ['Registration & Queue'],
    summary: 'Get detail pendaftaran',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Detail pendaftaran',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean'),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'registration_id', type: 'string'),
                        new OA\Property(property: 'patient_name', type: 'string'),
                        new OA\Property(property: 'status', type: 'string'),
                    ])
                ]
            )
        ),
    ]
)]
Route::get('/registrations/{id}', function () {
    // Get registration endpoint
})->middleware(['auth:sanctum']);

// ============================================================================
// EXAMINATION & PRESCRIPTION ENDPOINTS (Service Kelompok 3)
// ============================================================================

#[OA\Post(
    path: '/examinations',
    tags: ['Examination & Prescription'],
    summary: 'Buat catatan pemeriksaan',
    description: 'Dokter membuat catatan hasil pemeriksaan pasien.',
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['registration_id', 'diagnosis', 'doctor_id'],
            properties: [
                new OA\Property(property: 'registration_id', type: 'string', example: 'reg_001'),
                new OA\Property(property: 'complaint', type: 'string', example: 'Sakit kepala'),
                new OA\Property(property: 'diagnosis', type: 'string', example: 'Demam dan sakit kepala'),
                new OA\Property(property: 'diagnosis_icd10', type: 'string', example: 'R51.9'),
                new OA\Property(property: 'doctor_id', type: 'string', example: 'emp_doc_001'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Pemeriksaan berhasil dibuat',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'examination_id', type: 'string', example: 'exam_001'),
                        new OA\Property(property: 'status', type: 'string', example: 'EXAMINED'),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/examinations', function () {
    // Create examination endpoint
})->middleware(['auth:sanctum']);

#[OA\Post(
    path: '/prescriptions',
    tags: ['Examination & Prescription'],
    summary: 'Buat resep obat',
    description: 'Dokter membuat resep obat untuk pasien berdasarkan hasil pemeriksaan.',
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['examination_id', 'items'],
            properties: [
                new OA\Property(property: 'examination_id', type: 'string', example: 'exam_001'),
                new OA\Property(property: 'items', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'medicine_code', type: 'string', example: 'med_001'),
                        new OA\Property(property: 'qty', type: 'integer', example: 10),
                        new OA\Property(property: 'dosage', type: 'string', example: '1-2 tablet, 3x sehari'),
                    ]
                )),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Resep berhasil dibuat',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'prescription_id', type: 'string', example: 'pres_001'),
                        new OA\Property(property: 'total_items', type: 'integer', example: 2),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/prescriptions', function () {
    // Create prescription endpoint
})->middleware(['auth:sanctum']);

// ============================================================================
// BILLING & PHARMACY ENDPOINTS (Service Kelompok 4)
// ============================================================================

#[OA\Post(
    path: '/billings',
    tags: ['Billing & Pharmacy'],
    summary: 'Buat invoice pasien',
    description: 'Kasir membuat invoice untuk pasien.',
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['registration_id', 'items'],
            properties: [
                new OA\Property(property: 'registration_id', type: 'string', example: 'reg_001'),
                new OA\Property(property: 'items', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'description', type: 'string', example: 'Konsultasi Dokter'),
                        new OA\Property(property: 'amount', type: 'number', format: 'decimal', example: 150000),
                    ]
                )),
                new OA\Property(property: 'medicine_cost', type: 'number', format: 'decimal', example: 95000),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Invoice berhasil dibuat',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'billing_id', type: 'string', example: 'bill_001'),
                        new OA\Property(property: 'grand_total', type: 'number', format: 'decimal', example: 495000),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/billings', function () {
    // Create billing endpoint
})->middleware(['auth:sanctum']);

#[OA\Post(
    path: '/payments',
    tags: ['Billing & Pharmacy'],
    summary: 'Catat pembayaran pasien',
    description: 'Kasir mencatat pembayaran yang diterima dari pasien.',
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['billing_id', 'amount_paid', 'payment_method'],
            properties: [
                new OA\Property(property: 'billing_id', type: 'string', example: 'bill_001'),
                new OA\Property(property: 'amount_paid', type: 'number', format: 'decimal', example: 495000),
                new OA\Property(property: 'payment_method', type: 'string', enum: ['cash', 'debit_card', 'credit_card', 'bpjs', 'bank_transfer']),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Pembayaran berhasil dicatat',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'payment_id', type: 'string', example: 'pay_001'),
                        new OA\Property(property: 'receipt_number', type: 'string', example: 'REC-2026-05-20-001'),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/payments', function () {
    // Create payment endpoint
})->middleware(['auth:sanctum']);

#[OA\Post(
    path: '/pharmacy/queues',
    tags: ['Billing & Pharmacy'],
    summary: 'Buat antrian pengambilan obat',
    description: 'Admin apotik membuat antrian pengambilan obat dari resep pasien.',
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['prescription_id', 'registration_id'],
            properties: [
                new OA\Property(property: 'prescription_id', type: 'string', example: 'pres_001'),
                new OA\Property(property: 'registration_id', type: 'string', example: 'reg_001'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Antrian apotik berhasil dibuat',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'pharmacy_queue_id', type: 'string', example: 'pq_001'),
                        new OA\Property(property: 'queue_number', type: 'string', example: 'AP-001'),
                    ])
                ]
            )
        ),
    ]
)]
Route::post('/pharmacy/queues', function () {
    // Create pharmacy queue endpoint
})->middleware(['auth:sanctum']);

#[OA\Put(
    path: '/pharmacy/queues/{id}/dispense',
    tags: ['Billing & Pharmacy'],
    summary: 'Berikan obat kepada pasien',
    description: 'Admin apotik memberikan obat kepada pasien sesuai dengan resep.',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['pharmacy_staff_id', 'dispensed_date'],
            properties: [
                new OA\Property(property: 'pharmacy_staff_id', type: 'string', example: 'emp_apotik_001'),
                new OA\Property(property: 'dispensed_date', type: 'string', format: 'date', example: '2026-05-20'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Obat berhasil diberikan',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'MEDICINE_DISPENSED'),
                    ])
                ]
            )
        ),
    ]
)]
Route::put('/pharmacy/queues/{id}/dispense', function () {
    // Dispense medicine endpoint
})->middleware(['auth:sanctum']);

// ============================================================================
// SECURITY SCHEME
// ============================================================================

#[OA\SecurityScheme(
    type: 'http',
    description: 'Bearer token untuk autentikasi. Dapatkan token dari /auth/login',
    name: 'Authorization',
    in: 'header',
    bearerFormat: 'JWT',
    scheme: 'bearer',
)]
class BearerAuth {}
