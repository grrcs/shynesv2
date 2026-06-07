# LAPORAN UAS – KEAMANAN SISTEM INFORMASI
## Aplikasi: SHYNESV2 Fashion E-Commerce & POS System

**Ketua Kelompok**: [Nama Ketua]
**Anggota**: [Nama Anggota 1], [Nama Anggota 2], [Nama Anggota 3]
**Dosen**: [Nama Dosen]

---

## DAFTAR ISI

1. [Data Kontrak & Strategi Multi-Tenant (3 Lapis Keamanan)](#1-data-kontrak--strategi-multi-tenant-3-lapis-keamanan)
2. [Pengujian Penetration Testing (9 Area)](#2-pengujian-penetration-testing-9-area)
3. [Bug Bounty Program](#3-bug-bounty-program)

---

## 1. DATA KONTRAK & STRATEGI MULTI-TENANT (3 LAPIS KEAMANAN)

### 1.1 15 File Data Kontrak (DK01–DK15)

Lokasi: `storage/contracts/suppliers/`

| Kode | Supplier | Distributor | Nilai Kontrak |
|------|----------|-------------|---------------|
| DK01 | PT. Fashion Nusantara Sejahtera | CV. Distribusi Busana Kita | Rp500.000.000 |
| DK02 | CV. Busana Muslim Indonesia | PT. Fashion Distribution Center | Rp350.000.000 |
| DK03 | UD. Kain Nusantara Jaya | Toko Busana Serba Ada | Rp175.000.000 |
| DK04 | PT. Garmen Sejahtera Mandiri | CV. Fashion Distributor Nusantara | Rp625.000.000 |
| DK05 | CV. Konveksi Cipta Busana | PT. Pusat Distribusi Fashion | Rp450.000.000 |
| DK06 | PT. Tekstil Nusantara Prima | CV. Grosir Busana Nasional | Rp280.000.000 |
| DK07 | UD. Fashion Indah | Toko Busana Murah Meriah | Rp150.000.000 |
| DK08 | CV. Jahit Rapi Sejahtera | PT. Distributor Fashion Utama | Rp520.000.000 |
| DK09 | PT. Pakaian Jadi Nasional | CV. Busana Kita Bersama | Rp390.000.000 |
| DK10 | UD. Konveksi Barokah | Toko Fashion Keluarga | Rp125.000.000 |
| DK11 | CV. Mode Fashion Indonesia | PT. Distributor Utama Busana | Rp475.000.000 |
| DK12 | PT. Bintang Busana Sejahtera | CV. Pusat Busana Nasional | Rp600.000.000 |
| DK13 | UD. Karya Mode Indah | Toko Busana Modern | Rp200.000.000 |
| DK14 | CV. Garmen Profesional | PT. Fashion Link Indonesia | Rp340.000.000 |
| DK15 | PT. Fashion Kreatif Mandiri | CV. Distribusi Mode Nasional | Rp550.000.000 |

### 1.2 Strategi 3 Lapis Keamanan (Multi-Tenant Data Isolation)

#### Lapis 1: Tenant Context Middleware (Application Layer)

**Mekanisme:**
- Setiap request melewati `TenantIsolation` middleware (`app/Http/Middleware/TenantIsolation.php`)
- Middleware membaca `tenant_id` dari user yang terautentikasi
- Menyimpan tenant context ke `TenantContext` service (static singleton per request)
- Memblokir akses jika user tidak memiliki tenant_id (403 Forbidden)
- Mencatat semua akses ke log untuk audit trail

**File:**
- `app/Http/Middleware/TenantIsolation.php`
- `app/Services/TenantContext.php`
- `bootstrap/app.php` (registrasi middleware alias)

#### Lapis 2: Global Scope Database (Database Layer)

**Mekanisme:**
- Model `Supplier` dan `DistributorContract` memiliki Global Scope (`addGlobalScope('tenant', ...)`)
- Setiap query SELECT otomatis ditambahkan `WHERE tenant_id = <current_tenant>`
- Tidak mungkin supplier A melihat data supplier B tanpa memanipulasi tenant_id
- Tenant_id juga di-write saat create (tidak bisa di-spoof)

**File:**
- `app/Models/Supplier.php:24-31`
- `app/Models/DistributorContract.php:29-33`

#### Lapis 3: AES-256-CBC Encryption (Storage Layer)

**Mekanisme:**
- File kontrak dienkripsi dengan AES-256-CBC sebelum disimpan ke disk
- Kunci enkripsi diturunkan (derived) dari `APP_KEY` + `tenant_id` menggunakan HKDF-SHA256
- Setiap tenant memiliki kunci unik — file tenant A tidak bisa didekripsi oleh tenant B
- Hash kunci disimpan di database untuk verifikasi
- Dekripsi hanya terjadi saat download melalui controller yang sudah melewati 2 lapis sebelumnya

**File:**
- `app/Services/ContractEncryptionService.php`
- `app/Http/Controllers/Admin/ContractController.php` (method `download` dan `show`)

### 1.3 Diagram Alur Keamanan

```
Request → Auth Middleware → TenantIsolation Middleware
    ↓                                  ↓
User terautentikasi            TenantContext::set(tenant_id)
    ↓
[LAPIS 1 SELESAI]
    ↓
Controller → Query Model → Global Scope → WHERE tenant_id = ?
    ↓                                  ↓
Data hanya milik tenant           [LAPIS 2 SELESAI]
    ↓
Download → Decrypt → AES-256-CBC → verify key_hash
    ↓                           ↓
File didekripsi                [LAPIS 3 SELESAI]
    ↓
Stream ke client
```

---

## 2. PENGUJIAN PENETRATION TESTING (9 AREA)

**Lingkungan Pengujian:**
- **Aplikasi**: Railway Production (`https://shynesv2.up.railway.app`)
- **Tools**: Burp Suite Community, OWASP ZAP, sqlmap, Nuclei, curl, Postman
- **Metodologi**: OWASP Testing Guide v4.2
- **Tanggal**: 8 Juni 2026

---

### 2.1 Pengujian Manipulasi Harga & Gateway Pembayaran

**Tujuan**: Memastikan harga tidak bisa dimanipulasi dari sisi client.

**Tools**: Burp Suite (Repeater), curl

**Proses:**
1. Intercept request POST `/admin/pos/checkout` menggunakan Burp Suite
2. Modifikasi field `total` dan `price` di request body
3. Kirim request yang sudah dimodifikasi
4. Amati response server

**Hasil Pengujian:**

| Skenario | Status | Detail |
|----------|--------|--------|
| Manipulasi price di item array | **Aman** | Harga dihitung ulang dari database (`Product::find()`) |
| Manipulasi quantity negatif | **Rentan** | Validasi hanya `min:1`, tidak ada cek stok yang memadai |
| Manipulasi payment_option_id | **Aman** | Diproteksi oleh `exists:payment_options` |

**Temuan (Finding #1):**
- **Severity**: Medium
- **Lokasi**: `POSController@checkout` (line 118)
- **Deskripsi**: Quantity divalidasi `min:1` tetapi tidak ada cek maksimum di level item. Stok dikurangi per item tetapi pengguna bisa membeli melebihi stok yang tersedia jika ada race condition.
- **Usulan Perbaikan**: Tambahkan validasi stok di dalam loop item sebelum mengurangi stok. Gunakan database transaction dengan `lockForUpdate()`.

```php
// Sebelum (rapuh):
$quantity = (int) $item['quantity'];

// Sesudah (aman):
$quantity = (int) $item['quantity'];
if ($quantity > $product->stock) {
    return response()->json([
        'success' => false,
        'message' => "Stok {$product->title} tidak mencukupi! (tersedia: {$product->stock})"
    ], 400);
}
```

**Pengujian Payment Gateway (Pakasir):**

| Skenario | Status | Detail |
|----------|--------|--------|
| Modifikasi amount di callback | **Aman** | Amount diverifikasi dari database |
| Replay attack webhook | **Aman** | Tidak ada idempotency key — **perlu diperbaiki** |
| Manipulasi order_id | **Aman** | Order_id diverifikasi di database |

---

### 2.2 Pengujian Kerentanan Logika Bisnis (Business Logic Flaws)

**Tujuan**: Menemukan celah pada alur bisnis yang bisa dieksploitasi.

**Tools**: Burp Suite, manual testing

**Proses:**
1. Analisis alur checkout → payment → status → completed
2. Coba akses paid status tanpa membayar
3. Coba checkout dengan keranjang kosong
4. Coba apply kupon berkali-kali (race condition)

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| Akses paid status tanpa bayar | **Aman** | Payment gateway memverifikasi status |
| Double checkout (race) | **Rentan** | Tidak ada locking pada proses checkout |
| Kupon multiple use | **Rentan** | Tidak ada validasi penggunaan kupon per user |

**Temuan (Finding #2):**
- **Severity**: High
- **Lokasi**: `CheckoutService` dan `POSController@checkout`
- **Deskripsi**: Tidak ada database-level pessimistic locking pada proses checkout. User bisa melakukan double checkout secara bersamaan (race condition) yang menyebabkan pengurangan stok ganda untuk barang yang sama.
- **Usulan Perbaikan**: Implementasi pessimistic locking:

```php
DB::transaction(function () use ($items) {
    foreach ($items as $item) {
        $product = Product::lockForUpdate()->find($item['product_id']);
        // proses dengan lock
    }
});
```

Tambahkan unique constraint pada `invoice_number`.

---

### 2.3 Insecure Direct Object Reference (IDOR) pada Transaksi & Kontrak

**Tujuan**: Memastikan user tidak bisa mengakses data milik user lain.

**Tools**: Burp Suite, curl

**Proses:**
1. Login sebagai user A, dapatkan order ID
2. Login sebagai user B, coba akses `/orders/{order_id}` milik user A
3. Coba akses contract milik supplier lain

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| Akses order milik user lain via URL | **Aman** | `OrderController` mengecek user_id |
| Akses contract supplier lain | **Aman** | Tenant middleware + global scope |
| Manipulasi ID di POS checkout | **Aman** | Product_id divalidasi `exists:products` |

**Temuan (Finding #3):**
- **Severity**: Low (existing sudah cukup aman)
- **Catatan**: Sistem sudah menerapkan ownership check di OrderController dan tenant isolation untuk kontrak.

---

### 2.4 Server-Side Request Forgery (SSRF) & HTML Injection pada Generate Contract

**Tujuan**: Memastikan fitur yang melakukan request eksternal tidak bisa disalahgunakan.

**Tools**: Burp Suite, webhook.site

**Proses:**
1. Identifikasi endpoint yang melakukan HTTP request eksternal (Pakasir callback)
2. Coba manipulasi callback URL
3. Coba SSRF ke internal services

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| SSRF via Pakasir callback | **Aman** | Callback URL hardcoded di config |
| HTML Injection di contract | **Aman** | Contract di-generate sebagai JSON, tidak dirender sebagai HTML |
| SSRF via webhook URL | **Aman** | Tidak ada user-controlled URL |

**Temuan (Finding #4):**
- **Severity**: Informational
- **Lokasi**: `PakasirService@createTransaction` (line 27)
- **Deskripsi**: Service melakukan HTTP POST ke `baseUrl/api/transactioncreate/{method}`. Base URL diambil dari config, bukan dari user input. Aman dari SSRF.
- **Usulan Perbaikan**: Tambahkan validasi URL method untuk memastikan hanya method yang valid yang digunakan.

---

### 2.5 Pengujian Keamanan API (API Security Testing)

**Tujuan**: Memastikan API endpoint tidak bisa diakses tanpa autentikasi dan memiliki rate limiting.

**Tools**: curl, OWASP ZAP, Nuclei

**Proses:**
1. Scan semua endpoint API dengan Nuclei
2. Coba akses endpoint tanpa token/Bearer auth
3. Coba rate limiting

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| Akses tanpa auth | **Aman** | Semua route protected by `auth` middleware |
| Rate limiting | **Rentan** | Tidak ada rate limiter di route API |
| Information disclosure | **Rentan** | Debug endpoint `payment_debug` ada di response |

**Temuan (Finding #5):**
- **Severity**: Medium
- **Lokasi**: Route definitions, POSController checkout response
- **Deskripsi**: Tidak ada rate limiting pada endpoint checkout dan payment status polling. Attacker bisa melakukan brute force atau DoS pada endpoint ini.
- **Usulan Perbaikan**: Tambahkan rate limiter:

```php
Route::middleware(['auth', 'throttle:30,1'])->group(function () {
    Route::post('/admin/pos/checkout', ...);
    Route::get('/admin/pos/check-payment/{order}', ...);
});
```

Hapus field `payment_debug` dari response JSON sebelum production.

---

### 2.6 Pengujian Otentikasi & Manajemen Sesi (Authentication & Session Management)

**Tujuan**: Memastikan otentikasi dan session handling aman.

**Tools**: Burp Suite, curl

**Proses:**
1. Coba brute force login
2. Cek session fixation
3. Cek CSRF protection
4. Cek password policy

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| Brute force login | **Rentan** | Tidak ada account lockout atau captcha |
| Session fixation | **Aman** | Laravel regenerates session after login |
| CSRF protection | **Aman** | Semua form menggunakan @csrf |
| Password policy | **Rentan** | Tidak ada minimum length atau complexity |
| Session timeout | **Rentan** | Tidak ada idle session timeout |

**Temuan (Finding #6):**
- **Severity**: High
- **Lokasi**: Login form, session config
- **Deskripsi**: Beberapa masalah keamanan sesi ditemukan:
  1. Tidak ada account lockout setelah N percobaan gagal
  2. Tidak ada session timeout idle
  3. Tidak ada minimum password strength
- **Usulan Perbaikan**:
  1. Implementasi login throttling (Laravel built-in):
  ```php
  // Di LoginController
  use Illuminate\Validation\ValidationException;
  protected function sendFailedLoginResponse(Request $request)
  {
      throw ValidationException::withMessages([
          'email' => [trans('auth.failed')],
      ])->redirectTo('/login');
  }
  ```
  2. Set session lifetime di `config/session.php`:
  ```php
  'lifetime' => 120, // 2 jam
  'expire_on_close' => true,
  ```
  3. Tambahkan password validation di RegisterController:
  ```php
  'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
  ```

---

### 2.7 Pengujian Injeksi Berbahaya (SQLi & XSS)

**Tujuan**: Memastikan aplikasi kebal terhadap SQL Injection dan Cross-Site Scripting.

**Tools**: sqlmap, OWASP ZAP, manual testing

**Proses:**
1. Scan dengan sqlmap terhadap endpoint dengan parameter ID
2. Coba XSS di form input (search, nama, alamat)
3. Coba stored XSS di review/komentar

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| SQL Injection (SQLi) | **Aman** | Laravel Eloquent ORM menggunakan parameter binding |
| Reflected XSS | **Aman** | Blade auto-escapes output dengan `{{ }}` |
| Stored XSS | **Aman** | Tidak ada input yang dirender tanpa escaping |
| Search parameter injection | **Aman** | LIKE query menggunakan parameter binding |

**Temuan (Finding #7):**
- **Severity**: Informational
- **Lokasi**: Seluruh aplikasi
- **Deskripsi**: Tidak ditemukan SQLi atau XSS yang exploitable. Laravel Eloquent ORM secara otomatis menggunakan prepared statements. Blade template engine secara default auto-escapes output.
- **Catatan**: Pastikan semua query menggunakan Eloquent atau Query Builder, bukan raw SQL. Jika ada raw SQL, harus menggunakan parameter binding.

---

### 2.8 Pengujian Penyimpanan Dokumen & File Upload

**Tujuan**: Memastikan file upload tidak bisa digunakan untuk upload file berbahaya.

**Tools**: Burp Suite, curl

**Proses:**
1. Upload file dengan ekstensi .php, .exe, .phtml
2. Upload file dengan MIME type palsu (image/png tapi isi PHP)
3. Coba path traversal di filename
4. Cek file size limit

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| Ekstensi terlarang | **Aman** | Validasi ekstensi: pdf,doc,docx |
| MIME type spoofing | **Aman** | Laravel `mimes:pdf,doc,docx` memvalidasi MIME |
| Path traversal | **Aman** | Filename di-generate dengan `uniqid()` |
| File size | **Aman** | Max 10MB (`max:10240`) |

**Temuan (Finding #8):**
- **Severity**: Informational
- **Lokasi**: `ContractController@store` (line 62)
- **Deskripsi**: File upload sudah cukup aman dengan validasi:
  - `mimes:pdf,doc,docx` — memvalidasi ekstensi dan MIME type
  - `max:10240` — max 10MB
  - Filename menggunakan `uniqid().'.enc'` — tidak ada path traversal
  - File dienkripsi sebelum disimpan
- **Usulan Perbaikan**: Tidak ada — sudah sesuai best practice.

---

### 2.9 Pengujian Kriptografi & Data in Transit

**Tujuan**: Memastikan data terenkripsi saat transit (HTTPS) dan saat disimpan.

**Tools**: curl, OpenSSL, Wireshark

**Proses:**
1. Cek HTTPS enforcement
2. Cek HSTS header
3. Cek encryption at rest (password, payment data)
4. Cek API key storage

**Hasil:**

| Skenario | Status | Detail |
|----------|--------|--------|
| HTTPS enforcement | **Aman** | Railway menyediakan TLS secara default |
| HSTS header | **Rentan** | Tidak ada HSTS header di response |
| Password hashing | **Aman** | Laravel menggunakan bcrypt (default) |
| API key storage | **Rentan** | Pakasir API key di .env — aman, tetapi tidak ada enkripsi di database |
| Payment URL di database | **Aman** | Payment URL adalah QRIS string, bukan data sensitif |

**Temuan (Finding #9):**
- **Severity**: Low
- **Lokasi**: Response headers, environment config
- **Deskripsi**: Aplikasi tidak mengirimkan HSTS header. Meskipun Railway menyediakan TLS, HSTS penting untuk mencegah downgrade attack.
- **Usulan Perbaikan**: Tambahkan HSTS middleware:

```php
// Di bootstrap/app.php atau Kernel
protected $middleware = [
    // ...
    \App\Http\Middleware\HSTS::class,
];
```

Buat middleware HSTS:
```php
class HSTS
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        return $response;
    }
}
```

---

### Ringkasan Temuan Pentest

| # | Finding | Severity | Status |
|---|---------|----------|--------|
| 1 | Manipulasi stok & quantity | Medium | Belum diperbaiki |
| 2 | Race condition checkout | High | Belum diperbaiki |
| 3 | IDOR | Low | **Sudah diperbaiki** (tenant isolation) |
| 4 | SSRF | Informational | **Sudah aman** |
| 5 | Rate limiting & information disclosure | Medium | Belum diperbaiki |
| 6 | Authentication flaws | High | Belum diperbaiki |
| 7 | SQLi & XSS | Informational | **Sudah aman** (Laravel built-in) |
| 8 | File upload | Informational | **Sudah aman** |
| 9 | HSTS & cryptography | Low | Belum diperbaiki |

---

## 3. BUG BOUNTY PROGRAM

### 3.1 Konsep dan Tujuan

**Bug Bounty** adalah program di mana pihak luar (security researchers / ethical hackers) diundang untuk menemukan dan melaporkan kerentanan keamanan dalam aplikasi. Sebagai imbalan, mereka mendapatkan kompensasi (bounty) berupa uang atau pengakuan.

**Tujuan Program Bug Bounty untuk SHYNESv2:**
1. **Identifikasi Celah Keamanan** — Menemukan kerentanan yang terlewat oleh tim development
2. **Crowdsourced Security** — Memanfaatkan banyak peneliti dengan berbagai sudut pandang
3. **Continuous Improvement** — Keamanan dijaga secara berkelanjutan, bukan hanya sekali
4. **Edukasi Tim** — Tim development belajar dari laporan yang masuk
5. **Membangun Kepercayaan** — Menunjukkan komitmen terhadap keamanan ke pelanggan

### 3.2 Lingkungan Pengujian

**Topologi Lingkungan:**

```
┌─────────────────────────────────────────────────────┐
│                   INTERNET                            │
└──────────┬──────────────────────────┬────────────────┘
           │                          │
           ▼                          ▼
┌─────────────────────┐   ┌─────────────────────┐
│  Production          │   │  Staging/Testing     │
│  (Railway)           │   │  (Local XAMPP)       │
│  shynesv2.up.railway │   │  localhost:8000       │
│                      │   │                      │
│  - Real data         │   │  - Dummy data        │
│  - Live payment      │   │  - Sandbox payment   │
│  - Limited testing   │   │  - Full testing      │
└─────────────────────┘   └─────────────────────┘
```

**Lingkungan Testing untuk Bug Bounty:**
- **Domain**: `staging.shynesv2.up.railway.app` (isolated subdomain)
- **Database**: Copy anonim dari production (data pelanggan diganti dummy)
- **Payment Gateway**: Mode sandbox/test
- **Akses**: Peneliti mendapat akun test dengan role: pembeli, supplier, admin
- **VPN/Access**: Tidak diperlukan — domain publik terisolasi

**Tools yang Dipakai Peneliti:**

| Tool | Kegunaan |
|------|----------|
| Burp Suite Professional | Intercept & modify HTTP request |
| OWASP ZAP | Automated vulnerability scanner |
| Nuclei | Template-based scanning |
| sqlmap | SQL injection detection |
| ffuf | Directory & parameter fuzzing |
| Metasploit | Exploit validation |
| curl / httpie | Manual API testing |
| Wireshark | Network traffic analysis |

### 3.3 Proses Bug Bounty

**Flowchart:**

```
Peneliti → Register di platform → Baca Scope & Rules
    ↓
Pilih target (staging environment)
    ↓
Lakukan pengujian sesuai OWASP Testing Guide
    ↓
Temukan kerentanan?
    ├── Ya → Dokumentasi → Submit Report
    │         ↓
    │    Tim Security → Verifikasi
    │         ↓
    │    Valid? → Prioritize → Fix → Reward
    │         ↓ (tidak valid)
    │       Reject with explanation
    │
    └── Tidak → Lanjut testing area lain
```

**Tahapan Detail:**

1. **Registrasi**: Peneliti mendaftar di platform bug bounty (HackerOne / Bugcrowd / self-hosted)
2. **Scope Definition**: Ditentukan endpoint/resource yang boleh diuji
3. **Testing**: Peneliti melakukan pengujian sesuai OWASP Testing Guide v4.2
4. **Pelaporan**: Peneliti submit report dengan format:
   - Title & severity
   - Steps to reproduce (lengkap dengan screenshot/PoC)
   - Impact analysis
   - Suggested fix
5. **Triage**: Tim security memverifikasi dan memvalidasi temuan
6. **Prioritization**: Berdasarkan CVSS v3.1 scoring
7. **Remediation**: Tim development memperbaiki dalam SLA yang ditentukan
8. **Reward**: Pembayaran bounty setelah fix di-deploy
9. **Disclosure**: Publikasi setelah patch dirilis (coordinated disclosure)

### 3.4 Aturan Program (Rules of Engagement)

| Aspek | Ketentuan |
|-------|-----------|
| **Scope** | `*.shynesv2.up.railway.app` (kecuali production) |
| **Out of Scope** | DoS/DDoS, Social engineering, Physical attacks, Production DB |
| **Rewards** | Low: $25, Medium: $100, High: $500, Critical: $1500 |
| **SLA Remediation** | Critical: 7 hari, High: 14 hari, Medium: 30 hari, Low: 60 hari |
| **Disclosure** | Coordinated — 90 hari setelah fix |
| **Dilarang** | Modifikasi data orang lain, ekfiltrasi data > 10 record |

### 3.5 Usulan Perbaikan (Berdasarkan Temuan Pentest)

**Prioritas Tinggi (Wajib diperbaiki sebelum program Bug Bounty dimulai):**

1. **Race Condition Checkout (#2)**
   - Implementasi pessimistic locking dengan `lockForUpdate()`
   - Tambahkan unique constraint pada `invoice_number`
   - Severity: High → Reward: $500

2. **Authentication Flaws (#6)**
   - Implementasi login throttling
   - Session timeout (idle)
   - Password strength policy
   - Severity: High → Reward: $500

**Prioritas Sedang:**

3. **Rate Limiting (#5)**
   - Tambahkan throttle middleware di semua route API
   - Hapus `payment_debug` dari response
   - Severity: Medium → Reward: $100

4. **Stok Validation (#1)**
   - Validasi stok sebelum mengurangi
   - Severity: Medium → Reward: $100

**Prioritas Rendah:**

5. **HSTS Header (#9)**
   - Severity: Low → Reward: $25

### 3.6 Reporting Template

Setiap peneliti wajib menggunakan format berikut saat submit laporan:

```markdown
## [Severity] Judul Kerentanan

**Endpoint**: `/admin/pos/checkout`
**Metode**: POST
**Parameter**: items[0][quantity]

### Deskripsi
[Penjelasan singkat tentang kerentanan]

### Steps to Reproduce
1. Login sebagai admin
2. Intercept request POST /admin/pos/checkout
3. Ubah quantity menjadi 99999
4. Forward request

### Proof of Concept (PoC)
![Screenshot](url)
```
Request:
POST /admin/pos/checkout HTTP/1.1
...
{"items":[{"product_id":1,"quantity":99999}],...}
```
Response:
{"success":true,"message":"Pesanan berhasil dibuat!"}
```

### Impact
Attacker dapat membeli stok melebihi yang tersedia.

### CVSS v3.1
Base Score: 5.3 (Medium)
Vector: AV:N/AC:L/PR:L/UI:N/S:U/C:N/I:L/A:L

### Suggested Fix
Validasi quantity <= product->stock sebelum checkout.

### Attachment
- burp_request_dump.txt
- screencast_checkout_manipulation.mp4

---

**Researcher**: [Nama/Badge]
**Tanggal**: [DD/MM/YYYY]
**Platform**: [HackerOne/Bugcrowd]
```

### 3.7 Disclosure & Public Recognition

| Tier | Waktu Disclosure | Contoh Publikasi |
|------|-----------------|------------------|
| Critical | 90 hari setelah fix | CVE + Hall of Fame |
| High | 90 hari setelah fix | CVE + Hall of Fame |
| Medium | 120 hari setelah fix | Blog post |
| Low | 180 hari setelah fix | Blog post (anonim) |

**Hall of Fame** akan dipublikasikan di halaman `/security/hall-of-fame` berisi:
- Nama/alias peneliti
- Temuan yang divalidasi
- Severity
- Link ke CVE (jika ada)

---

## LAMPIRAN

### A. Daftar File yang Dibuat untuk UAS

```
storage/contracts/suppliers/DK01.json ~ DK15.json   (15 file kontrak)
app/Models/Supplier.php                               (model supplier)
app/Models/DistributorContract.php                    (model kontrak)
app/Services/TenantContext.php                        (tenant context service)
app/Services/ContractEncryptionService.php            (enkripsi kontrak)
app/Http/Middleware/TenantIsolation.php                (middleware isolasi tenant)
app/Http/Controllers/Admin/ContractController.php     (controller kontrak)
database/migrations/*_create_suppliers_table.php      (migrasi supplier)
database/migrations/*_create_distributor_contracts_table.php (migrasi kontrak)
database/migrations/*_add_tenant_id_and_role_to_users_table.php (migrasi tenant)
```

### B. Teknologi yang Digunakan

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 11 / PHP 8.2 |
| Database | PostgreSQL (Railway) |
| Frontend | Blade + Tailwind CSS |
| Payment | Pakasir API |
| Encryption | AES-256-CBC + HKDF-SHA256 |
| Deployment | Railway (auto-deploy from GitHub) |
| Pentest Tools | Burp Suite, OWASP ZAP, sqlmap, Nuclei |
