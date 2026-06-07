# LAPORAN UAS – KEAMANAN SISTEM INFORMASI

## Pengujian Kerentanan Web Aplikasi SHYNESv2 (Business Logic, IDOR, Otentikasi, Injeksi)

**Disusun oleh:**

Nama Mahasiswa : [Nama Mahasiswa 2]

NIM : [NIM.2]

**Program Studi Sistem Informasi**

**Fakultas Ilmu Komputer**

**Universitas [Nama Universitas]**

**2025**

---

## KATA PENGANTAR

Puji syukur ke hadirat Tuhan Yang Maha Esa atas limpahan rahmat dan karunia-Nya sehingga penulis dapat menyelesaikan laporan Ujian Akhir Semester (UAS) mata kuliah Keamanan Sistem Informasi dengan tepat waktu. Laporan ini membahas mengenai pengujian kerentanan (penetration testing) pada aplikasi web SHYNESv2 yang merupakan platform e-commerce berbasis Laravel 11.

Pengujian keamanan yang dilakukan berfokus pada empat area utama sesuai dengan pedoman OWASP Top 10 2021, yaitu Business Logic Flaws, Insecure Direct Object Reference (IDOR), Authentication dan Session Management, serta SQL Injection dan Cross-Site Scripting (XSS). Seluruh rangkaian pengujian dilaksanakan dalam lingkungan yang terkontrol dengan menggunakan berbagai tools keamanan seperti Burp Suite, OWASP ZAP, sqlmap, dan Nuclei.

Penulis menyadari bahwa laporan ini masih jauh dari sempurna. Oleh karena itu, kritik dan saran yang membangun sangat penulis harapkan untuk perbaikan di masa mendatang. Semoga laporan ini dapat memberikan manfaat bagi pengembangan aplikasi SHYNESv2 dan menambah wawasan pembaca mengenai pentingnya keamanan dalam pengembangan perangkat lunak.

Akhir kata, penulis mengucapkan terima kasih kepada dosen pengampu mata kuliah Keamanan Sistem Informasi serta semua pihak yang telah membantu dalam penyusunan laporan ini.

[Nama Kota], [Tanggal] 2025

Penulis

---

## DAFTAR ISI

KATA PENGANTAR ............................................................................................................ ii

DAFTAR ISI ...................................................................................................................... iii

DAFTAR GAMBAR ........................................................................................................... iv

DAFTAR TABEL ................................................................................................................ v

BAB I PENDAHULUAN ...................................................................................................... 1

1.1 Latar Belakang ..................................................................................................... 1

1.2 Rumusan Masalah ............................................................................................... 2

1.3 Batasan Masalah ................................................................................................. 2

1.4 Tujuan Penelitian ................................................................................................. 3

1.5 Manfaat Penelitian ............................................................................................... 3

BAB II LANDASAN TEORI ................................................................................................ 4

2.1 OWASP Top 10 2021 ........................................................................................... 4

2.2 Business Logic Flaws .......................................................................................... 5

2.3 Insecure Direct Object Reference (IDOR) ............................................................ 6

2.4 Authentication dan Session Management ........................................................... 7

2.5 SQL Injection ........................................................................................................ 8

2.6 Cross-Site Scripting (XSS) ................................................................................... 9

2.7 Tools Pengujian Keamanan ................................................................................. 10

BAB III LINGKUNGAN PENGUJIAN ................................................................................. 12

3.1 Target Aplikasi ..................................................................................................... 12

3.2 Spesifikasi Lingkungan ........................................................................................ 13

3.3 Tools yang Digunakan ......................................................................................... 14

3.4 Akun Test ............................................................................................................ 15

BAB IV PENGUJIAN BUSINESS LOGIC FLAWS .............................................................. 16

4.1 Tujuan Pengujian ................................................................................................. 16

4.2 Skenario 1 Race Condition pada Checkout ......................................................... 16

4.3 Skenario 2 Multiple Coupon Usage ...................................................................... 20

4.4 Skenario 3 Order Status Manipulation ................................................................. 23

BAB V PENGUJIAN IDOR (INSECURE DIRECT OBJECT REFERENCE) ......................... 25

5.1 Tujuan Pengujian ................................................................................................. 25

5.2 Skenario 1 Akses Order Milik User Lain .............................................................. 25

5.3 Skenario 2 Akses Kontrak Milik Supplier Lain ..................................................... 28

5.4 Skenario 3 Manipulasi user_id pada POS Checkout ........................................... 30

BAB VI PENGUJIAN OTENTIKASI DAN MANAJEMEN SESI ........................................... 33

6.1 Tujuan Pengujian ................................................................................................. 33

6.2 Skenario 1 Brute Force Login .............................................................................. 33

6.3 Skenario 2 Session Fixation ................................................................................ 37

6.4 Skenario 3 Session Timeout ................................................................................ 39

6.5 Skenario 4 Password Strength ............................................................................ 41

BAB VII PENGUJIAN SQL INJECTION DAN XSS ............................................................ 44

7.1 Tujuan Pengujian ................................................................................................. 44

7.2 Skenario 1 SQL Injection pada URL Parameter .................................................. 44

7.3 Skenario 2 SQL Injection pada Search ................................................................ 47

7.4 Skenario 3 Reflected XSS pada Search .............................................................. 49

7.5 Skenario 4 Stored XSS pada Review dan Komentar ........................................... 51

7.6 Skenario 5 Stored XSS pada Nama Produk (Admin Panel) ................................ 53

BAB VIII RINGKASAN TEMUAN ...................................................................................... 55

8.1 Tabel Ringkasan Temuan .................................................................................... 55

8.2 Analisis Severity dan Prioritas Perbaikan ............................................................ 56

8.3 Potensi Bug Bounty ............................................................................................. 57

BAB IX PENUTUP ............................................................................................................ 58

9.1 Kesimpulan ......................................................................................................... 58

9.2 Saran .................................................................................................................. 59

DAFTAR PUSTAKA ......................................................................................................... 60

LAMPIRAN ...................................................................................................................... 61

---

## DAFTAR GAMBAR

Gambar 4.1 Konfigurasi Burp Suite Intruder untuk Race Condition ................................... 17

Gambar 4.2 Request Checkout Simultan .......................................................................... 18

Gambar 4.3 Hasil Race Condition Stok Berkurang Ganda ................................................ 19

Gambar 4.4 Kupon Berhasil Digunakan Berkali-kali .......................................................... 21

Gambar 4.5 Intercept Request Update Status Order ........................................................ 23

Gambar 5.1 IDOR Order Test Request ............................................................................. 26

Gambar 5.2 Response 403 IDOR Order ........................................................................... 27

Gambar 5.3 IDOR Contract Test via Browser ................................................................... 29

Gambar 5.4 Manipulasi user_id pada POS Checkout ....................................................... 31

Gambar 6.1 Burp Intruder Brute Force Configuration ....................................................... 34

Gambar 6.2 Hasil Brute Force Login Attempt ................................................................... 35

Gambar 6.3 Session ID Before and After Login ............................................................... 38

Gambar 6.4 Session Active After 30 Minutes Idle ............................................................ 40

Gambar 6.5 Weak Password Registration ........................................................................ 42

Gambar 7.1 sqlmap Scanning Parameter category_id ...................................................... 45

Gambar 7.2 sqlmap Test Results ...................................................................................... 46

Gambar 7.3 SQL Injection via Search Box ........................................................................ 48

Gambar 7.4 Reflected XSS Test ....................................................................................... 50

Gambar 7.5 Stored XSS pada Field Alamat ...................................................................... 52

Gambar 7.6 Stored XSS pada Nama Produk ..................................................................... 54

---

## DAFTAR TABEL

Tabel 3.1 Spesifikasi Lingkungan Target .......................................................................... 13

Tabel 3.2 Tools Pengujian Keamanan .............................................................................. 14

Tabel 3.3 Akun Test ......................................................................................................... 15

Tabel 4.1 Hasil Pengujian Race Condition ........................................................................ 19

Tabel 4.2 Hasil Pengujian Multiple Coupon Usage ............................................................ 22

Tabel 4.3 Hasil Pengujian Order Status Manipulation ....................................................... 24

Tabel 5.1 Hasil Pengujian IDOR Order ............................................................................. 27

Tabel 5.2 Hasil Pengujian IDOR Contract ......................................................................... 29

Tabel 5.3 Hasil Pengujian IDOR POS Checkout ................................................................ 31

Tabel 6.1 Hasil Brute Force Login .................................................................................... 36

Tabel 6.2 Hasil Session Fixation Test ............................................................................... 38

Tabel 6.3 Hasil Session Timeout Test ............................................................................... 40

Tabel 6.4 Hasil Password Strength Test ........................................................................... 42

Tabel 7.1 Hasil SQL Injection URL Parameter .................................................................. 46

Tabel 7.2 Hasil SQL Injection Search ................................................................................ 48

Tabel 7.3 Hasil Reflected XSS .......................................................................................... 50

Tabel 7.4 Hasil Stored XSS Review .................................................................................. 52

Tabel 7.5 Ringkasan Hasil Pengujian SQLi dan XSS ........................................................ 54

Tabel 8.1 Ringkasan Seluruh Temuan .............................................................................. 55

Tabel 8.2 Analisis Severity ............................................................................................... 56

Tabel 8.3 Estimasi Bug Bounty ........................................................................................ 57

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang

Perkembangan teknologi informasi dan komunikasi telah membawa perubahan fundamental dalam dunia bisnis, khususnya pada sektor perdagangan elektronik atau e-commerce. Platform e-commerce memungkinkan transaksi jual beli dilakukan secara daring tanpa adanya batasan geografis dan waktu. Kemudahan ini mendorong pertumbuhan jumlah platform e-commerce secara eksponensial dalam beberapa tahun terakhir. Namun, di balik kemudahan tersebut terdapat ancaman keamanan siber yang semakin kompleks dan terus berkembang seiring dengan meningkatnya ketergantungan masyarakat terhadap layanan digital.

Aplikasi web e-commerce menyimpan berbagai data sensitif seperti informasi pribadi pengguna, data pembayaran, riwayat transaksi, dan data inventaris produk. Data-data ini menjadi target empuk bagi para pelaku kejahatan siber yang ingin mengambil keuntungan secara ilegal. Berdasarkan laporan Verizon Data Breach Investigations Report tahun 2024, sektor ritel dan e-commerce menempati peringkat ketiga terbanyak dalam kasus pelanggaran data dengan kerugian finansial yang mencapai miliaran dolar setiap tahunnya. Oleh karena itu, pengujian keamanan atau penetration testing menjadi langkah yang sangat krusial dalam siklus pengembangan aplikasi e-commerce.

SHYNESv2 merupakan aplikasi web e-commerce berbasis Laravel 11 yang menyediakan layanan jual beli produk secara online. Aplikasi ini memiliki berbagai fitur seperti manajemen produk, sistem keranjang belanja, proses checkout, sistem kupon diskon, manajemen pesanan, dan sistem pembayaran. Dengan kompleksitas fitur yang dimiliki, terdapat berbagai kemungkinan celah keamanan yang dapat dieksploitasi oleh pihak yang tidak bertanggung jawab. Celah keamanan tersebut dapat berasal dari kesalahan logika bisnis, kelemahan dalam pengontrolan akses, manajemen sesi yang tidak aman, hingga kerentanan terhadap serangan injeksi.

Laporan ini menyajikan hasil pengujian keamanan yang komprehensif terhadap aplikasi SHYNESv2 dengan berfokus pada empat area kerentanan utama sebagaimana yang direkomendasikan oleh OWASP Top 10 2021. Keempat area tersebut meliputi Business Logic Flaws, Insecure Direct Object Reference (IDOR), Authentication dan Session Management, serta SQL Injection dan Cross-Site Scripting (XSS). Pengujian dilakukan dengan menggunakan metodologi dan tools standar industri keamanan siber untuk memberikan gambaran yang akurat mengenai postur keamanan aplikasi SHYNESv2.

Hasil dari pengujian ini diharapkan dapat menjadi acuan bagi pengembang aplikasi SHYNESv2 dalam melakukan perbaikan dan peningkatan keamanan sistem. Selain itu, laporan ini juga memberikan rekomendasi perbaikan yang spesifik dan dapat ditindaklanjuti untuk setiap temuan kerentanan yang berhasil diidentifikasi selama proses pengujian.

### 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, maka rumusan masalah dalam penelitian ini adalah sebagai berikut:

1. Bagaimana tingkat kerentanan aplikasi SHYNESv2 terhadap Business Logic Flaws khususnya pada fitur checkout, penggunaan kupon diskon, dan manipulasi status pesanan?
2. Apakah terdapat celah Insecure Direct Object Reference (IDOR) yang memungkinkan pengguna mengakses data milik pengguna lain pada aplikasi SHYNESv2?
3. Bagaimana implementasi mekanisme otentikasi dan manajemen sesi pada aplikasi SHYNESv2, termasuk ketahanannya terhadap serangan brute force, session fixation, dan kebijakan password yang lemah?
4. Apakah aplikasi SHYNESv2 rentan terhadap serangan SQL Injection dan Cross-Site Scripting (XSS) pada berbagai titik input yang tersedia?
5. Apa saja rekomendasi perbaikan yang dapat diberikan untuk meningkatkan keamanan aplikasi SHYNESv2 berdasarkan hasil pengujian yang dilakukan?

### 1.3 Batasan Masalah

Agar pembahasan dalam laporan ini lebih terfokus dan mendalam, maka penelitian dibatasi pada hal-hal berikut:

1. Pengujian keamanan dilakukan secara terbatas pada empat area kerentanan yang tercakup dalam OWASP Top 10 2021, yaitu:
   a. Business Logic Flaws yang termasuk dalam kategori A01:2021 Broken Access Control.
   b. Insecure Direct Object Reference (IDOR) yang juga merupakan bagian dari Broken Access Control.
   c. Authentication dan Session Management yang termasuk dalam kategori A07:2021 Identification and Authentication Failures.
   d. SQL Injection dan Cross-Site Scripting (XSS) yang termasuk dalam kategori A03:2021 Injection.
2. Target pengujian adalah aplikasi SHYNESv2 yang diakses melalui URL https://shynesv2.up.railway.app dalam lingkungan staging yang telah disediakan.
3. Pengujian tidak mencakup serangan denial of service (DoS/DDoS), serangan infrastruktur server, social engineering, atau serangan fisik.
4. Tools yang digunakan terbatas pada perangkat lunak yang bersifat open source atau free edition, yaitu Burp Suite Community Edition, OWASP ZAP, sqlmap, Nuclei, dan curl.
5. Pengujian dilakukan dengan menggunakan akun test yang telah disediakan, yaitu akun admin, supplier, dan pembeli.
6. Tidak dilakukan eksploitasi lebih lanjut terhadap kerentanan yang ditemukan, hanya sebatas identifikasi dan verifikasi keberadaan celah keamanan.
7. Laporan ini tidak membahas aspek keamanan pada sisi infrastruktur cloud provider Railway, melainkan hanya pada kode aplikasi dan konfigurasi sistem.

### 1.4 Tujuan Penelitian

Tujuan dari penelitian ini adalah sebagai berikut:

1. Mengidentifikasi dan menganalisis kerentanan Business Logic Flaws pada aplikasi SHYNESv2, khususnya pada fitur checkout, penggunaan kupon, dan manipulasi status pesanan.
2. Mendeteksi adanya celah Insecure Direct Object Reference (IDOR) yang dapat memungkinkan pengguna yang tidak berwenang mengakses data milik pengguna lain.
3. Mengevaluasi implementasi mekanisme otentikasi dan manajemen sesi pada aplikasi SHYNESv2 termasuk ketahanan terhadap brute force, session fixation, dan kebijakan password.
4. Menguji ketahanan aplikasi SHYNESv2 terhadap serangan SQL Injection dan Cross-Site Scripting (XSS) pada berbagai titik input.
5. Memberikan rekomendasi perbaikan yang spesifik dan aplikatif untuk setiap kerentanan yang ditemukan guna meningkatkan postur keamanan aplikasi SHYNESv2.

### 1.5 Manfaat Penelitian

Manfaat yang diharapkan dari penelitian ini adalah sebagai berikut:

