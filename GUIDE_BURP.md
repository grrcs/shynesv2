# PANDUAN TESTING BURP SUITE per ORANG

---

## 🧑 ORANG 1: Multi-Tenant (Testing Isolasi Data)

**Tools**: Browser aja (gak perlu Burp)

### Step 1: Cek Supplier A cuma lihat kontraknya sendiri
1. Login sebagai user supplier A (punya kontrak DK01)
2. Buka `/admin/contracts`
3. **Screenshot**: Tampilkan daftar kontrak — pastikan cuma DK01 yang muncul

### Step 2: Coba akses kontrak supplier B
1. Masih login sbg supplier A
2. Buka URL: `/admin/contracts/3`
3. **Screenshot**: Harusnya muncul **403 Forbidden**

### Step 3: Coba download kontrak supplier B
1. Buka URL: `/admin/contracts/3/download`
2. **Screenshot**: Error "Unauthorized access"

---

## 🧑 ORANG 2: Business Logic, IDOR, Auth, SQLi & XSS

### A. IDOR Test (curl)

1. Buka **PowerShell** atau **Command Prompt**
2. Login sebagai user A, catat order_id
3. Jalankan:
```powershell
# Ganti {order_id} dengan punya user lain
curl https://shynesv2.up.railway.app/orders/{order_id} -UseBasicParsing
```
4. **Screenshot**: output-nya (harus redirect ke login atau 403)

### B. Brute Force Test (Burp)

1. **Burp Proxy → Intercept** → klik **Intercept is on** (jadi nyala)
2. Di browser, login dengan password **salah** (apa aja)
3. Di Burp akan muncul request login → **Right click → Send to Intruder**
4. Klik **Positions** → Clear § → pilih password value → Add §
5. Klik **Payloads** → isi list: `123456`, `password`, `admin`, `qwerty`
6. Klik **Start Attack**
7. **Screenshot**: Hasil intruder (banyak request)

### C. SQLi Test (sqlmap)

Buka **Command Prompt**:
```cmd
sqlmap -u "https://shynesv2.up.railway.app/products?category_id=1" --batch
```
**Screenshot**: Output sqlmap (harusnya "not injectable")

### D. XSS Test (Browser)

1. Buka halaman search
2. Ketik: `<script>alert('XSS')</script>`
3. **Screenshot**: Muncul hasil pencarian dengan tulisan itu (bukan popup alert)

### E. Race Condition Test (Burp - manual)

1. Intercept checkout request (POST `/admin/pos/checkout`)
2. **Right click → Send to Repeater** (2x, jadi 2 tab)
3. Di masing-masing tab, klik **Send** cepat-cepat
4. **Screenshot**: Kedua response sukses (ini celahnya)

### F. No Session Timeout Test

1. Login, catat waktu
2. Biarkan browser terbuka 30 menit (gak disentuh)
3. Klik halaman lain
4. **Screenshot**: Halaman masih kebuka (gak redirect ke login)

---

## 🧑 ORANG 3: Payment, SSRF, API, File Upload, Kriptografi

### A. Manipulasi Harga (Burp)

1. **Proxy → Intercept → Intercept is on**
2. Di browser: POS → pilih produk → checkout
3. Di Burp akan muncul request `POST /admin/pos/checkout`
4. **Ubah quantity** dari `1` jadi `99999`
5. Klik **Forward**
6. Lihat response: **"success": true**
7. **Screenshot**: Request (quantity diubah) + Response (sukses)

### B. Webhook Spoofing (curl)

Buka PowerShell:
```powershell
curl -Method POST https://shynesv2.up.railway.app/payment/pakasir/callback -Body '{"status":"completed","order_id":"POS-20260608-XXXXXX","amount":"100000"}' -ContentType "application/json" -UseBasicParsing
```
**Screenshot**: Hasil response

### C. API Rate Limit Test

Buka PowerShell, jalankan:
```powershell
for ($i=0; $i -lt 50; $i++) { curl https://shynesv2.up.railway.app/ -UseBasicParsing }
```
**Screenshot**: Semua sukses (gak ada yg kena 429) — ini celahnya

### D. File Upload Test (Burp)

1. Siapkan file palsu: buat `test.php` isinya `<?php system($_GET['cmd']); ?>`
2. Di browser, buka form upload kontrak
3. **Proxy → Intercept on**
4. Upload file `test.php`
5. Di Burp → ubah `filename="test.php"` → **Forward**
6. **Screenshot**: Error validasi

### E. HSTS Header Test (curl)

```powershell
curl -I https://shynesv2.up.railway.app
```
**Screenshot**: Cari header `strict-transport-security` — kalo gak ada, itu celah

### F. Replay Attack Test (Burp)

1. Burp → **Proxy → HTTP History**
2. Cari request ke `/payment/pakasir/callback`
3. **Right click → Send to Repeater**
4. Klik **Send** 2x cepat
5. **Screenshot**: Kedua response sukses — celah (harusnya yg kedua ditolak)

### G. SSRF Test (Burp)

Cari semua endpoint yang punya parameter URL — screenshot hasilnya (gak ada).

---

## 🧑 ORANG 4: Bug Bounty (Dokumentasi)

**Gak perlu tools testing.** Tugas dokumen:

1. Baca laporan Orang 1, 2, 3
2. Screenshot masing-masing temuan masukin ke laporan Orang 4
3. Buat halaman Hall of Fame:
```
# Security Hall of Fame
Terima kasih kepada:
- [Nama 1] - Race Condition (High)
- [Nama 2] - Brute Force Login (High)
- [Nama 3] - Webhook Spoofing (High)
```
4. **Screenshot halaman** ini

---

## ⚠️ Tips Screenshot Biar Nilai Bagus

| Bagian | Cara |
|--------|------|
| **Request** | Tampilin URL + parameter yang diubah (highlight) |
| **Response** | Tampilin response JSON atau halaman error |
| **Burp** | Tampilin tab Proxy atau Repeater biar kelihatan toolnya |
| **Browser** | Tampilin address bar biar kelihatan URL-nya |

**Pakai Windows + Shift + S**, bukan foto HP ya biar rapi!