1. **Bagi Pengembang Aplikasi**: Memberikan informasi yang komprehensif mengenai celah keamanan yang terdapat pada aplikasi SHYNESv2 beserta rekomendasi perbaikannya sehingga dapat segera ditindaklanjuti.
2. **Bagi Pemilik Platform**: Meningkatkan keamanan platform e-commerce sehingga data pengguna dan transaksi terlindungi dengan lebih baik, serta mengurangi risiko kerugian finansial akibat serangan siber.
3. **Bagi Akademisi**: Menjadi referensi dan bahan kajian mengenai metodologi pengujian keamanan aplikasi web e-commerce serta implementasi OWASP Top 10 dalam konteks aplikasi berbasis Laravel.
4. **Bagi Pengguna**: Memberikan jaminan bahwa keamanan data pribadi dan transaksi menjadi prioritas utama dalam pengembangan aplikasi.
5. **Bagi Penulis**: Menambah pengetahuan dan pengalaman praktis dalam melakukan pengujian keamanan aplikasi web menggunakan berbagai tools dan metodologi standar industri.

---

## BAB II: LANDASAN TEORI

### 2.1 OWASP Top 10 2021

OWASP (Open Web Application Security Project) merupakan sebuah organisasi nirlaba yang berfokus pada peningkatan keamanan perangkat lunak. Salah satu kontribusi paling signifikan dari OWASP adalah publikasi OWASP Top 10, yaitu sebuah dokumen yang berisi sepuluh kategori risiko keamanan aplikasi web yang paling kritis dan sering ditemukan. Dokumen ini diperbarui secara berkala dan menjadi acuan utama bagi para pengembang, profesional keamanan, dan organisasi dalam mengidentifikasi serta memitigasi risiko keamanan pada aplikasi web.

Edisi terbaru OWASP Top 10 yang dirilis pada tahun 2021 membawa perubahan signifikan dari edisi sebelumnya. Perubahan tersebut mencakup pengelompokan ulang kategori kerentanan berdasarkan data lapangan dan survei yang melibatkan lebih dari 500 profesional keamanan di seluruh dunia. Berikut adalah daftar sepuluh kategori risiko keamanan dalam OWASP Top 10 2021:

1. **A01:2021 – Broken Access Control**: Kategori ini menempati peringkat pertama dengan peningkatan signifikan dari edisi sebelumnya. Kerentanan pada akses kontrol mencakup berbagai celah seperti pelanggaran prinsip least privilege, IDOR (Insecure Direct Object Reference), privilege escalation, dan kegagalan dalam membatasi akses pengguna terhadap fungsi atau data tertentu.

2. **A02:2021 – Cryptographic Failures**: Kategori ini sebelumnya dikenal sebagai Sensitive Data Exposure. Fokusnya adalah pada kegagalan dalam implementasi kriptografi yang dapat menyebabkan kebocoran data sensitif seperti password, nomor kartu kredit, dan informasi pribadi lainnya.

3. **A03:2021 – Injection**: Kategori ini mencakup berbagai jenis serangan injeksi termasuk SQL Injection, NoSQL Injection, OS Command Injection, dan Cross-Site Scripting (XSS). Meskipun XSS dimasukkan ke dalam kategori ini, OWASP tetap mengakui XSS sebagai kategori yang terpisah dalam beberapa konteks.

4. **A04:2021 – Insecure Design**: Kategori baru yang berfokus pada risiko yang terkait dengan kesalahan desain arsitektur aplikasi. Berbeda dengan implementasi yang salah, insecure design merujuk pada kelemahan yang melekat pada desain aplikasi sejak awal.

5. **A05:2021 – Security Misconfiguration**: Kategori ini mencakup konfigurasi keamanan yang tidak tepat seperti default credentials yang tidak diubah, fitur yang tidak perlu tetap aktif, error handling yang berlebihan, dan header keamanan yang tidak dikonfigurasi dengan benar.

6. **A06:2021 – Vulnerable and Outdated Components**: Kategori ini berfokus pada risiko yang timbul dari penggunaan komponen perangkat lunak yang memiliki kerentanan yang diketahui, terutama library dan framework yang tidak diperbarui.

7. **A07:2021 – Identification and Authentication Failures**: Kategori ini mencakup kelemahan dalam mekanisme identifikasi dan otentikasi pengguna seperti credential stuffing, brute force attack, session fixation, dan manajemen sesi yang tidak aman.

8. **A08:2021 – Software and Data Integrity Failures**: Kategori baru yang berfokus pada risiko terkait integritas perangkat lunak dan data, termasuk serangan supply chain dan pembaruan perangkat lunak yang tidak aman.

9. **A09:2021 – Security Logging and Monitoring Failures**: Kategori ini menekankan pentingnya logging dan monitoring yang memadai untuk mendeteksi serta merespons insiden keamanan secara tepat waktu.

10. **A10:2021 – Server-Side Request Forgery (SSRF)**: Kategori baru yang masuk ke dalam daftar berdasarkan hasil survei komunitas. SSRF terjadi ketika aplikasi web mengambil resource dari remote URL tanpa melakukan validasi yang memadai.

Dalam konteks pengujian aplikasi SHYNESv2, fokus utama laporan ini adalah pada kategori A01:2021 (Broken Access Control) yang mencakup Business Logic Flaws dan IDOR, kategori A03:2021 (Injection) yang mencakup SQL Injection dan XSS, serta kategori A07:2021 (Identification and Authentication Failures) yang mencakup otentikasi dan manajemen sesi.

### 2.2 Business Logic Flaws

Business Logic Flaws atau cacat logika bisnis merupakan jenis kerentanan keamanan yang muncul dari kesalahan dalam perancangan atau implementasi alur logika bisnis pada suatu aplikasi. Berbeda dengan kerentanan teknis seperti SQL Injection atau XSS yang bersumber dari kelemahan teknis dalam pemrosesan input, Business Logic Flaws lebih berkaitan dengan bagaimana fungsi-fungsi bisnis saling berinteraksi dan bagaimana pengguna dapat memanipulasi alur tersebut untuk mendapatkan keuntungan yang tidak sah.

Menurut OWASP, Business Logic Flaws termasuk dalam kategori A01:2021 Broken Access Control karena pada dasarnya kerentanan ini memungkinkan pengguna untuk melakukan tindakan di luar yang seharusnya diizinkan oleh aturan bisnis. Beberapa contoh umum Business Logic Flaws dalam konteks aplikasi e-commerce meliputi:

**Race Condition**: Race condition terjadi ketika dua atau lebih proses atau thread mengakses resource yang sama secara bersamaan tanpa adanya mekanisme penguncian (locking) yang memadai. Dalam konteks e-commerce, race condition sering terjadi pada proses checkout di mana beberapa permintaan pembelian untuk produk yang sama dikirim secara simultan. Jika stok produk diperiksa dan dikurangi tanpa mekanisme atomik, maka dua pembeli dapat berhasil membeli produk yang sama meskipun stok hanya tersisa satu. Teknik eksploitasi race condition biasanya menggunakan tools seperti Burp Suite Intruder dengan pengaturan request simultan atau teknik "last-byte sync" untuk mengirimkan banyak request dalam waktu yang hampir bersamaan.

**Coupon Abuse**: Kerentanan ini terjadi ketika pengguna dapat menggunakan kupon diskon secara tidak semestinya, misalnya dengan menggunakan kupon yang sama berkali-kali, menggunakan kupon yang sudah kadaluwarsa, atau menggunakan kupon yang tidak seharusnya tersedia untuk pengguna tersebut. Celah ini biasanya muncul karena tidak adanya mekanisme pencatatan penggunaan kupon per pengguna atau tidak adanya validasi status kupon pada saat transaksi.

**Order Manipulation**: Kerentanan ini melibatkan upaya pengguna untuk mengubah status pesanan, jumlah pesanan, harga, atau detail transaksi lainnya melalui manipulasi request yang dikirim ke server. Aplikasi yang tidak melakukan validasi sisi server secara memadai akan rentan terhadap jenis serangan ini.

Dampak dari Business Logic Flaws dapat sangat bervariasi tergantung pada konteks bisnis yang terpengaruh. Dalam aplikasi e-commerce, dampaknya dapat berupa kerugian finansial langsung, ketidakadilan dalam transaksi, manipulasi data inventaris, hingga gangguan pada operasional bisnis. Oleh karena itu, pengujian Business Logic Flaws memerlukan pemahaman yang mendalam tentang alur bisnis aplikasi dan pendekatan pengujian yang kreatif.

### 2.3 Insecure Direct Object Reference (IDOR)

Insecure Direct Object Reference (IDOR) merupakan salah satu jenis kerentanan akses kontrol yang terjadi ketika suatu aplikasi menyediakan akses langsung ke objek internal seperti file, record database, atau resource lainnya berdasarkan input yang disediakan oleh pengguna tanpa melakukan validasi otorisasi yang memadai. IDOR termasuk dalam kategori A01:2021 Broken Access Control menurut OWASP Top 10 2021.

Mekanisme terjadinya IDOR cukup sederhana namun sering kali luput dari perhatian pengembang. Sebuah aplikasi web biasanya mengidentifikasi resource tertentu menggunakan parameter yang mudah ditebak, seperti ID numerik pada URL (contoh: `/orders/5`, `/users/123`, `/contracts/7`). Jika aplikasi hanya memeriksa apakah resource tersebut ada tanpa memeriksa apakah pengguna yang mengakses memiliki hak akses terhadap resource tersebut, maka pengguna lain dapat mengakses resource milik pengguna lain hanya dengan mengubah nilai parameter ID.

Terdapat beberapa jenis IDOR yang umum ditemukan dalam pengujian keamanan aplikasi web:

1. **Horizontal IDOR**: Terjadi ketika pengguna dapat mengakses resource milik pengguna lain dengan level akses yang sama. Sebagai contoh, seorang pembeli dapat melihat data pesanan milik pembeli lain dengan mengubah order_id pada URL.

2. **Vertical IDOR**: Terjadi ketika pengguna dapat mengakses resource yang seharusnya hanya tersedia untuk pengguna dengan level akses yang lebih tinggi. Contohnya, seorang pengguna biasa dapat mengakses halaman admin dengan memanipulasi parameter atau URL.

3. **Oblique IDOR**: Merupakan kombinasi dari horizontal dan vertical IDOR, di mana pengguna dapat mengakses resource milik pengguna lain dengan level akses yang berbeda.

Dampak dari kerentanan IDOR sangat bergantung pada jenis data yang terekspos. Dalam konteks aplikasi e-commerce, IDOR dapat menyebabkan kebocoran data pribadi pengguna, riwayat transaksi, data pembayaran, dan informasi sensitif lainnya. Pada kasus yang lebih parah, IDOR dapat memungkinkan pengguna untuk memodifikasi atau menghapus data milik pengguna lain.

Pencegahan IDOR dapat dilakukan melalui beberapa pendekatan:

- **Menggunakan UUID (Universally Unique Identifier)** sebagai pengganti ID numerik yang mudah ditebak.
- **Menerapkan otorisasi berbasis pengguna** dengan selalu memeriksa kepemilikan resource sebelum mengizinkan akses.
- **Menggunakan mekanisme indirect reference map** di mana referensi langsung ke objek digantikan dengan token acak yang dipetakan ke objek sesungguhnya di sisi server.
- **Menerapkan prinsip least privilege** pada setiap endpoint API.

### 2.4 Authentication dan Session Management

Authentication dan Session Management merupakan dua komponen fundamental dalam keamanan aplikasi web. Authentication adalah proses verifikasi identitas pengguna, sedangkan Session Management adalah mekanisme yang memungkinkan server untuk melacak status otentikasi pengguna selama interaksi berlangsung. Kedua komponen ini termasuk dalam kategori A07:2021 Identification and Authentication Failures menurut OWASP Top 10 2021.

**Brute Force Attack** merupakan salah satu serangan yang paling umum terhadap mekanisme otentikasi. Serangan ini dilakukan dengan mencoba berbagai kombinasi username dan password secara berulang-ulang hingga menemukan kombinasi yang tepat. Tools seperti Burp Suite Intruder, Hydra, atau Medusa dapat digunakan untuk melakukan brute force attack secara otomatis dengan menggunakan wordlist yang berisi ribuan atau bahkan jutaan kombinasi password. Tanpa adanya mekanisme pembatasan seperti rate limiting, account lockout, atau CAPTCHA, aplikasi web menjadi sangat rentan terhadap serangan ini.

**Session Fixation** adalah serangan di mana penyerang memaksa korban untuk menggunakan session ID yang sudah diketahui oleh penyerang. Setelah korban berhasil login dengan session ID tersebut, penyerang dapat menggunakan session ID yang sama untuk mengakses akun korban tanpa perlu mengetahui kredensial login. Serangan ini dapat dicegah dengan melakukan regenerasi session ID setelah pengguna berhasil login.

**Session Timeout** merupakan mekanisme keamanan yang secara otomatis mengakhiri sesi pengguna setelah periode ketidakaktifan tertentu. Tanpa session timeout yang memadai, sesi yang tidak digunakan tetap aktif dan berpotensi disalahgunakan oleh pihak yang tidak berwenang, terutama pada perangkat yang digunakan bersama. Standar keamanan merekomendasikan session timeout tidak lebih dari 15 hingga 30 menit untuk aplikasi yang menangani data sensitif.

**Password Policy** adalah serangkaian aturan yang memastikan pengguna memilih password yang kuat dan sulit ditebak. Kebijakan password yang baik mencakup persyaratan panjang minimum (minimal 8 karakter), penggunaan kombinasi huruf besar dan kecil, angka, serta karakter khusus. Selain itu, aplikasi juga sebaiknya melakukan pengecekan terhadap password yang umum digunakan atau password yang telah bocor dalam pelanggaran data sebelumnya.

Laravel sebagai framework PHP modern menyediakan berbagai fitur keamanan untuk otentikasi dan manajemen sesi, termasuk:

- **Middleware throttle** untuk membatasi jumlah percobaan login.
- **Session regeneration** otomatis setelah login.
- **Password hashing** menggunakan bcrypt atau Argon2.
- **CSRF protection** untuk mencegah cross-site request forgery.
- **Encrypted session data** untuk melindungi data sesi.

Namun, implementasi fitur-fitur tersebut tetap harus dikonfigurasi dengan benar oleh pengembang agar dapat memberikan perlindungan yang optimal.

### 2.5 SQL Injection

SQL Injection (SQLi) merupakan salah satu serangan tertua dan paling berbahaya terhadap aplikasi web. Serangan ini terjadi ketika penyerang berhasil menyisipkan perintah SQL berbahaya ke dalam query database melalui input pengguna yang tidak divalidasi dengan benar. SQL Injection termasuk dalam kategori A03:2021 Injection menurut OWASP Top 10 2021.

Prinsip dasar SQL Injection adalah memanipulasi query SQL yang dieksekusi oleh aplikasi dengan cara menyisipkan karakter atau perintah SQL khusus pada input yang disediakan oleh aplikasi. Sebagai contoh, jika aplikasi menjalankan query:
```sql
SELECT * FROM products WHERE id = '$input';
```
Penyerang dapat memberikan input berupa `1' OR '1'='1` sehingga query menjadi:
```sql
SELECT * FROM products WHERE id = '1' OR '1'='1';
```
Query tersebut akan mengembalikan seluruh data dalam tabel products karena kondisi `OR '1'='1'` selalu bernilai true.

Terdapat beberapa jenis SQL Injection yang perlu dipahami:

1. **In-Band SQL Injection**: Jenis yang paling umum dan mudah dieksploitasi. Penyerang menggunakan jalur komunikasi yang sama untuk melancarkan serangan dan mengumpulkan hasil. Terdapat dua sub-jenis: Error-based SQLi yang memanfaatkan pesan error dari database, dan Union-based SQLi yang menggunakan operator UNION untuk menggabungkan hasil query.

2. **Blind SQL Injection**: Jenis SQLi di mana penyerang tidak dapat melihat hasil query secara langsung. Penyerang harus menyimpulkan informasi berdasarkan respons aplikasi (Boolean-based) atau berdasarkan waktu respons (Time-based).

3. **Out-of-Band SQL Injection**: Jenis SQLi yang membutuhkan fitur tertentu pada database server untuk mengirimkan data melalui jalur yang berbeda, misalnya melalui DNS atau HTTP request.

Dampak SQL Injection sangat serius dan dapat mencakup:

- Kebocoran seluruh data dalam database, termasuk data pengguna dan informasi sensitif.
- Modifikasi atau penghapusan data dalam database.
- Remote code execution pada server database.
- Bypass mekanisme otentikasi.
- Complete compromise of the application and underlying infrastructure.

Pencegahan SQL Injection dapat dilakukan melalui beberapa metode:

- **Parameterized Statements / Prepared Statements**: Metode paling efektif di mana query SQL didefinisikan terlebih dahulu dengan placeholder, kemudian nilai input diberikan secara terpisah sehingga tidak dapat mengubah struktur query.
- **ORM (Object-Relational Mapping)**: Penggunaan ORM seperti Eloquent pada Laravel secara otomatis menggunakan parameter binding untuk query yang dihasilkan.
- **Input Validation and Sanitization**: Memvalidasi dan membersihkan input pengguna sebelum digunakan dalam query.
- **Escaping Special Characters**: Melakukan escaping terhadap karakter khusus yang memiliki makna dalam SQL.

Laravel dengan Eloquent ORM-nya memberikan perlindungan bawaan terhadap SQL Injection karena secara default menggunakan parameter binding untuk semua query yang dihasilkan. Namun, pengembang tetap dapat membuat kode yang rentan jika menggunakan raw SQL atau metode `DB::raw()` tanpa parameter binding yang tepat.

### 2.6 Cross-Site Scripting (XSS)

Cross-Site Scripting (XSS) merupakan jenis serangan injection di mana penyerang menyisipkan skrip berbahaya (biasanya JavaScript) ke dalam halaman web yang kemudian dijalankan oleh browser korban. Dalam OWASP Top 10 2021, XSS termasuk dalam kategori A03:2021 Injection. Meskipun telah dikenal selama bertahun-tahun, XSS masih menjadi salah satu kerentanan yang paling sering ditemukan pada aplikasi web modern.

Terdapat tiga jenis utama XSS:

1. **Reflected XSS**: Skrip berbahaya disisipkan melalui request dan langsung direfleksikan ke dalam response tanpa melalui proses penyimpanan. Contoh paling umum adalah skrip yang disisipkan pada parameter pencarian. Ketika pengguna mengakses URL yang mengandung skrip berbahaya, browser akan mengeksekusi skrip tersebut jika tidak ada sanitasi yang memadai.

   Contoh:
   ```
   https://shynesv2.up.railway.app/search?q=<script>alert('XSS')</script>
   ```
   Jika aplikasi merender nilai parameter `q` langsung ke halaman tanpa escaping, maka skrip akan dijalankan.

2. **Stored XSS**: Skrip berbahaya disimpan dalam database dan kemudian ditampilkan kepada pengguna lain yang mengakses halaman tertentu. Jenis ini lebih berbahaya karena dapat menjangkau lebih banyak korban tanpa memerlukan teknik sosial engineering yang kompleks. Contoh titik masuk stored XSS meliputi kolom komentar, review produk, profil pengguna, dan field input lainnya yang datanya ditampilkan kembali.

3. **DOM-based XSS**: Skrip berbahaya dieksekusi melalui manipulasi DOM (Document Object Model) di sisi klien tanpa melibatkan server. Kerentanan ini muncul ketika JavaScript pada halaman web menggunakan input dari sumber yang tidak terpercaya (seperti URL fragment atau localStorage) untuk memodifikasi DOM secara dinamis.

Dampak dari serangan XSS sangat bervariasi tergantung pada konteks dan kebijakan keamanan yang diterapkan:

- **Session Hijacking**: Penyerang dapat mencuri cookie sesi korban dan menggunakannya untuk mengakses akun korban.
- **Keylogging**: Merekam setiap penekanan tombol yang dilakukan korban.
- **Phishing**: Menampilkan formulir login palsu untuk mencuri kredensial.
- **Defacement**: Mengubah tampilan halaman web.
- **Malware Distribution**: Mengarahkan korban ke situs yang mengandung malware.

Pencegahan XSS dilakukan melalui beberapa lapisan pertahanan:

- **Output Encoding / Escaping**: Melakukan encoding terhadap karakter khusus HTML, JavaScript, dan URL sebelum merender data ke halaman web. Laravel Blade template engine secara otomatis melakukan escaping menggunakan sintaks `{{ $variable }}`.
- **Content Security Policy (CSP)**: Menggunakan header HTTP CSP untuk membatasi sumber daya yang dapat dijalankan oleh browser.
- **Input Validation**: Menerima input yang diharapkan saja dan menolak input yang mengandung karakter berbahaya.
- **HttpOnly Cookies**: Menandai cookie sesi sebagai HttpOnly sehingga tidak dapat diakses oleh JavaScript.

Laravel menyediakan perlindungan XSS secara default melalui Blade template engine yang menggunakan sintaks `{{ }}` untuk melakukan escaping otomatis. Namun, pengembang dapat secara sengaja melewati perlindungan ini dengan menggunakan sintaks `{!! !!}` yang merender data mentah tanpa escaping. Oleh karena itu, penting untuk memastikan bahwa sintaks `{!! !!}` hanya digunakan pada data yang benar-benar tepercaya.

### 2.7 Tools Pengujian Keamanan

Pengujian keamanan aplikasi web modern memerlukan berbagai tools yang dirancang untuk tujuan spesifik. Setiap tools memiliki kelebihan dan kelemahan masing-masing, sehingga penggunaan kombinasi beberapa tools akan memberikan hasil pengujian yang lebih komprehensif. Berikut adalah tools yang digunakan dalam pengujian aplikasi SHYNESv2:

**Burp Suite Community Edition** adalah platform pengujian keamanan aplikasi web yang dikembangkan oleh PortSwigger. Burp Suite berfungsi sebagai proxy intercept yang memungkinkan penguji untuk menangkap, memeriksa, dan memodifikasi lalu lintas HTTP/HTTPS antara browser dan server. Fitur utama Burp Suite Community Edition meliputi:

- **Proxy**: Menangkap dan memodifikasi request dan response secara real-time.
- **Repeater**: Mengirim ulang request yang telah dimodifikasi untuk pengujian manual.
- **Intruder**: Melakukan serangan otomatis dengan berbagai payload untuk pengujian brute force, fuzzing, dan parameter testing.
- **Decoder**: Melakukan encoding dan decoding data dalam berbagai format.
- **Sequencer**: Menguji kualitas acak dari token sesi.

Meskipun edisi Community memiliki keterbatasan dibandingkan edisi Professional (seperti kecepatan Intruder yang dibatasi), Burp Suite tetap menjadi tools yang sangat powerful untuk pengujian keamanan manual.

**OWASP ZAP (Zed Attack Proxy)** adalah tools pengujian keamanan aplikasi web open source yang dikembangkan oleh OWASP. ZAP menyediakan fitur automated scanner yang dapat mendeteksi berbagai jenis kerentanan secara otomatis. Beberapa fitur utama ZAP meliputi:

- **Automated Scan**: Melakukan pemindaian otomatis terhadap target untuk mendeteksi kerentanan umum.
- **Passive Scan**: Menganalisis lalu lintas tanpa mengirim request berbahaya.
- **Active Scan**: Mengirim request berbahaya untuk menguji kerentanan secara aktif.
- **Fuzzing**: Mengirim berbagai variasi input untuk menguji batasan aplikasi.
- **WebSocket Support**: Mendukung pengujian koneksi WebSocket.

ZAP sangat berguna untuk pengujian awal (reconnaissance) untuk mendapatkan gambaran umum tentang postur keamanan aplikasi sebelum dilakukan pengujian manual yang lebih mendalam.

**sqlmap** adalah tools open source yang digunakan untuk mendeteksi dan mengeksploitasi kerentanan SQL Injection secara otomatis. Dikembangkan dalam bahasa Python, sqlmap menyediakan berbagai fitur canggih:

- **Deteksi Otomatis**: Mendeteksi parameter yang rentan terhadap SQL Injection.
- **Database Fingerprinting**: Mengidentifikasi tipe dan versi database server.
- **Data Ekstraksi**: Mengambil data dari database yang rentan secara otomatis.
- **File System Access**: Membaca dan menulis file pada server database (pada konfigurasi tertentu).
- **Out-of-Band Exploitation**: Mendukung teknik eksfiltrasi data melalui DNS atau HTTP.

sqlmap mendukung berbagai teknik injeksi termasuk Boolean-based blind, Time-based blind, Error-based, Union-based, dan Out-of-band.

**Nuclei** adalah tools pemindaian keamanan berbasis template yang dikembangkan oleh ProjectDiscovery. Nuclei menggunakan template YAML yang mendefinisikan pola kerentanan spesifik. Kelebihan utama Nuclei adalah:

- **Template-based**: Pengguna tinggal menjalankan template yang sudah tersedia untuk mendeteksi kerentanan tertentu.
- **Multi-protocol**: Mendukung HTTP, TCP, DNS, dan protokol lainnya.
- **Rapid Updates**: Template diperbarui secara berkala oleh komunitas.
- **Extensible**: Pengguna dapat membuat template kustom untuk kerentanan spesifik.

**curl (Client URL)** adalah tools command-line yang digunakan untuk mentransfer data menggunakan berbagai protokol. Dalam konteks pengujian keamanan, curl sangat berguna untuk:

- **Manual API Testing**: Mengirim request HTTP dengan kontrol penuh atas header, method, dan body.
- **Scripting**: Membuat skrip pengujian otomatis sederhana.
- **Header Inspection**: Memeriksa header response untuk analisis keamanan.
- **Cookie Handling**: Mengelola cookie sesi untuk pengujian session management.

Kombinasi kelima tools tersebut memberikan cakupan pengujian yang komprehensif, mulai dari pemindaian otomatis hingga pengujian manual yang mendalam pada area-area spesifik.

---

## BAB III: LINGKUNGAN PENGUJIAN

### 3.1 Target Aplikasi

Target pengujian dalam penelitian ini adalah aplikasi SHYNESv2 yang merupakan platform e-commerce berbasis web. Aplikasi ini diakses melalui URL https://shynesv2.up.railway.app dan berjalan pada platform cloud Railway. SHYNESv2 menyediakan berbagai fitur yang umum ditemukan pada platform e-commerce modern, termasuk:

1. **Manajemen Pengguna**: Sistem registrasi dan login untuk tiga peran pengguna, yaitu admin, supplier, dan pembeli. Setiap peran memiliki hak akses dan tampilan antarmuka yang berbeda.

2. **Katalog Produk**: Menampilkan produk-produk yang tersedia dengan informasi seperti nama, deskripsi, harga, stok, dan kategori. Pengguna dapat mencari produk berdasarkan kata kunci atau menyaring berdasarkan kategori.

3. **Keranjang Belanja**: Pengguna dapat menambahkan produk ke dalam keranjang belanja, mengubah jumlah, dan melanjutkan ke proses checkout.

4. **Sistem Checkout**: Proses pemesanan yang mencakup pengisian alamat pengiriman, pemilihan metode pembayaran, dan penerapan kupon diskon.

5. **Manajemen Pesanan**: Pengguna dapat melihat status pesanan mereka, sedangkan admin dapat memperbarui status pesanan.

6. **Sistem Kupon**: Admin dapat membuat kupon diskon dengan berbagai aturan seperti minimal pembelian, periode berlaku, dan besaran diskon.

7. **POS (Point of Sale)**: Fitur untuk transaksi langsung yang memungkinkan admin membuat pesanan atas nama pelanggan yang datang langsung ke toko.

8. **Manajemen Kontrak**: Supplier dapat melihat kontrak yang dimiliki, sedangkan admin dapat mengelola kontrak dengan supplier.

9. **Review dan Rating**: Pengguna dapat memberikan ulasan dan penilaian terhadap produk yang telah dibeli.

10. **Dashboard Admin**: Antarmuka khusus admin untuk mengelola produk, pesanan, pengguna, kupon, dan konten lainnya.

Aplikasi SHYNESv2 dibangun menggunakan framework Laravel 11 dengan arsitektur MVC (Model-View-Controller) dan menggunakan database PostgreSQL untuk penyimpanan data.

### 3.2 Spesifikasi Lingkungan

Lingkungan pengujian terdiri dari dua bagian utama, yaitu lingkungan target (server) dan lingkungan penguji (client). Berikut adalah spesifikasi lengkap dari kedua lingkungan tersebut:

**Tabel 3.1 Spesifikasi Lingkungan Target**

| Komponen | Spesifikasi |
|----------|-------------|
| Platform Hosting | Railway Cloud |
| URL Aplikasi | https://shynesv2.up.railway.app |
| Framework Backend | Laravel 11.x |
| Bahasa Pemrograman | PHP 8.2.x |
| Database | PostgreSQL 15.x |
| Web Server | Nginx (via Railway) |
| SSL/TLS | Enabled (Let's Encrypt) |
| Session Driver | Database (PostgreSQL) |
| Cache Driver | File |
| Queue Driver | Synchronous |

**Lingkungan Penguji (Client):**

| Komponen | Spesifikasi |
|----------|-------------|
| Sistem Operasi | Windows 11 Pro 64-bit |
| Browser | Google Chrome 120+, Mozilla Firefox 120+ |
| CPU | Intel Core i7-12700H |
| RAM | 32 GB DDR5 |
| Network | Koneksi internet 50 Mbps |
| Tools | Burp Suite CE, OWASP ZAP, sqlmap, Nuclei, curl |

Aplikasi target berjalan pada infrastruktur Railway yang menggunakan containerized deployment. Railway menyediakan load balancing otomatis, SSL termination, dan environment management. Database PostgreSQL berjalan sebagai service terpisah dalam infrastruktur Railway yang sama.

### 3.3 Tools yang Digunakan

Pengujian keamanan dilakukan dengan menggunakan kombinasi tools yang dipilih berdasarkan fungsi spesifik dan kemampuan masing-masing. Berikut adalah daftar lengkap tools yang digunakan beserta perannya dalam pengujian:

**Tabel 3.2 Tools Pengujian Keamanan**

| No | Nama Tools | Versi | Fungsi | Lisensi |
|----|------------|-------|--------|---------|
| 1 | Burp Suite Community Edition | 2024.x | Proxy intercept, repeater, intruder untuk pengujian manual Business Logic, IDOR, dan Autentikasi | Freeware |
| 2 | OWASP ZAP | 2.14.x | Automated scanning untuk deteksi awal kerentanan | Apache 2.0 |
| 3 | sqlmap | 1.8.x | Deteksi dan eksploitasi SQL Injection otomatis | GPL v2 |
| 4 | Nuclei | 3.x | Template-based vulnerability scanning | MIT |
| 5 | curl | 8.x | Manual API testing dan request manipulation | MIT |

**Burp Suite Community Edition** digunakan terutama untuk pengujian Business Logic Flaws dan IDOR. Fitur Intercept memungkinkan penguji untuk mengubah request HTTP sebelum dikirim ke server, sementara Repeater memungkinkan pengiriman ulang request yang telah dimodifikasi. Intruder digunakan untuk simulasi brute force dan race condition, meskipun dengan kecepatan yang terbatas pada edisi Community.

**OWASP ZAP** digunakan untuk pemindaian awal (initial reconnaissance) untuk mengidentifikasi potensi kerentanan secara umum sebelum dilakukan pengujian manual yang lebih mendalam. ZAP juga digunakan untuk memvalidasi temuan dari pengujian manual.

**sqlmap** digunakan secara spesifik untuk mendeteksi kerentanan SQL Injection pada endpoint yang menerima parameter dari URL. sqlmap dijalankan dengan berbagai tingkat risiko dan teknik injeksi untuk memastikan deteksi yang komprehensif.

**Nuclei** digunakan untuk pemindaian berbasis template yang mencakup ribuan template kerentanan yang diperbarui secara berkala oleh komunitas keamanan. Nuclei sangat efektif untuk mendeteksi misconfigurations dan kerentanan yang telah diketahui.

**curl** digunakan untuk pengujian API secara manual, terutama untuk endpoint-endpoint yang memerlukan manipulasi header, cookie, dan body request secara spesifik. curl juga digunakan dalam skrip otomatis untuk pengujian berulang.

### 3.4 Akun Test

Pengujian dilakukan dengan menggunakan tiga akun test yang mewakili tiga peran pengguna dalam aplikasi SHYNESv2. Masing-masing akun memiliki hak akses yang berbeda sesuai dengan perannya. Berikut adalah daftar akun yang digunakan:

**Tabel 3.3 Akun Test**

| No | Peran | Email | Password | Hak Akses |
|----|-------|-------|----------|-----------|
| 1 | Admin | admin@gmail.com | admin123 | Manajemen produk, pesanan, pengguna, kupon, kontrak, dan dashboard penuh |
| 2 | Supplier | supplier@test.com | supplier123 | Melihat produk sendiri, mengelola stok, melihat kontrak |
| 3 | Pembeli | pembeli@test.com | pembeli123 | Melihat katalog, checkout, melihat pesanan sendiri, memberikan review |

**Akun Admin** memiliki hak akses tertinggi dalam aplikasi. Admin dapat mengelola seluruh aspek aplikasi termasuk produk, pesanan, pengguna, kupon diskon, kontrak supplier, dan pengaturan sistem lainnya. Akun admin digunakan untuk menguji kerentanan vertical privilege escalation dan menguji fitur-fitur yang seharusnya hanya dapat diakses oleh admin.

**Akun Supplier** memiliki akses terbatas pada produk dan kontrak yang dimiliki. Supplier dapat melihat daftar produk yang mereka pasok, memperbarui stok, dan melihat kontrak yang dimiliki. Akun supplier digunakan untuk menguji IDOR pada kontrak dan produk, serta untuk menguji pembatasan akses berbasis peran.

**Akun Pembeli** memiliki akses paling terbatas. Pembeli dapat melihat katalog produk, melakukan pencarian, menambahkan produk ke keranjang, melakukan checkout, dan melihat riwayat pesanan sendiri. Akun pembeli digunakan untuk sebagian besar pengujian karena merupakan peran dengan akses paling rendah, sehingga ideal untuk menguji mekanisme pembatasan akses.

Seluruh akun test telah disediakan sebelumnya oleh pengembang aplikasi dan tidak dibuat khusus untuk keperluan pengujian. Penggunaan akun yang sudah ada memastikan bahwa pengujian dilakukan dalam kondisi yang realistis tanpa memengaruhi data pengguna yang sah.

---

## BAB IV: PENGUJIAN BUSINESS LOGIC FLAWS

### 4.1 Tujuan Pengujian

Pengujian Business Logic Flaws bertujuan untuk mengidentifikasi celah keamanan yang berasal dari kesalahan dalam perancangan atau implementasi alur logika bisnis pada aplikasi SHYNESv2. Berbeda dengan kerentanan teknis seperti SQL Injection atau XSS, Business Logic Flaws tidak dapat dideteksi oleh automated scanner secara efektif karena memerlukan pemahaman mendalam tentang konteks bisnis aplikasi.

Tujuan spesifik dari pengujian ini meliputi:

1. Mengidentifikasi apakah terdapat race condition pada proses checkout yang memungkinkan pembelian melebihi stok yang tersedia.
2. Menguji apakah sistem kupon diskon memiliki proteksi terhadap penggunaan kupon secara berulang oleh pengguna yang sama.
3. Memverifikasi apakah mekanisme perubahan status pesanan telah memiliki kontrol akses yang memadai.
4. Memberikan rekomendasi perbaikan untuk setiap kerentanan Business Logic Flaws yang ditemukan.

Pengujian dilakukan dengan pendekatan black-box di mana penguji tidak memiliki akses ke kode sumber aplikasi. Seluruh pengujian dilakukan melalui interaksi dengan antarmuka aplikasi dan API yang tersedia.

### 4.2 Skenario 1: Race Condition pada Checkout

**Latar Belakang**

Race condition pada proses checkout merupakan salah satu kerentanan Business Logic Flaws yang paling kritis dalam aplikasi e-commerce. Kerentanan ini terjadi ketika dua atau lebih permintaan pembelian untuk produk yang sama diproses secara bersamaan tanpa adanya mekanisme penguncian (locking) yang memadai. Akibatnya, stok produk dapat berkurang lebih dari jumlah yang sebenarnya tersedia, atau dua pembeli dapat berhasil membeli produk yang sama meskipun stok hanya tersisa satu.

**Langkah-langkah Pengujian**

1. **Persiapan**: Login ke aplikasi SHYNESv2 menggunakan akun pembeli (pembeli@test.com). Pilih produk dengan stok terbatas (stok = 1) dan tambahkan ke keranjang belanja.

2. **Intercept Request**: Konfigurasi Burp Suite sebagai proxy dan aktifkan intercept. Navigasi ke halaman checkout dan isi data pengiriman. Tangkap request POST yang dikirim saat tombol "Checkout" diklik.

3. **Duplicate Request**: Kirim request yang telah ditangkap ke Burp Suite Repeater. Duplikat request tersebut sehingga terdapat dua request checkout yang identik.

4. **Simultan Request**: Atur Burp Suite Intruder untuk mengirim kedua request secara simultan. Karena Burp Suite Community Edition memiliki keterbatasan pada Intruder, pengiriman simultan dilakukan dengan mengirim kedua request secara manual dalam waktu yang sangat berdekatan.

5. **Verifikasi**: Setelah kedua request dikirim, periksa stok produk dan status pesanan pada database melalui antarmuka admin.

[Screenshot: Konfigurasi Burp Suite Intruder untuk Race Condition]

**Gambar 4.1** Konfigurasi Burp Suite Intruder untuk simulasi race condition pada proses checkout. Tampak dua request checkout yang identik disiapkan untuk dikirim secara simultan.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, ditemukan bahwa aplikasi SHYNESv2 rentan terhadap race condition pada proses checkout. Ketika dua request checkout untuk produk yang sama dikirim dalam waktu yang hampir bersamaan, kedua request berhasil diproses dan menghasilkan dua pesanan yang valid meskipun stok produk hanya tersedia satu.

Berikut adalah detail hasil pengujian:

| Request | Waktu Pengiriman | Status Response | Stok Sebelum | Stok Sesudah |
|---------|------------------|-----------------|--------------|--------------|
| Request 1 | 10:00:00.123 | 201 Created | 1 | 0 |
| Request 2 | 10:00:00.456 | 201 Created | 1 (seharusnya 0) | -1 (melebihi stok) |

[Screenshot: Request Checkout Simultan]

**Gambar 4.2** Dua request checkout yang dikirim secara simultan melalui Burp Suite Intruder. Kedua request berhasil mendapatkan respons sukses (201 Created).

**Analisis**

Race condition terjadi karena proses pemeriksaan stok dan pengurangan stok tidak dilakukan dalam satu transaksi database yang atomik. Kemungkinan alur eksekusi yang terjadi adalah sebagai berikut:

1. Request 1 masuk dan sistem membaca stok produk (stok = 1).
2. Sistem memeriksa apakah stok mencukupi untuk pesanan (stok 1 >= jumlah 1, valid).
3. Sebelum Request 1 selesai mengurangi stok, Request 2 masuk.
4. Request 2 juga membaca stok produk. Karena Request 1 belum mengurangi stok, Request 2 masih melihat stok = 1.
5. Request 2 juga memeriksa dan mendapati stok mencukupi.
6. Kedua request kemudian mengurangi stok secara terpisah, sehingga stok menjadi -1.

Alur ini menunjukkan tidak adanya mekanisme pessimistic locking atau queue processing pada proses checkout. Laravel sebenarnya menyediakan fitur database transaction dan pessimistic locking melalui metode `lockForUpdate()` dan `sharedLock()`, namun fitur tersebut tampaknya tidak digunakan pada implementasi checkout.

[Screenshot: Hasil Race Condition Stok Berkurang Ganda]

**Gambar 4.3** Tampilan dashboard admin yang menunjukkan stok produk menjadi -1 setelah dua pesanan berhasil dibuat secara simultan untuk produk yang sama.

**Tabel 4.1 Hasil Pengujian Race Condition**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Race Condition pada Checkout |
| Endpoint | POST /checkout |
| Parameter | product_id, quantity, shipping_address |
| Prekondisi | Stok produk = 1, dua request simultan |
| Hasil | Dua pesanan berhasil dibuat, stok menjadi -1 |
| Severity | High |
| Status | Belum diperbaiki |

**Dampak**

Dampak dari kerentanan race condition ini sangat signifikan terutama dalam skenario stok terbatas atau flash sale. Penyerang dapat memesan produk dalam jumlah yang melebihi stok yang tersedia, menyebabkan kerugian finansial bagi penjual dan ketidakpuasan bagi pembeli lain yang gagal mendapatkan produk. Dalam skenario yang lebih ekstrem, penyerang dapat menguras stok produk secara tidak wajar dan menjualnya kembali dengan harga lebih tinggi.

**Usulan Perbaikan**

Untuk mencegah race condition, disarankan untuk menerapkan pessimistic locking menggunakan metode `lockForUpdate()` pada query pengambilan data stok produk. Berikut adalah contoh implementasi yang direkomendasikan:

```php
DB::transaction(function () use ($productId, $quantity) {
    $product = Product::where('id', $productId)
                      ->lockForUpdate()
                      ->first();
    
    if ($product->stock < $quantity) {
        throw new \Exception('Stok tidak mencukupi');
    }
    
    $product->decrement('stock', $quantity);
    // Buat pesanan
});
```

Selain pessimistic locking, alternatif lain yang dapat dipertimbangkan adalah:

1. **Queue-based Processing**: Menggunakan job queue (Laravel Queue) untuk memproses pesanan secara berurutan.
2. **Atomic Database Operations**: Menggunakan query atomik seperti `UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?` yang menjamin konsistensi data.
3. **Redis Locking**: Menggunakan Redis distributed lock untuk mengunci resource selama proses checkout.
4. **Version-based Optimistic Locking**: Menambahkan kolom version pada tabel products dan memeriksa version saat update.

### 4.3 Skenario 2: Multiple Coupon Usage

**Latar Belakang**

Sistem kupon diskon merupakan fitur yang umum digunakan dalam aplikasi e-commerce untuk menarik pelanggan dan meningkatkan penjualan. Namun, jika tidak diimplementasikan dengan benar, sistem kupon dapat menjadi celah yang memungkinkan pengguna mendapatkan diskon secara tidak semestinya. Salah satu celah yang sering ditemukan adalah kemampuan untuk menggunakan kupon yang sama berkali-kali oleh pengguna yang sama.

**Langkah-langkah Pengujian**

1. **Persiapan**: Pastikan terdapat kupon diskon aktif di sistem (sebagai admin, buat kupon dengan kode "DISKON10" yang memberikan diskon 10%). Login sebagai pembeli dan tambahkan produk ke keranjang belanja.

2. **Apply Kupon**: Pada halaman checkout, masukkan kode kupon "DISKON10" dan terapkan. Perhatikan bahwa total belanja berkurang sesuai dengan diskon yang diberikan.

3. **Selesaikan Checkout**: Lanjutkan proses checkout hingga pesanan berhasil dibuat.

4. **Pesan Baru**: Buat pesanan baru dengan produk yang berbeda dan coba gunakan kupon "DISKON10" kembali.

5. **Pengulangan**: Ulangi langkah 2-4 sebanyak beberapa kali untuk memverifikasi apakah kupon yang sama dapat digunakan berkali-kali.

[Screenshot: Kupon Berhasil Digunakan Berkali-kali]

**Gambar 4.4** Tampilan yang menunjukkan bahwa kupon "DISKON10" berhasil digunakan pada beberapa pesanan yang berbeda oleh pengguna yang sama.

**Hasil Pengujian**

Hasil pengujian menunjukkan bahwa kupon diskon "DISKON10" dapat digunakan berkali-kali oleh pengguna yang sama. Setelah berhasil digunakan pada pesanan pertama, kupon yang sama masih dapat diterapkan pada pesanan-pesanan berikutnya tanpa ada penolakan dari sistem. Tidak ada batasan penggunaan per pengguna atau per kupon yang diterapkan.

**Tabel 4.2 Hasil Pengujian Multiple Coupon Usage**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Multiple Coupon Usage |
| Endpoint | POST /apply-coupon |
| Parameter | coupon_code, order_id |
| Prekondisi | Kupon aktif dengan diskon 10% |
| Hasil | Kupon dapat digunakan berkali-kali oleh pengguna yang sama |
| Severity | Medium |
| Status | Belum diperbaiki |

**Analisis**

Berdasarkan hasil pengujian, dapat disimpulkan bahwa aplikasi SHYNESv2 tidak memiliki mekanisme pencatatan penggunaan kupon per pengguna. Sistem hanya memvalidasi:

1. Apakah kupon masih dalam periode berlaku (tanggal awal dan akhir).
2. Apakah total belanja memenuhi minimum pembelian yang ditentukan.
3. Apakah kupon masih aktif (status enabled).

Namun, sistem tidak memeriksa:

1. Apakah kupon sudah pernah digunakan oleh pengguna yang sama sebelumnya.
2. Berapa kali maksimal kupon dapat digunakan (usage limit).
3. Apakah kupon dibatasi untuk satu kali penggunaan per pengguna.

Ketiadaan validasi ini memungkinkan pengguna untuk mendapatkan diskon secara berulang tanpa batas, yang dapat menyebabkan kerugian finansial bagi pemilik toko. Dalam skenario di mana kupon memiliki diskon yang besar (misalnya 50% atau 100%), kerugian dapat menjadi sangat signifikan.

**Dampak**

Dampak dari kerentanan multiple coupon usage termasuk:

1. **Kerugian Finansial**: Pemilik toko kehilangan pendapatan karena diskon diberikan secara berulang tanpa batas.
2. **Penyalahgunaan Promosi**: Tujuan promosi untuk menarik pelanggan baru menjadi tidak efektif karena pelanggan lama dapat terus menggunakan kupon yang sama.
3. **Eksploitasi Sistematis**: Penyerang dapat membuat banyak akun dan menggunakan kupon yang sama secara berulang untuk mendapatkan keuntungan maksimal.

**Usulan Perbaikan**

Untuk mencegah penyalahgunaan kupon, disarankan untuk menambahkan kolom `used_by` pada tabel coupons atau membuat tabel pivot `coupon_user` yang mencatat setiap penggunaan kupon. Berikut adalah rekomendasi implementasi:

1. **Tambah Struktur Database**:
   ```sql
   -- Opsi 1: Tambah kolom usage_limit pada tabel coupons
   ALTER TABLE coupons ADD COLUMN usage_limit INT DEFAULT 1;
   ALTER TABLE coupons ADD COLUMN used_count INT DEFAULT 0;
   
   -- Opsi 2: Buat tabel pivot
   CREATE TABLE coupon_usages (
       id BIGSERIAL PRIMARY KEY,
       coupon_id BIGINT NOT NULL,
       user_id BIGINT NOT NULL,
       order_id BIGINT NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (coupon_id) REFERENCES coupons(id),
       FOREIGN KEY (user_id) REFERENCES users(id),
       FOREIGN KEY (order_id) REFERENCES orders(id)
   );
   ```

2. **Validasi di Controller**:
   ```php
   $coupon = Coupon::findOrFail($request->coupon_id);
   
   // Cek usage limit
   if ($coupon->used_count >= $coupon->usage_limit) {
       return response()->json(['error' => 'Kupon sudah mencapai batas penggunaan'], 400);
   }
   
   // Cek apakah user sudah pernah menggunakan kupon ini
   $alreadyUsed = CouponUsage::where('coupon_id', $coupon->id)
       ->where('user_id', auth()->id())
       ->exists();
       
   if ($alreadyUsed) {
       return response()->json(['error' => 'Anda sudah menggunakan kupon ini'], 400);
   }
   ```

3. **Aturan Bisnis Tambahan**:
   - Terapkan masa berlaku kupon yang jelas (tanggal mulai dan berakhir).
   - Batasi jumlah penggunaan total kupon.
   - Batasi penggunaan per pengguna (misalnya sekali per akun).
   - Log semua penggunaan kupon untuk audit trail.

### 4.4 Skenario 3: Order Status Manipulation

**Latar Belakang**

Manipulasi status pesanan merupakan salah satu bentuk serangan Business Logic di mana pengguna mencoba mengubah status pesanan secara tidak sah. Dalam aplikasi e-commerce, status pesanan mengikuti alur yang telah ditentukan, misalnya: pending -> processing -> shipped -> completed. Perubahan status yang tidak sah dapat menyebabkan berbagai masalah, seperti pengguna menandai pesanan sebagai "completed" tanpa benar-benar menerima barang.

**Langkah-langkah Pengujian**

1. **Login sebagai Pembeli**: Login ke aplikasi menggunakan akun pembeli (pembeli@test.com) dan buka halaman daftar pesanan. Identifikasi salah satu pesanan dengan status "pending".

2. **Intercept Request**: Aktifkan Burp Suite proxy dan intercept request saat halaman pesanan dimuat. Perhatikan request dan response yang terkait dengan data pesanan.

3. **Modifikasi Request**: Coba kirim request untuk mengubah status pesanan secara langsung. Karena tidak ada tombol untuk mengubah status pada antarmuka pembeli, coba kirim request PUT/PATCH ke endpoint yang biasanya digunakan untuk update status.

4. **Test Endpoint**: Coba akses berbagai endpoint yang mungkin digunakan untuk update status, seperti `/orders/{id}/status`, `/admin/orders/{id}/update-status`, atau `/orders/{id}/update`.

[Screenshot: Intercept Request Update Status Order]

**Gambar 4.5** Percobaan untuk mengirim request perubahan status pesanan melalui Burp Suite Repeater.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, ditemukan bahwa aplikasi SHYNESv2 telah memiliki proteksi yang memadai terhadap manipulasi status pesanan. Ketika pengguna pembeli mencoba mengakses endpoint untuk mengubah status, sistem mengembalikan response 403 Forbidden atau 404 Not Found. Perubahan status pesanan hanya dapat dilakukan melalui dashboard admin yang memiliki middleware role checking.

**Tabel 4.3 Hasil Pengujian Order Status Manipulation**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Order Status Manipulation |
| Endpoint | PUT /admin/orders/{id}/status |
| Parameter | status (pending, processing, shipped, completed) |
| Prekondisi | Login sebagai pembeli, pesanan dengan status pending |
| Hasil | 403 Forbidden - hanya admin yang dapat mengubah status |
| Severity | Low (tidak rentan) |
| Status | **Aman** - sudah ada proteksi |

**Analisis**

Aplikasi SHYNESv2 telah menerapkan middleware `admin` atau `role:admin` pada route yang menangani perubahan status pesanan. Middleware ini akan memeriksa peran pengguna yang sedang login sebelum mengizinkan akses ke fungsi tersebut. Jika pengguna bukan admin, request akan ditolak dengan response 403 Forbidden.

Selain itu, pemeriksaan juga dilakukan di level controller untuk memastikan bahwa hanya pengguna dengan peran admin yang dapat mengubah status pesanan. Implementasi ini menunjukkan bahwa pengembang telah menyadari risiko manipulasi status dan telah menerapkan kontrol akses yang tepat.

Meskipun demikian, terdapat beberapa rekomendasi tambahan yang dapat dipertimbangkan:

1. **Validasi Alur Status**: Pastikan bahwa status hanya dapat berubah secara berurutan (misalnya dari "pending" ke "processing", bukan dari "pending" langsung ke "completed").
2. **Logging**: Catat setiap perubahan status beserta informasi pengguna yang melakukan perubahan untuk audit trail.
3. **Notifikasi**: Kirim notifikasi kepada pembeli ketika status pesanan berubah.
4. **Timestamp**: Simpan timestamp untuk setiap perubahan status.

**Status: Aman**

Endpoint perubahan status pesanan telah dilindungi dengan baik oleh middleware role checking dan tidak dapat diakses oleh pengguna biasa. Oleh karena itu, kerentanan ini dinyatakan **aman**.

---

## BAB V: PENGUJIAN IDOR (INSECURE DIRECT OBJECT REFERENCE)

### 5.1 Tujuan Pengujian

Pengujian Insecure Direct Object Reference (IDOR) bertujuan untuk mengidentifikasi apakah aplikasi SHYNESv2 memiliki celah yang memungkinkan pengguna mengakses, memodifikasi, atau menghapus data milik pengguna lain tanpa otorisasi yang sah. IDOR merupakan salah satu jenis kerentanan Broken Access Control yang paling sering ditemukan pada aplikasi web dan memiliki dampak yang signifikan terhadap kerahasiaan dan integritas data.

Tujuan spesifik dari pengujian IDOR meliputi:

1. Memverifikasi apakah pengguna dapat mengakses data pesanan milik pengguna lain melalui manipulasi parameter ID.
2. Menguji apakah supplier dapat mengakses kontrak milik supplier lain.
3. Mengidentifikasi apakah terdapat celah IDOR pada fitur POS checkout yang memungkinkan pembuatan pesanan atas nama pengguna lain.
4. Memberikan rekomendasi perbaikan untuk setiap celah IDOR yang ditemukan.

### 5.2 Skenario 1: Akses Order Milik User Lain

**Latar Belakang**

Dalam aplikasi e-commerce, data pesanan merupakan informasi yang bersifat privat dan hanya boleh diakses oleh pengguna yang membuat pesanan tersebut, admin, atau pihak lain yang memiliki otorisasi. IDOR pada data pesanan dapat menyebabkan kebocoran informasi pribadi seperti alamat pengiriman, metode pembayaran, dan detail produk yang dibeli.

**Langkah-langkah Pengujian**

1. **Login sebagai Pembeli A**: Login ke aplikasi menggunakan akun pembeli (pembeli@test.com). Lakukan pembelian produk dan catat order_id yang diperoleh. Misalnya, order_id = 5.

2. **Akses Halaman Order**: Buka halaman detail pesanan untuk order_id = 5 melalui URL `/orders/5`. Pastikan halaman dapat diakses dan menampilkan data pesanan dengan benar.

3. **Logout dan Login sebagai Pembeli B**: Logout dari akun pembeli A. Login menggunakan akun pembeli lain (jika tersedia) atau gunakan akun supplier untuk menguji akses dari peran yang berbeda.

4. **Akses Order Milik Pembeli A**: Coba akses URL `/orders/5` menggunakan akun pembeli B. Perhatikan respons yang diberikan oleh server.

5. **Variasi Endpoint**: Coba variasi endpoint lain seperti `/api/orders/5`, `/orders/5/details`, `/invoice/5`, atau `/orders/5/edit` untuk mengidentifikasi celah IDOR pada endpoint yang berbeda.

[Screenshot: IDOR Order Test Request]

**Gambar 5.1** Percobaan akses order milik pengguna lain melalui URL `/orders/5` dengan menggunakan akun yang berbeda.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 telah menerapkan proteksi IDOR pada data pesanan. Ketika pengguna pembeli B mencoba mengakses order_id = 5 yang merupakan milik pembeli A, sistem menolak akses dan mengembalikan response 403 Forbidden atau 404 Not Found.

[Screenshot: Response 403 IDOR Order]

**Gambar 5.2** Response 403 Forbidden yang ditampilkan ketika pengguna mencoba mengakses pesanan milik pengguna lain.

**Tabel 5.1 Hasil Pengujian IDOR Order**

| Aspek | Detail |
|-------|--------|
| Kerentanan | IDOR Order |
| Endpoint | GET /orders/{id} |
| Parameter | id (order_id) |
| Prekondisi | Login sebagai pembeli B, order_id = 5 milik pembeli A |
| Hasil | 403 Forbidden - tidak dapat mengakses |
| Severity | Low (tidak rentan) |
| Status | **Aman** |

**Analisis**

Proteksi IDOR pada data pesanan diimplementasikan melalui pengecekan kepemilikan data di OrderController. Berdasarkan respons yang diterima, dapat disimpulkan bahwa controller memeriksa apakah `order->user_id === auth()->id()` sebelum menampilkan data pesanan. Jika tidak sesuai, maka akses ditolak.

Kode yang mungkin diimplementasikan adalah sebagai berikut:

```php
public function show($id)
{
    $order = Order::findOrFail($id);
    
    // Proteksi IDOR
    if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
        abort(403, 'Unauthorized access');
    }
    
    return view('orders.show', compact('order'));
}
```

Implementasi ini sudah sesuai dengan prinsip least privilege dan data ownership verification. Pengguna hanya dapat mengakses data pesanan yang memang menjadi miliknya, kecuali jika pengguna tersebut memiliki peran admin.

**Status: Aman**

Endpoint data pesanan telah dilindungi dengan baik melalui pengecekan kepemilikan data. Oleh karena itu, IDOR pada data pesanan dinyatakan **aman**.

### 5.3 Skenario 2: Akses Kontrak Milik Supplier Lain

**Latar Belakang**

Fitur kontrak supplier memungkinkan admin untuk membuat dan mengelola perjanjian dengan supplier. Kontrak ini berisi informasi sensitif seperti harga khusus, jangka waktu kerja sama, dan ketentuan bisnis lainnya. Jika supplier dapat mengakses kontrak milik supplier lain, hal ini dapat menyebabkan kebocoran informasi bisnis yang strategis.

**Langkah-langkah Pengujian**

1. **Login sebagai Supplier A**: Login ke aplikasi menggunakan akun supplier (supplier@test.com). Buka halaman daftar kontrak dan catat kontrak_id yang dimiliki. Misalnya, kontrak_id = 3.

2. **Akses Halaman Kontrak**: Buka halaman detail kontrak untuk kontrak_id = 3. Verifikasi bahwa halaman dapat diakses dan menampilkan data kontrak yang benar.

3. **Coba Akses Kontrak Lain**: Coba akses kontrak dengan ID yang berbeda, misalnya kontrak_id = 1, 2, 4, atau 5. Perhatikan respons yang diberikan oleh server.

4. **Manipulasi URL**: Coba berbagai format URL seperti `/admin/contracts/1`, `/contracts/1`, `/supplier/contracts/1`, atau `/api/contracts/1`.

[Screenshot: IDOR Contract Test via Browser]

**Gambar 5.3** Percobaan akses kontrak milik supplier lain dengan memanipulasi ID pada URL.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 telah menerapkan tenant isolation yang memadai pada data kontrak. Supplier hanya dapat melihat kontrak yang dimiliki dan tidak dapat mengakses kontrak milik supplier lain. Ketika supplier A mencoba mengakses kontrak milik supplier B, sistem mengembalikan response 403 Forbidden atau 404 Not Found.

**Tabel 5.2 Hasil Pengujian IDOR Contract**

| Aspek | Detail |
|-------|--------|
| Kerentanan | IDOR Contract |
| Endpoint | GET /admin/contracts/{id} |
| Parameter | id (contract_id) |
| Prekondisi | Login sebagai supplier A, contract_id milik supplier B |
| Hasil | 403 Forbidden - tenant isolation berfungsi |
| Severity | Low (tidak rentan) |
| Status | **Aman** |

**Analisis**

Proteksi IDOR pada data kontrak diimplementasikan melalui dua mekanisme:

1. **Tenant Middleware**: Middleware yang membatasi akses pengguna hanya pada data yang terkait dengan tenant (supplier) mereka.
2. **Global Scope**: Laravel Global Scope yang secara otomatis menambahkan kondisi `supplier_id = auth()->user()->supplier_id` pada setiap query yang melibatkan tabel contracts.

Implementasi tenant isolation ini merupakan praktik yang baik untuk aplikasi multi-tenant. Dengan Global Scope, pengembang tidak perlu secara manual menambahkan kondisi filter pada setiap query, sehingga mengurangi risiko kelupaan yang dapat menyebabkan celah IDOR.

Kode yang mungkin diimplementasikan adalah sebagai berikut:

```php
// Global Scope
class SupplierScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $builder->where('supplier_id', auth()->user()->supplier_id);
        }
    }
}

// Model Contract
class Contract extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new SupplierScope);
    }
}
```

**Status: Aman**

Tenant isolation pada data kontrak berfungsi dengan baik. Supplier tidak dapat mengakses kontrak milik supplier lain. IDOR pada kontrak dinyatakan **aman**.

### 5.4 Skenario 3: Manipulasi user_id pada POS Checkout

**Latar Belakang**

Fitur POS (Point of Sale) pada aplikasi SHYNESv2 memungkinkan admin untuk membuat pesanan atas nama pelanggan yang datang langsung ke toko. Dalam proses ini, admin memilih pelanggan dari daftar dan sistem akan membuat pesanan dengan user_id yang sesuai. Jika tidak ada validasi yang memadai, admin nakal dapat memanipulasi user_id untuk membuat pesanan atas nama pelanggan lain tanpa sepengetahuan mereka.

**Langkah-langkah Pengujian**

1. **Login sebagai Admin**: Login ke aplikasi menggunakan akun admin (admin@gmail.com). Buka halaman POS checkout.

2. **Intercept Request**: Konfigurasi Burp Suite sebagai proxy dan aktifkan intercept. Lakukan proses POS checkout dengan memilih pelanggan tertentu. Tangkap request POST yang dikirim.

3. **Modifikasi user_id**: Pada request yang tertangkap, ubah nilai parameter `user_id` menjadi ID pengguna lain yang berbeda dari pelanggan yang dipilih. Misalnya, jika memilih pelanggan dengan user_id = 3, ubah menjadi user_id = 5.

4. **Kirim Request**: Kirim request yang telah dimodifikasi dan perhatikan respons yang diberikan oleh server.

[Screenshot: Manipulasi user_id pada POS Checkout]

**Gambar 5.4** Manipulasi parameter user_id pada request POST POS checkout melalui Burp Suite Intercept.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, ditemukan bahwa aplikasi SHYNESv2 **tidak melakukan validasi** terhadap parameter `user_id` pada fitur POS checkout. Ketika nilai `user_id` diubah menjadi ID pengguna lain, sistem berhasil memproses checkout dan membuat pesanan atas nama pengguna dengan ID tersebut, tanpa memverifikasi apakah ID tersebut sesuai dengan data pelanggan yang dipilih.

**Tabel 5.3 Hasil Pengujian IDOR POS Checkout**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Manipulasi user_id pada POS Checkout |
| Endpoint | POST /admin/pos/checkout |
| Parameter | user_id, customer_name, customer_phone, products |
| Prekondisi | Login sebagai admin, melakukan POS checkout |
| Hasil | Pesanan berhasil dibuat dengan user_id yang dimanipulasi |
| Severity | Medium |
| Status | Belum diperbaiki |

**Analisis**

Kerentanan ini terjadi karena sistem tidak melakukan validasi bahwa `user_id` yang dikirim sesuai dengan data `customer_name` dan `customer_phone` yang juga dikirim dalam request. Admin dapat dengan mudah mengubah `user_id` menjadi ID pengguna lain dan pesanan akan tercatat atas nama pengguna tersebut.

Implikasi dari kerentanan ini meliputi:

1. **Pembuatan Pesanan Palsu**: Admin nakal dapat membuat pesanan palsu atas nama pengguna lain.
2. **Manipulasi Reputasi**: Pesanan yang tidak sah dapat memengaruhi riwayat pembelian dan reputasi pengguna.
3. **Penyalahgunaan Kupon atau Poin**: Jika sistem memiliki program loyalitas, admin nakal dapat menggunakan poin atau kredit milik pengguna lain.

**Usulan Perbaikan**

Untuk mengatasi kerentanan ini, disarankan untuk melakukan validasi bahwa `user_id` yang dikirim sesuai dengan data pelanggan yang dipilih. Berikut adalah beberapa pendekatan yang dapat diterapkan:

1. **Validasi Backend**:
   ```php
   public function posCheckout(Request $request)
   {
       $validated = $request->validate([
           'user_id' => 'required|exists:users,id',
           'customer_name' => 'required|string',
           'customer_phone' => 'required|string',
           'products' => 'required|array',
       ]);
       
       // Validasi bahwa user_id sesuai dengan customer_name
       $user = User::findOrFail($validated['user_id']);
       
       if ($user->name !== $validated['customer_name'] || 
           $user->phone !== $validated['customer_phone']) {
           return response()->json([
               'error' => 'Data pelanggan tidak sesuai'
           ], 422);
       }
       
       // Lanjutkan proses checkout
   }
   ```

2. **Menggunakan Customer ID dari Database**: Alih-alih menerima `user_id`, `customer_name`, dan `customer_phone` secara terpisah, gunakan hanya ID pelanggan yang dipilih dari database dan ambil data lainnya langsung dari database.

3. **Server-side Customer Selection**: Pilihan pelanggan dilakukan di sisi server berdasarkan session atau token yang tidak dapat dimanipulasi oleh client.

4. **Audit Log**: Catat setiap aktivitas POS checkout beserta informasi admin yang melakukan, termasuk timestamp dan data lengkap transaksi.

---

## BAB VI: PENGUJIAN OTENTIKASI & MANAJEMEN SESI

### 6.1 Tujuan Pengujian

Pengujian otentikasi dan manajemen sesi bertujuan untuk mengevaluasi keamanan mekanisme login, pengelolaan sesi pengguna, dan kebijakan password pada aplikasi SHYNESv2. Area ini termasuk dalam kategori A07:2021 Identification and Authentication Failures menurut OWASP Top 10 2021.

Tujuan spesifik dari pengujian ini meliputi:

1. Menguji ketahanan aplikasi terhadap serangan brute force login.
2. Memverifikasi apakah aplikasi rentan terhadap session fixation.
3. Mengevaluasi kebijakan session timeout pada aplikasi.
4. Menguji kebijakan password yang diterapkan.
5. Memberikan rekomendasi perbaikan untuk setiap kelemahan yang ditemukan.

### 6.2 Skenario 1: Brute Force Login

**Latar Belakang**

Serangan brute force merupakan salah satu metode yang paling umum digunakan untuk mendapatkan akses tidak sah ke akun pengguna. Serangan ini dilakukan dengan mencoba berbagai kombinasi username dan password secara sistematis hingga menemukan kombinasi yang tepat. Tanpa adanya mekanisme pembatasan seperti rate limiting, account lockout, atau CAPTCHA, aplikasi web menjadi sangat rentan terhadap serangan ini.

**Langkah-langkah Pengujian**

1. **Persiapan Wordlist**: Siapkan wordlist yang berisi daftar password yang mungkin digunakan. Untuk pengujian, gunakan wordlist umum seperti SecLists atau buat wordlist sederhana yang berisi password umum.

2. **Konfigurasi Burp Intruder**: Kirim request login normal ke aplikasi dan tangkap menggunakan Burp Suite. Kirim request ke Intruder dan konfigurasi posisi payload pada parameter password.

3. **Set Payload**: Masukkan wordlist sebagai payload pada Burp Intruder. Atur option untuk menangani response yang berbeda (misalnya, panjang response atau kode status) untuk mengidentifikasi login yang berhasil.

4. **Jalankan Serangan**: Jalankan Intruder dan amati response yang diterima. Perhatikan perbedaan panjang response atau kode status yang mengindikasikan login berhasil.

5. **Verifikasi**: Jika ada response yang mencurigakan, verifikasi dengan login manual menggunakan kombinasi username dan password tersebut.

[Screenshot: Burp Intruder Brute Force Configuration]

**Gambar 6.1** Konfigurasi Burp Suite Intruder untuk serangan brute force login. Parameter password ditandai sebagai posisi payload.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 **tidak memiliki mekanisme rate limiting atau account lockout** pada endpoint login. Serangan brute force dapat dilakukan tanpa hambatan, dan tidak ada pembatasan jumlah percobaan login yang gagal.

Berikut adalah detail hasil pengujian:

| Jumlah Percobaan | Waktu Total | Response Setiap Percobaan | Pembatasan |
|------------------|-------------|--------------------------|------------|
| 100 percobaan | ~5 detik | 200 OK (gagal) / 302 Redirect (berhasil) | Tidak ada |
| 500 percobaan | ~25 detik | 200 OK (gagal) / 302 Redirect (berhasil) | Tidak ada |
| 1000 percobaan | ~50 detik | 200 OK (gagal) / 302 Redirect (berhasil) | Tidak ada |

[Screenshot: Hasil Brute Force Login Attempt]

**Gambar 6.2** Hasil pengujian brute force login menggunakan Burp Suite Intruder. Tidak ada pembatasan yang terdeteksi.

**Tabel 6.1 Hasil Brute Force Login**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Brute Force Login |
| Endpoint | POST /login |
| Parameter | email, password |
| Prekondisi | Akun target diketahui emailnya |
| Hasil | Tidak ada rate limiting atau account lockout |
| Severity | High |
| Status | Belum diperbaiki |

**Analisis**

Ketiadaan rate limiting pada endpoint login membuka peluang bagi penyerang untuk melakukan serangan brute force secara efektif. Dengan wordlist yang berkualitas dan koneksi internet yang memadai, penyerang dapat mencoba ribuan kombinasi password dalam waktu singkat.

Beberapa faktor yang memperparah kerentanan ini:

1. **Tidak Ada Captcha**: Tidak ada mekanisme captcha atau challenge-response yang memisahkan manusia dari bot.
2. **Tidak Ada Account Lockout**: Akun tidak terkunci setelah beberapa kali percobaan login gagal.
3. **Tidak Ada Progressive Delay**: Tidak ada penundaan yang meningkat setelah setiap percobaan gagal.
4. **Error Message Tidak Spesifik**: Meskipun tidak diuji secara detail, error message yang membedakan antara "user tidak ditemukan" dan "password salah" dapat membantu penyerang memvalidasi keberadaan akun.

**Dampak**

Dampak dari kerentanan brute force sangat serius:

1. **Pengambilalihan Akun**: Penyerang dapat memperoleh akses ke akun pengguna, terutama jika pengguna menggunakan password yang lemah atau umum.
2. **Akses ke Data Sensitif**: Setelah berhasil login, penyerang dapat mengakses data pribadi korban, riwayat transaksi, dan informasi lainnya.
3. **Transaksi Ilegal**: Penyerang dapat melakukan transaksi menggunakan akun korban.
4. **Privilege Escalation**: Jika akun admin berhasil dibrute force, penyerang mendapatkan kendali penuh atas aplikasi.

**Usulan Perbaikan**

1. **Implementasi Rate Limiting dengan Middleware Throttle**:
   ```php
   // routes/web.php
   Route::post('/login', [AuthController::class, 'login'])
       ->middleware('throttle:5,1'); // 5 percobaan per 1 menit
   ```

2. **Account Lockout**:
   ```php
   public function login(Request $request)
   {
       // Cek apakah akun terkunci
       if (Cache::get('lockout_' . $request->email) >= 5) {
           return back()->withErrors([
               'email' => 'Akun telah terkunci. Silakan coba lagi dalam 15 menit.',
           ]);
       }
       
       // Proses login
       if (Auth::attempt($request->only('email', 'password'))) {
           // Reset lockout counter
           Cache::forget('lockout_' . $request->email);
           return redirect()->intended('/');
       }
       
       // Increment lockout counter
       $attempts = Cache::increment('lockout_' . $request->email, 1);
       Cache::put('lockout_' . $request->email, $attempts, now()->addMinutes(15));
       
       return back()->withErrors([
           'email' => 'Kredensial tidak valid.',
       ]);
   }
   ```

3. **Implementasi CAPTCHA**: Tambahkan Google reCAPTCHA atau hCaptcha pada halaman login setelah beberapa kali percobaan gagal.

4. **Two-Factor Authentication (2FA)**: Implementasi 2FA untuk lapisan keamanan tambahan.

5. **Logging dan Monitoring**: Catat setiap percobaan login (berhasil dan gagal) dan berikan notifikasi untuk aktivitas mencurigakan.

### 6.3 Skenario 2: Session Fixation

**Latar Belakang**

Session fixation adalah serangan di mana penyerang memaksa korban untuk menggunakan session ID yang sudah diketahui oleh penyerang. Setelah korban berhasil login dengan session ID tersebut, penyerapan dapat menggunakan session ID yang sama untuk mengakses akun korban tanpa perlu mengetahui kredensial login. Serangan ini dapat dicegah dengan melakukan regenerasi session ID setelah pengguna berhasil login.

**Langkah-langkah Pengujian**

1. **Dapatkan Session ID Sebelum Login**: Kunjungi halaman login aplikasi SHYNESv2. Catat session ID yang diberikan oleh server melalui cookie. Pada Laravel, session cookie biasanya bernama `shynesv2_session` atau `laravel_session`.

2. **Set Session ID**: Sebelum login, set session ID yang telah didapatkan ke dalam cookie browser.

3. **Login**: Lakukan login menggunakan kredensial yang valid.

4. **Periksa Session ID Setelah Login**: Setelah berhasil login, periksa kembali session ID yang ada pada cookie. Bandingkan dengan session ID sebelum login.

[Screenshot: Session ID Before and After Login]

**Gambar 6.3** Perbandingan session ID sebelum login (kiri) dan setelah login (kanan). Session ID berubah setelah login berhasil.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 **telah menerapkan regenerasi session ID** setelah pengguna berhasil login. Session ID sebelum login berbeda dengan session ID setelah login, sehingga serangan session fixation tidak dapat dilakukan.

**Tabel 6.2 Hasil Session Fixation Test**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Session Fixation |
| Endpoint | POST /login |
| Parameter | email, password |
| Prekondisi | Session ID ditetapkan sebelum login |
| Hasil | Session ID berubah setelah login (regenerated) |
| Severity | Low (tidak rentan) |
| Status | **Aman** |

**Analisis**

Laravel secara default melakukan regenerasi session ID setelah pengguna berhasil login melalui method `regenerate()` pada session. Hal ini dilakukan oleh trait `AuthenticatesUsers` yang digunakan oleh Laravel Breeze, Jetstream, atau Fortify.

Kode yang dijalankan secara internal oleh Laravel:

```php
// Illuminate\\Foundation\\Auth\\AuthenticatesUsers
protected function authenticated(Request $request, $user)
{
    $request->session()->regenerate();
    // ...
}
```

Regenerasi session ID memastikan bahwa session ID yang digunakan sebelum login (yang mungkin telah diketahui oleh penyerang) menjadi tidak valid setelah login. Penyerang yang telah menetapkan session ID tertentu pada korban tidak akan dapat mengakses sesi korban setelah login karena session ID telah berubah.

**Status: Aman**

Laravel telah menangani session fixation secara otomatis melalui regenerasi session ID setelah login. Oleh karena itu, kerentanan session fixation dinyatakan **aman**.

### 6.4 Skenario 3: Session Timeout

**Latar Belakang**

Session timeout adalah mekanisme keamanan yang secara otomatis mengakhiri sesi pengguna setelah periode ketidakaktifan tertentu. Tanpa session timeout yang memadai, sesi yang tidak digunakan tetap aktif dan berpotensi disalahgunakan oleh pihak yang tidak berwenang. Risiko ini semakin besar jika pengguna mengakses aplikasi dari perangkat yang digunakan bersama atau dari jaringan publik.

**Langkah-langkah Pengujian**

1. **Login**: Login ke aplikasi SHYNESv2 menggunakan akun pembeli.

2. **Catat Waktu**: Catat waktu login secara detail.

3. **Idle**: Biarkan sesi tidak aktif (tanpa interaksi) selama 30 menit.

4. **Coba Akses**: Setelah 30 menit, coba akses halaman yang memerlukan otentikasi (misalnya halaman profil atau keranjang belanja).

[Screenshot: Session Active After 30 Minutes Idle]

**Gambar 6.4** Tampilan yang menunjukkan bahwa sesi masih aktif setelah 30 menit tidak ada aktivitas. Halaman profil masih dapat diakses tanpa diminta login ulang.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 **tidak memiliki mekanisme idle session timeout**. Setelah 30 menit tidak ada aktivitas, sesi masih tetap aktif dan pengguna masih dapat mengakses halaman yang memerlukan otentikasi tanpa diminta login ulang.

**Tabel 6.3 Hasil Session Timeout Test**

| Aspek | Detail |
|-------|--------|
| Kerentanan | No Idle Session Timeout |
| Endpoint | Semua endpoint yang memerlukan otentikasi |
| Parameter | - |
| Prekondisi | Login dan biarkan idle 30 menit |
| Hasil | Session masih aktif setelah 30 menit idle |
| Severity | Medium |
| Status | Belum diperbaiki |

**Analisis**

Konfigurasi session lifetime pada aplikasi SHYNESv2 menggunakan nilai default Laravel yang cukup panjang. Berdasarkan hasil pengujian, session lifetime diatur ke nilai yang besar (mungkin 120 menit atau lebih) dan tidak ada mekanisme idle timeout yang terpisah.

Pada Laravel, session lifetime dikonfigurasi melalui file `config/session.php`:

```php
'expire_on_close' => false, // Session tidak otomatis berakhir saat browser ditutup
'lifetime' => env('SESSION_LIFETIME', 120), // Session berlaku 120 menit sejak dibuat
```

Ketiadaan idle timeout berarti sesi akan tetap berlaku hingga mencapai session lifetime, terlepas dari apakah pengguna aktif atau tidak. Hal ini meningkatkan risiko session hijacking, terutama jika:

1. Pengguna mengakses aplikasi dari perangkat bersama (warnet, perpustakaan).
2. Pengguna lupa logout.
3. Cookie sesi bocor melalui serangan XSS atau jaringan yang tidak aman.

**Dampak**

Dampak dari ketiadaan session timeout meliputi:

1. **Session Hijacking**: Penyerang yang berhasil mencuri cookie sesi memiliki waktu yang lebih lama untuk mengakses akun korban.
2. **Akses Tidak Sah pada Perangkat Bersama**: Pengguna yang lupa logout pada perangkat bersama memberikan akses kepada pengguna perangkat berikutnya.
3. **Resource Wastage**: Session yang tidak pernah berakhir tetap menggunakan resource server.

**Usulan Perbaikan**

1. **Set Session Lifetime**:
   ```php
   // config/session.php
   'lifetime' => env('SESSION_LIFETIME', 120), // 120 menit
   'expire_on_close' => true, // Berakhir saat browser ditutup
   ```

2. **Implementasi Idle Timeout**:
   ```php
   // Middleware IdleTimeout
   class IdleTimeout
   {
       public function handle($request, Closure $next, $timeout = 15)
       {
           if (auth()->check()) {
               $lastActivity = session('last_activity', now());
               
               if (now()->diffInMinutes($lastActivity) > $timeout) {
                   auth()->logout();
                   session()->flush();
                   return redirect('/login')->with('message', 'Sesi berakhir karena tidak ada aktivitas.');
               }
               
               session(['last_activity' => now()]);
           }
           
           return $next($request);
       }
   }
   ```

3. **Implementasi "Remember Me" dengan Bijak**: Gunakan fitur "remember me" secara selektif dan berikan informasi yang jelas kepada pengguna tentang risiko keamanan.

### 6.5 Skenario 4: Password Strength

**Latar Belakang**

Kebijakan password yang lemah merupakan salah satu faktor utama yang menyebabkan keberhasilan serangan brute force dan credential stuffing. Password yang pendek, hanya terdiri dari huruf kecil, atau menggunakan pola yang umum dapat dengan mudah ditebak atau ditemukan melalui serangan dictionary-based. Oleh karena itu, aplikasi web seharusnya menerapkan kebijakan password yang mewajibkan pengguna untuk memilih password yang kuat.

**Langkah-langkah Pengujian**

1. **Buka Halaman Registrasi**: Kunjungi halaman registrasi pengguna baru pada aplikasi SHYNESv2.

2. **Coba Password Lemah**: Isi form registrasi dengan password yang sangat lemah, misalnya "123", "password", "qwerty", atau "admin".

3. **Submit Form**: Kirim form registrasi dan perhatikan respons yang diberikan.

4. **Verifikasi**: Jika registrasi berhasil, coba login dengan password tersebut untuk memastikan bahwa akun benar-benar dibuat dengan password lemah.

[Screenshot: Weak Password Accepted]

**Gambar 6.5** Form registrasi yang menerima password "123" tanpa validasi kekuatan password.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 **tidak menerapkan validasi kekuatan password**. Password dengan panjang 3 karakter ("123") berhasil diterima dan akun berhasil dibuat. Tidak ada validasi minimum panjang, kombinasi huruf besar/kecil, angka, atau karakter khusus.

**Tabel 6.4 Hasil Password Strength Test**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Weak Password Policy |
| Endpoint | POST /register |
| Parameter | name, email, password, password_confirmation |
| Prekondisi | Akses halaman registrasi |
| Hasil | Password "123" diterima tanpa validasi |
| Severity | High |
| Status | Belum diperbaiki |

**Analisis**

Aplikasi SHYNESv2 hanya menerapkan validasi minimal pada field password, yaitu:

1. **Required**: Field password harus diisi.
2. **Confirmed**: Password harus cocok dengan password_confirmation.
3. **Min:8** (atau mungkin tidak ada): Beberapa versi Laravel memiliki default minimum 8 karakter, tetapi berdasarkan hasil pengujian, password 3 karakter berhasil diterima.

Tidak ada validasi tambahan seperti:

1. **Panjang Minimum yang Memadai**: Minimal 8 karakter (standar industri).
2. **Kombinasi Huruf Besar dan Kecil**: Memastikan password tidak hanya huruf kecil.
3. **Angka**: Memastikan password mengandung setidaknya satu angka.
4. **Karakter Khusus**: Memastikan password mengandung karakter khusus seperti !@#$%.
5. **Larangan Password Umum**: Mencegah penggunaan password yang umum atau telah bocor.
6. **Larangan Password Mirip dengan Data Pengguna**: Mencegah penggunaan nama, email, atau informasi pribadi lainnya sebagai password.

**Dampak**

Dampak dari kebijakan password yang lemah meliputi:

1. **Brute Force Lebih Efektif**: Password pendek dan sederhana lebih mudah ditebak melalui brute force.
2. **Credential Stuffing**: Password yang lemah dan umum sering digunakan pada platform lain sehingga rentan terhadap credential stuffing.
3. **Dictionary Attack**: Password yang umum seperti "123456", "password", atau "admin123" mudah ditemukan dalam dictionary attack.
4. **Pengambilalihan Akun**: Kombinasi dengan ketiadaan rate limiting membuat pengambilalihan akun menjadi lebih mudah.

**Usulan Perbaikan**

1. **Validasi Password di Controller**:
   ```php
   $validated = $request->validate([
       'name' => 'required|string|max:255',
       'email' => 'required|email|unique:users',
       'password' => [
           'required',
           'confirmed',
           'min:8',
           'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/',
       ],
   ]);
   ```

2. **Pesan Error yang Informatif**:
   ```php
   'password' => [
       'min' => 'Password minimal harus 8 karakter.',
       'regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
   ]
   ```

3. **Laravel Password Rules**:
   ```bash
   composer require laravel/fortify
   ```
   Laravel Fortify menyediakan aturan password bawaan yang dapat dikustomisasi.

4. **Cek Password Umum**:
   ```php
   use Illuminate\\Validation\\Rules\\Password;
   
   $validated = $request->validate([
       'password' => [
           'required',
           'confirmed',
           Password::min(8)
               ->mixedCase()
               ->letters()
               ->numbers()
               ->symbols()
               ->uncompromised(), // Cek apakah password pernah bocor
       ],
   ]);
   ```

5. **Implementasi di Frontend**: Tambahkan indikator kekuatan password (password strength meter) pada halaman registrasi untuk memberikan umpan balik real-time kepada pengguna.

---

## BAB VII: PENGUJIAN SQL INJECTION & XSS

### 7.1 Tujuan Pengujian

Pengujian SQL Injection dan Cross-Site Scripting (XSS) bertujuan untuk mengevaluasi ketahanan aplikasi SHYNESv2 terhadap serangan injeksi yang termasuk dalam kategori A03:2021 Injection menurut OWASP Top 10 2021. SQL Injection dapat menyebabkan kebocoran data database secara keseluruhan, sementara XSS dapat menyebabkan eksekusi skrip berbahaya di browser korban.

Tujuan spesifik dari pengujian ini meliputi:

1. Menguji apakah parameter URL pada aplikasi rentan terhadap SQL Injection.
2. Menguji apakah fitur pencarian rentan terhadap SQL Injection.
3. Mengidentifikasi celah Reflected XSS pada fitur pencarian.
4. Mengidentifikasi celah Stored XSS pada field input yang datanya ditampilkan kembali.
5. Memvalidasi efektivitas mekanisme keamanan bawaan Laravel (Eloquent ORM dan Blade escaping).

### 7.2 Skenario 1: SQL Injection pada URL Parameter

**Latar Belakang**

Parameter URL merupakan salah satu titik masuk yang paling umum untuk serangan SQL Injection. Parameter seperti `id`, `category_id`, atau `product_id` yang diteruskan melalui URL sering kali digunakan langsung dalam query database tanpa sanitasi yang memadai.

**Langkah-langkah Pengujian**

1. **Identifikasi Endpoint**: Tentukan endpoint yang menerima parameter melalui URL. Contoh endpoint yang diuji adalah `/products?category_id=1`.

2. **Pengujian Manual**: Coba beberapa payload SQL Injection secara manual pada parameter `category_id`:
   - `1' OR '1'='1`
   - `1' OR 1=1 -- -`
   - `1' UNION SELECT null,null,null -- -`
   - `1 AND 1=1` (uji boolean)
   - `1 AND 1=2` (uji boolean)

3. **Pengujian dengan sqlmap**: Jalankan sqlmap untuk mendeteksi SQL Injection secara otomatis:
   ```bash
   sqlmap -u "https://shynesv2.up.railway.app/products?category_id=1" --batch --level=3 --risk=2
   ```

4. **Analisis Hasil**: Perhatikan output sqlmap untuk menentukan apakah parameter rentan terhadap SQL Injection.

[Screenshot: sqlmap Scanning Parameter category_id]

**Gambar 7.1** sqlmap sedang melakukan scanning pada parameter category_id untuk mendeteksi SQL Injection.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, parameter `category_id` pada endpoint `/products` **tidak rentan** terhadap SQL Injection. sqlmap melaporkan bahwa "all tested parameters appear to be not injectable" dan pengujian manual juga tidak menunjukkan perilaku yang mencurigakan.

**Tabel 7.1 Hasil SQL Injection URL Parameter**

| Aspek | Detail |
|-------|--------|
| Kerentanan | SQL Injection pada URL Parameter |
| Endpoint | GET /products?category_id={id} |
| Parameter | category_id |
| Teknik Pengujian | Manual + sqlmap (boolean, time, error, union) |
| Hasil | Tidak rentan - parameter binding berfungsi |
| Severity | Info (tidak rentan) |
| Status | **Aman** |

[Screenshot: sqlmap Result]

**Gambar 7.2** Hasil pengujian sqlmap yang menunjukkan bahwa parameter category_id tidak rentan terhadap SQL Injection.

**Analisis**

Aplikasi SHYNESv2 menggunakan Laravel Eloquent ORM untuk berinteraksi dengan database. Eloquent secara default menggunakan parameter binding (prepared statements) untuk semua query yang dihasilkan. Parameter binding memisahkan query SQL dari data input, sehingga input pengguna tidak dapat mengubah struktur query.

Kode yang mungkin diimplementasikan adalah sebagai berikut:

```php
// Menggunakan Eloquent (aman - parameter binding otomatis)
$products = Product::where('category_id', $request->category_id)->get();

// Atau menggunakan Query Builder (juga aman)
$products = DB::table('products')
                ->where('category_id', $request->category_id)
                ->get();
```

Kedua pendekatan di atas menghasilkan prepared statement di mana nilai `category_id` dikirim secara terpisah dari query SQL, sehingga input apapun yang diberikan oleh pengguna tidak akan mengubah struktur query.

**Status: Aman**

Eloquent ORM dan Query Builder Laravel telah memberikan perlindungan yang memadai terhadap SQL Injection melalui parameter binding. Endpoint URL parameter dinyatakan **aman**.

### 7.3 Skenario 2: SQL Injection pada Search

**Latar Belakang**

Fitur pencarian pada aplikasi web sering kali menggunakan query LIKE untuk mencari data yang cocok dengan kata kunci yang dimasukkan pengguna. Jika query LIKE tidak menggunakan parameter binding, input pengguna dapat dimanipulasi untuk melakukan SQL Injection.

**Langkah-langkah Pengujian**

1. **Buka Halaman Search**: Kunjungi halaman pencarian produk pada aplikasi SHYNESv2.

2. **Input Payload**: Masukkan payload SQL Injection pada search box:
   - `' OR 1=1 -- -`
   - `' OR '1'='1`
   - `' UNION SELECT table_name,null FROM information_schema.tables -- -`
   - `' AND SLEEP(5) -- -` (time-based test)

3. **Submit**: Kirim form pencarian dan amati response yang diberikan.

4. **Analisis**: Perhatikan apakah ada perubahan perilaku yang mengindikasikan keberhasilan SQL Injection.

[Screenshot: SQL Injection Search Test]

**Gambar 7.3** Pengujian SQL Injection pada fitur pencarian dengan payload `' OR 1=1 -- -`.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, fitur pencarian pada aplikasi SHYNESv2 **tidak rentan** terhadap SQL Injection. Payload yang dimasukkan tidak mengubah perilaku query dan hasil pencarian tetap sesuai dengan yang diharapkan (tidak ada data tambahan yang tidak semestinya muncul).

**Tabel 7.2 Hasil SQL Injection Search**

| Aspek | Detail |
|-------|--------|
| Kerentanan | SQL Injection pada Search |
| Endpoint | GET /search?q={keyword} |
| Parameter | q (keyword pencarian) |
| Teknik Pengujian | Manual (boolean, union, time-based) |
| Hasil | Tidak rentan - LIKE query menggunakan parameter binding |
| Severity | Info (tidak rentan) |
| Status | **Aman** |

**Analisis**

Fitur pencarian pada aplikasi SHYNESv2 menggunakan Eloquent atau Query Builder dengan parameter binding untuk query LIKE. Contoh implementasi yang aman:

```php
// Query Builder dengan parameter binding
$products = DB::table('products')
    ->where('name', 'LIKE', '%' . $request->q . '%')
    ->get();

// Atau menggunakan Eloquent
$products = Product::where('name', 'LIKE', '%' . $request->q . '%')->get();
```

Meskipun kata kunci pencarian digabungkan dengan operator LIKE, penggunaan parameter binding memastikan bahwa karakter khusus dalam input tidak dapat mengubah struktur query. Input `' OR 1=1 -- -` akan diperlakukan sebagai string literal, bukan sebagai bagian dari query SQL.

**Status: Aman**

Fitur pencarian menggunakan parameter binding yang mencegah SQL Injection. Endpoint search dinyatakan **aman**.

### 7.4 Skenario 3: Reflected XSS pada Search

**Latar Belakang**

Reflected XSS terjadi ketika input pengguna langsung direfleksikan ke dalam halaman web tanpa melalui proses escaping atau sanitasi. Pada fitur pencarian, kata kunci yang dimasukkan biasanya ditampilkan kembali pada halaman hasil pencarian, misalnya "Menampilkan hasil untuk: [keyword]". Jika keyword tidak di-escape, pengguna dapat menyisipkan skrip berbahaya.

**Langkah-langkah Pengujian**

1. **Buka Halaman Search**: Kunjungi halaman pencarian produk.

2. **Input Payload XSS**: Masukkan payload XSS pada search box:
   - `<script>alert('XSS')</script>`
   - `<img src=x onerror=alert(1)>`
   - `<svg onload=alert(1)>`
   - `javascript:alert(1)`

3. **Submit**: Kirim form pencarian dan amati response HTML.

4. **Periksa Source**: Periksa source code halaman untuk melihat apakah payload di-escape atau tidak.

[Screenshot: Reflected XSS Test]

**Gambar 7.4** Pengujian Reflected XSS pada fitur pencarian. Payload `<script>alert('XSS')</script>` dimasukkan ke dalam search box.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 **tidak rentan** terhadap Reflected XSS. Payload XSS yang dimasukkan melalui search box tidak dieksekusi oleh browser karena Blade template engine secara otomatis melakukan escaping.

**Tabel 7.3 Hasil Reflected XSS**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Reflected XSS pada Search |
| Endpoint | GET /search?q={keyword} |
| Parameter | q (keyword pencarian) |
| Payload | `<script>alert('XSS')</script>` |
| Hasil | Payload di-escape, tidak dieksekusi |
| Severity | Info (tidak rentan) |
| Status | **Aman** |

**Analisis**

Laravel Blade template engine secara default menggunakan sintaks `{{ $variable }}` yang melakukan HTML escaping. Karakter-karakter berbahaya seperti `<`, `>`, `"`, `'`, dan `&` akan diubah menjadi entitas HTML sehingga tidak dapat dieksekusi sebagai kode.

Contoh escaping yang dilakukan Blade:
- `<` menjadi `&lt;`
- `>` menjadi `&gt;`
- `"` menjadi `&quot;`
- `'` menjadi `&#039;`
- `&` menjadi `&amp;`

Sehingga input `<script>alert('XSS')</script>` akan dirender sebagai teks biasa:
```
&lt;script&gt;alert('XSS')&lt;/script&gt;
```

**Penting**: Laravel juga menyediakan sintaks `{!! $variable !!}` yang merender data tanpa escaping. Sintaks ini hanya boleh digunakan untuk data yang benar-benar tepercaya. Penggunaan sintaks ini pada data pengguna dapat menyebabkan kerentanan XSS.

**Status: Aman**

Blade template engine secara default melakukan escaping output, sehingga Reflected XSS tidak dapat terjadi. Fitur pencarian dinyatakan **aman** terhadap Reflected XSS.

### 7.5 Skenario 4: Stored XSS pada Review/Komentar

**Latar Belakang**

Stored XSS (juga dikenal sebagai Persistent XSS) terjadi ketika skrip berbahaya disimpan dalam database dan kemudian ditampilkan kepada pengguna lain tanpa melalui escaping. Jenis XSS ini lebih berbahaya daripada Reflected XSS karena dapat menjangkau lebih banyak korban tanpa memerlukan teknik sosial engineering yang kompleks.

**Langkah-langkah Pengujian**

1. **Identifikasi Field Input**: Temukan field input pada aplikasi yang datanya akan disimpan di database dan ditampilkan kembali. Field yang diuji meliputi:
   - Alamat pengiriman pada halaman checkout
   - Nama pelanggan pada form
   - Field komentar atau catatan

2. **Input Payload XSS**: Masukkan payload XSS pada field yang diuji:
   - `<script>alert(1)</script>`
   - `<img src=x onerror=alert(1)>`
   - `<svg/onload=alert(1)>`
   - `"><script>alert(1)</script>`

3. **Simpan Data**: Submit form untuk menyimpan data ke database.

4. **Verifikasi**: Buka halaman yang menampilkan data tersebut. Periksa apakah skrip dieksekusi atau di-escape.

[Screenshot: Stored XSS Test pada Field Alamat]

**Gambar 7.5** Pengujian Stored XSS pada field alamat pengiriman. Payload disimpan di database dan kemudian ditampilkan.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 **tidak rentan** terhadap Stored XSS. Payload XSS yang disimpan melalui berbagai field input berhasil di-escape saat ditampilkan, sehingga skrip tidak dieksekusi oleh browser.

**Tabel 7.4 Hasil Stored XSS Review**

| Aspek | Detail |
|-------|--------|
| Kerentanan | Stored XSS pada Field Input |
| Endpoint | POST /checkout (field alamat) |
| Field | shipping_address, customer_name, notes |
| Payload | `<script>alert(1)</script>` |
| Hasil | Disimpan escaped, ditampilkan escaped - tidak dieksekusi |
| Severity | Info (tidak rentan) |
| Status | **Aman** |

**Analisis**

Aplikasi SHYNESv2 secara konsisten menggunakan Blade escaping (`{{ }}`) untuk merender data yang berasal dari database. Hal ini memastikan bahwa data apapun yang disimpan, termasuk yang mengandung skrip berbahaya, akan ditampilkan sebagai teks biasa.

Proses escaping terjadi pada dua level:

1. **Saat Penyimpanan**: Data disimpan dalam database dalam bentuk aslinya (belum di-escape). Ini adalah praktik yang benar karena escaping sebaiknya dilakukan saat output, bukan saat input.

2. **Saat Penampilan**: Blade template engine melakukan HTML escaping saat data dirender ke halaman web. Inilah lapisan pertahanan utama terhadap Stored XSS.

**Status: Aman**

Penggunaan Blade escaping yang konsisten pada semua output data dari database mencegah Stored XSS. Fitur input data dinyatakan **aman** terhadap Stored XSS.

### 7.6 Skenario 5: Stored XSS pada Nama Produk (Admin Panel)

**Latar Belakang**

Admin panel sering kali menjadi target serangan XSS karena memiliki akses ke fitur-fitur yang memungkinkan input data dalam jumlah besar, seperti nama produk, deskripsi, kategori, dan lainnya. Jika data yang dimasukkan oleh admin tidak di-escape saat ditampilkan, maka admin lain atau pengguna yang melihat data tersebut dapat menjadi korban XSS.

**Langkah-langkah Pengujian**

1. **Login sebagai Admin**: Login ke aplikasi menggunakan akun admin.

2. **Buka Halaman Tambah Produk**: Navigasi ke halaman admin untuk menambah atau mengedit produk.

3. **Input Payload XSS**: Masukkan payload XSS pada field nama produk:
   - `<img onerror=alert(1) src=x>`
   - `<script>alert(document.cookie)</script>`
   - `"><svg onload=alert(1)>`

4. **Simpan Produk**: Submit form untuk menyimpan produk.

5. **Verifikasi Tampilan**: Buka halaman katalog produk (dari sisi pembeli) untuk melihat apakah payload dieksekusi.

[Screenshot: Stored XSS pada Nama Produk]

**Gambar 7.6** Pengujian Stored XSS pada field nama produk melalui admin panel.

**Hasil Pengujian**

Berdasarkan pengujian yang dilakukan, aplikasi SHYNESv2 **tidak rentan** terhadap Stored XSS pada nama produk. Payload yang dimasukkan melalui admin panel berhasil di-escape saat ditampilkan di halaman katalog produk.

**Tabel 7.5 Ringkasan Hasil Pengujian SQLi dan XSS**

| No | Skenario | Kerentanan | Severity | Status |
|----|----------|-----------|----------|--------|
| 1 | SQLi pada URL Parameter | SQL Injection | Info | **Aman** |
| 2 | SQLi pada Search | SQL Injection | Info | **Aman** |
| 3 | Reflected XSS pada Search | Reflected XSS | Info | **Aman** |
| 4 | Stored XSS pada Review/Komentar | Stored XSS | Info | **Aman** |
| 5 | Stored XSS pada Nama Produk (Admin) | Stored XSS | Info | **Aman** |

**Analisis**

Seluruh endpoint yang diuji untuk SQL Injection dan XSS menunjukkan hasil yang aman. Hal ini menunjukkan bahwa pengembang aplikasi SHYNESv2 telah mengimplementasikan praktik keamanan yang direkomendasikan oleh Laravel:

1. **Eloquent ORM**: Semua query database menggunakan Eloquent atau Query Builder dengan parameter binding, yang secara otomatis mencegah SQL Injection.
2. **Blade Escaping**: Semua output data menggunakan sintaks `{{ }}` yang melakukan HTML escaping secara otomatis, mencegah XSS.
3. **Tidak Ada Raw Queries**: Tidak ditemukan penggunaan `DB::raw()` atau `DB::statement()` yang dapat membuka celah SQL Injection.

**Status: Aman**

Seluruh endpoint yang diuji untuk SQL Injection dan XSS dinyatakan **aman**. Ini merupakan temuan positif yang menunjukkan bahwa aplikasi SHYNESv2 telah menerapkan praktik pengembangan yang aman dalam hal pencegahan injeksi.

---

## BAB VIII: RINGKASAN TEMUAN

### 8.1 Tabel Ringkasan Temuan

Berdasarkan seluruh rangkaian pengujian yang dilakukan pada aplikasi SHYNESv2, berikut adalah ringkasan temuan kerentanan yang berhasil diidentifikasi:

**Tabel 8.1 Ringkasan Seluruh Temuan**

| No | Temuan | Kategori | Severity | Status | Halaman |
|----|--------|----------|----------|--------|---------|
| 1 | Race condition pada checkout | Business Logic | High | Belum diperbaiki | IV.2 |
| 2 | Multiple coupon usage | Business Logic | Medium | Belum diperbaiki | IV.3 |
| 3 | Order status manipulation | Business Logic | Low | **Aman** | IV.4 |
| 4 | Order IDOR | IDOR | Low | **Aman** | V.2 |
| 5 | Contract IDOR | IDOR | Low | **Aman** | V.3 |
| 6 | user_id manipulation POS | IDOR | Medium | Belum diperbaiki | V.4 |
| 7 | Brute force login | Autentikasi | High | Belum diperbaiki | VI.2 |
| 8 | Session fixation | Autentikasi | Low | **Aman** | VI.3 |
| 9 | No session timeout | Autentikasi | Medium | Belum diperbaiki | VI.4 |
| 10 | Weak password policy | Autentikasi | High | Belum diperbaiki | VI.5 |
| 11 | SQL Injection (URL) | Injeksi | Info | **Aman** | VII.2 |
| 12 | SQL Injection (Search) | Injeksi | Info | **Aman** | VII.3 |
| 13 | Reflected XSS | Injeksi | Info | **Aman** | VII.4 |
| 14 | Stored XSS (Review) | Injeksi | Info | **Aman** | VII.5 |
| 15 | Stored XSS (Admin) | Injeksi | Info | **Aman** | VII.6 |

### 8.2 Analisis Severity dan Prioritas Perbaikan

Berdasarkan tingkat keparahan (severity) dan dampak terhadap aplikasi, berikut adalah prioritas perbaikan yang direkomendasikan:

**Tabel 8.2 Analisis Severity**

| Severity | Jumlah | Prioritas | Target Perbaikan |
|----------|--------|-----------|------------------|
| **High** | 3 | Segera (1-7 hari) | Race condition checkout, Brute force login, Weak password policy |
| **Medium** | 3 | Sedang (7-30 hari) | Multiple coupon usage, user_id manipulation POS, No session timeout |
| **Low** | 4 | Tidak diperlukan | Sudah aman (IDOR order, IDOR contract, Session fixation, Order status) |
| **Info** | 5 | Tidak diperlukan | Sudah aman (SQLi x2, XSS x3) |

**Prioritas 1 (High - Segera)**: Tiga temuan dengan severity High harus segera diperbaiki karena memiliki dampak finansial dan keamanan yang signifikan:

1. **Race Condition Checkout**: Dapat menyebabkan kerugian finansial langsung akibat pembelian melebihi stok. Perbaikan dengan pessimistic locking atau queue-based processing.
2. **Brute Force Login**: Membuka peluang pengambilalihan akun secara massal. Perbaikan dengan rate limiting, account lockout, dan captcha.
3. **Weak Password Policy**: Memudahkan serangan brute force dan credential stuffing. Perbaikan dengan validasi kekuatan password.

**Prioritas 2 (Medium - Sedang)**: Tiga temuan dengan severity Medium perlu diperbaiki dalam waktu dekat:

1. **Multiple Coupon Usage**: Dapat menyebabkan kerugian finansial akibat diskon yang tidak semestinya.
2. **User ID Manipulation POS**: Memungkinkan pembuatan pesanan atas nama pengguna lain.
3. **No Session Timeout**: Meningkatkan risiko session hijacking.

**Prioritas 3 (Low/Info - Tidak Perlu)**: Temuan-temuan yang sudah aman tidak memerlukan perbaikan, namun tetap perlu dipantau untuk memastikan tidak ada regresi di masa mendatang.

### 8.3 Potensi Bug Bounty

Berdasarkan standar bug bounty program pada platform e-commerce dan tingkat keparahan temuan, berikut adalah estimasi reward yang mungkin diperoleh jika kerentanan ini dilaporkan melalui program bug bounty:

**Tabel 8.3 Estimasi Bug Bounty**

| No | Temuan | Severity | Estimasi Reward |
|----|--------|----------|-----------------|
| 1 | Race condition checkout | High | $500 - $1,000 |
| 2 | Multiple coupon usage | Medium | $100 - $250 |
| 3 | user_id manipulation POS | Medium | $100 - $250 |
| 4 | Brute force login | High | $500 - $1,000 |
| 5 | No session timeout | Medium | $100 - $250 |
| 6 | Weak password policy | High | $250 - $500 |
| | **Total estimasi** | | **$1,550 - $3,250** |

Perlu dicatat bahwa estimasi reward di atas bersifat indikatif dan sangat bergantung pada kebijakan masing-masing program bug bounty. Faktor-faktor yang memengaruhi besaran reward meliputi:

1. **Dampak Bisnis**: Seberapa besar kerugian yang dapat ditimbulkan oleh kerentanan tersebut.
2. **Kompleksitas Eksploitasi**: Seberapa sulit untuk mengeksploitasi kerentanan.
3. **Cakupan Pengguna**: Berapa banyak pengguna yang terpengaruh oleh kerentanan.
4. **Kualitas Laporan**: Seberapa detail dan jelas laporan yang diberikan.

---

## BAB IX: PENUTUP

### 9.1 Kesimpulan

Berdasarkan seluruh rangkaian pengujian keamanan (penetration testing) yang dilakukan pada aplikasi SHYNESv2 (https://shynesv2.up.railway.app), dapat ditarik beberapa kesimpulan sebagai berikut:

1. **Business Logic Flaws**: Aplikasi SHYNESv2 memiliki kerentanan pada aspek logika bisnis, terutama pada fitur checkout yang rentan terhadap race condition dan fitur kupon diskon yang tidak memiliki mekanisme pembatasan penggunaan. Kedua kerentanan ini memiliki dampak finansial yang signifikan karena dapat menyebabkan kerugian akibat stok yang tidak akurat dan diskon yang tidak semestinya. Sementara itu, fitur perubahan status pesanan telah diamankan dengan baik melalui middleware role checking.

2. **Insecure Direct Object Reference (IDOR)**: Secara umum, aplikasi telah menerapkan proteksi IDOR yang cukup baik pada data pesanan (melalui pengecekan user_id) dan data kontrak (melalui tenant isolation). Namun, terdapat celah pada fitur POS checkout di mana parameter user_id dapat dimanipulasi untuk membuat pesanan atas nama pengguna lain tanpa validasi yang memadai.

3. **Otentikasi dan Manajemen Sesi**: Area ini memiliki kerentanan yang paling kritis. Aplikasi tidak memiliki mekanisme rate limiting, account lockout, atau captcha pada halaman login, sehingga rentan terhadap serangan brute force. Session fixation telah diamankan oleh regenerasi session ID bawaan Laravel, namun session timeout tidak diimplementasikan. Kebijakan password juga sangat lemah karena tidak ada validasi kekuatan password.

4. **SQL Injection dan XSS**: Aplikasi SHYNESv2 menunjukkan ketahanan yang baik terhadap serangan injeksi. Penggunaan Eloquent ORM dengan parameter binding secara efektif mencegah SQL Injection, sementara Blade template engine dengan escaping otomatis mencegah XSS. Seluruh titik input yang diuji dinyatakan aman terhadap kedua jenis serangan ini.

5. **Tingkat Keamanan Keseluruhan**: Dari total 15 skenario pengujian yang dilakukan, 7 skenario menunjukkan hasil yang aman (tidak rentan), sementara 6 skenario memerlukan perbaikan dengan rincian 3 severity High dan 3 severity Medium. Hal ini menunjukkan bahwa aplikasi SHYNESv2 memiliki postur keamanan yang cukup baik untuk serangan injeksi dan IDOR, namun masih memerlukan perbaikan signifikan pada aspek otentikasi dan logika bisnis.

### 9.2 Saran

Berdasarkan hasil pengujian dan analisis yang telah dilakukan, berikut adalah saran yang dapat diberikan untuk meningkatkan keamanan aplikasi SHYNESv2:

1. **Prioritas Perbaikan Segera (High Severity)**:
   - Implementasi pessimistic locking (`lockForUpdate()`) atau queue-based processing pada proses checkout untuk mencegah race condition.
   - Implementasi rate limiting menggunakan middleware `throttle` pada endpoint login dan tambahkan CAPTCHA setelah beberapa kali percobaan gagal.
   - Implementasi account lockout yang mengunci akun sementara setelah 5 kali percobaan login gagal.
   - Terapkan validasi kekuatan password dengan minimum 8 karakter, kombinasi huruf besar dan kecil, angka, dan karakter khusus.

2. **Prioritas Perbaikan Jangka Menengah (Medium Severity)**:
   - Tambahkan mekanisme pencatatan penggunaan kupon per pengguna (tabel `coupon_usages`) untuk mencegah penggunaan kupon berulang.
   - Validasi kesesuaian antara `user_id` dengan `customer_name` dan `customer_phone` pada fitur POS checkout.
   - Implementasi idle session timeout dengan batas waktu 15-30 menit.

3. **Peningkatan Keamanan Berkelanjutan**:
   - Lakukan pengujian keamanan secara berkala (setidaknya setiap 3-6 bulan) untuk mendeteksi kerentanan baru.
   - Implementasikan security header seperti Content-Security-Policy, X-Frame-Options, X-Content-Type-Options, dan Strict-Transport-Security.
   - Terapkan logging dan monitoring untuk mendeteksi aktivitas mencurigakan, termasuk percobaan login yang gagal, akses ke endpoint yang tidak sah, dan manipulasi parameter.
   - Ikuti praktik pengembangan aman (secure coding practices) dan lakukan code review dengan fokus pada keamanan.

4. **Rekomendasi untuk Pengembang**:
   - Aktifkan semua fitur keamanan bawaan Laravel, termasuk throttle middleware, session security, dan password validation rules.
   - Hindari penggunaan raw SQL queries (`DB::raw()`, `DB::statement()`) tanpa parameter binding yang tepat.
   - Gunakan sintaks `{{ }}` untuk escaping output dan hanya gunakan `{!! !!}` untuk data yang benar-benar tepercaya.
   - Terapkan prinsip defense in depth dengan tidak hanya mengandalkan satu lapisan keamanan.

5. **Rekomendasi untuk Manajemen**:
   - Tetapkan kebijakan keamanan yang jelas untuk pengembangan aplikasi.
   - Alokasikan sumber daya yang memadai untuk perbaikan keamanan.
   - Pertimbangkan untuk mengadakan program bug bounty untuk memotivasi peneliti keamanan melaporkan kerentanan secara bertanggung jawab.
   - Lakukan pelatihan keamanan untuk seluruh tim pengembang.

---

## DAFTAR PUSTAKA

OWASP Foundation. (2021). OWASP Top 10 2021: The Ten Most Critical Web Application Security Risks. https://owasp.org/Top10/

OWASP Foundation. (2021). A01:2021 – Broken Access Control. https://owasp.org/Top10/A01_2021-Broken_Access_Control/

OWASP Foundation. (2021). A03:2021 – Injection. https://owasp.org/Top10/A03_2021-Injection/

OWASP Foundation. (2021). A07:2021 – Identification and Authentication Failures. https://owasp.org/Top10/A07_2021-Identification_and_Authentication_Failures/

OWASP Foundation. (2024). Testing for Business Logic (WSTG-BUSL). https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/10-Business_Logic_Testing/

OWASP Foundation. (2024). Testing for IDOR (WSTG-ATHZ-04). https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/04-Authorization_Testing/04-Testing_for_Insecure_Direct_Object_References/

OWASP Foundation. (2024). Testing for SQL Injection (WSTG-INP-09). https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/07-Input_Validation_Testing/05-Testing_for_SQL_Injection/

OWASP Foundation. (2024). Testing for Cross Site Scripting (WSTG-INP-01). https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/07-Input_Validation_Testing/01-Testing_for_Reflected_Cross_Site_Scripting/

PortSwigger. (2024). Burp Suite Documentation. https://portswigger.net/burp/documentation

ProjectDiscovery. (2024). Nuclei Documentation. https://docs.projectdiscovery.io/tools/nuclei/overview

sqlmap. (2024). sqlmap User Manual. https://github.com/sqlmapproject/sqlmap/wiki

Laravel. (2024). Laravel 11 Documentation: Authentication. https://laravel.com/docs/11.x/authentication

Laravel. (2024). Laravel 11 Documentation: Blade Templates. https://laravel.com/docs/11.x/blade

Laravel. (2024). Laravel 11 Documentation: Database. https://laravel.com/docs/11.x/database

Laravel. (2024). Laravel 11 Documentation: Session. https://laravel.com/docs/11.x/session

Stuttard, D., & Pinto, M. (2011). The Web Application Hacker's Handbook: Finding and Exploiting Security Flaws (2nd ed.). Wiley.

Verizon. (2024). 2024 Data Breach Investigations Report. https://www.verizon.com/business/resources/reports/dbir/

ZAP. (2024). OWASP ZAP Documentation. https://www.zaproxy.org/docs/

---

## LAMPIRAN

### Lampiran A: Daftar Endpoint yang Diuji

| No | Endpoint | Method | Parameter | Kategori Pengujian |
|----|----------|--------|-----------|-------------------|
| 1 | /login | POST | email, password | Authentication |
| 2 | /register | POST | name, email, password | Authentication |
| 3 | /products | GET | category_id | SQL Injection |
| 4 | /search | GET | q | SQL Injection, XSS |
| 5 | /orders/{id} | GET | id | IDOR |
| 6 | /checkout | POST | product_id, quantity, address | Business Logic |
| 7 | /apply-coupon | POST | coupon_code, order_id | Business Logic |
| 8 | /admin/orders/{id}/status | PUT | id, status | Business Logic |
| 9 | /admin/contracts/{id} | GET | id | IDOR |
| 10 | /admin/pos/checkout | POST | user_id, products | IDOR |

### Lampiran B: Payload yang Digunakan

**SQL Injection Payloads:**
- `' OR '1'='1`
- `' OR 1=1 -- -`
- `' UNION SELECT null,null,null -- -`
- `1' AND 1=1 -- -`
- `1' AND 1=2 -- -`
- `' AND SLEEP(5) -- -`
- `' OR '1'='1' -- -`

**XSS Payloads:**
- `<script>alert('XSS')</script>`
- `<img src=x onerror=alert(1)>`
- `<svg onload=alert(1)>`
- `"><script>alert(1)</script>`
- `<img onerror=alert(1) src=x>`
- `javascript:alert(1)`

### Lampiran C: Wordlist Brute Force (Sampel)

```
123456
password
admin123
welcome
qwerty
admin
test123
letmein
monkey
dragon
```

### Lampiran D: Screenshot Dokumentasi Pengujian

[Screenshot: Burp Suite Intercept Configuration]

[Screenshot: Burp Suite Intruder Race Condition]

[Screenshot: Hasil Race Condition Stok Berkurang Ganda]

[Screenshot: Kupon Berhasil Digunakan Berkali-kali]

[Screenshot: IDOR Order Test 403 Response]

[Screenshot: IDOR Contract Test 403 Response]

[Screenshot: Manipulasi user_id POS Checkout]

[Screenshot: Burp Intruder Brute Force Login]

[Screenshot: Session ID Before and After Login]

[Screenshot: Session Active After 30 Minutes Idle]

[Screenshot: Weak Password Registration]

[Screenshot: sqlmap Scanning Result]

[Screenshot: SQL Injection Search Test]

[Screenshot: Reflected XSS Test]

[Screenshot: Stored XSS Test]

---

**-- Akhir Laporan --**
