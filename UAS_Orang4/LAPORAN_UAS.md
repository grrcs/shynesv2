---
title: LAPORAN UAS – KEAMANAN SISTEM INFORMASI
subtitle: Perancangan Program Bug Bounty pada Aplikasi SHYNESv2 Fashion E-Commerce
author: [Nama Mahasiswa 4]
nim: [NIM.4]
dosen: [Nama Dosen Pengampu]
mata-kuliah: Keamanan Sistem Informasi
program-studi: Sistem Informasi
universitas: [Nama Universitas]
tanggal: 2026
---

# LAPORAN UAS – KEAMANAN SISTEM INFORMASI

## Topik: Perancangan Program Bug Bounty pada Aplikasi SHYNESv2 Fashion E-Commerce

**Disusun oleh:**

[Nama Mahasiswa 4]

NIM: [NIM.4]

---

**[Nama Universitas]**

**[Fakultas]**

**[Program Studi Sistem Informasi]**

**2026**

---

## KATA PENGANTAR

Puji syukur ke hadirat Tuhan Yang Maha Esa atas segala rahmat dan karunia-Nya sehingga laporan Ujian Akhir Semester mata kuliah Keamanan Sistem Informasi ini dapat diselesaikan dengan baik. Laporan ini disusun sebagai salah satu syarat untuk menyelesaikan perkuliahan Keamanan Sistem Informasi pada semester ini.

Laporan ini membahas tentang perancangan program Bug Bounty untuk aplikasi SHYNESv2 Fashion E-Commerce. Program Bug Bounty merupakan salah satu pendekatan keamanan siber yang memanfaatkan kekuatan crowdsourced security, di mana para peneliti keamanan dari seluruh dunia diundang untuk mencari dan melaporkan kerentanan pada aplikasi. Topik ini dipilih mengingat semakin meningkatnya ancaman siber di era digital dan pentingnya pendekatan proaktif dalam mengamankan aplikasi e-commerce.

Penulis menyadari bahwa laporan ini masih jauh dari sempurna. Oleh karena itu, kritik dan saran yang membangun sangat diharapkan untuk perbaikan di masa mendatang. Semoga laporan ini dapat memberikan manfaat bagi pengembangan ilmu pengetahuan di bidang keamanan sistem informasi, khususnya dalam implementasi program Bug Bounty di Indonesia.

Akhir kata, penulis mengucapkan terima kasih kepada dosen pengampu mata kuliah Keamanan Sistem Informasi yang telah memberikan bimbingan dan arahan selama perkuliahan, serta kepada rekan-rekan mahasiswa yang telah memberikan dukungan dalam penyusunan laporan ini.
---

## DAFTAR ISI

KATA PENGANTAR ......................................................................................................... ii

DAFTAR ISI .................................................................................................................... iii

DAFTAR GAMBAR ......................................................................................................... v

DAFTAR TABEL ............................................................................................................. vi

BAB I PENDAHULUAN ................................................................................................... 1

1.1 Latar Belakang ..................................................................................................... 1

1.2 Rumusan Masalah ............................................................................................... 3

1.3 Batasan Masalah ................................................................................................. 4

1.4 Tujuan Penelitian ................................................................................................. 5

1.5 Manfaat Penelitian ............................................................................................... 6

BAB II LANDASAN TEORI ............................................................................................ 7

2.1 Definisi Bug Bounty .............................................................................................. 7

2.2 Sejarah dan Perkembangan Bug Bounty .............................................................. 9

2.3 Platform Bug Bounty ............................................................................................ 12

2.4 Jenis-Jenis Bug Bounty Programs ...................................................................... 15

2.5 Metodologi Bug Bounty ....................................................................................... 18

2.6 OWASP Testing Guide v4.2 ............................................................................... 22

2.7 CVSS v3.1 Scoring System ................................................................................ 25

BAB III GAMBARAN UMUM APLIKASI SHYNESv2 ...................................................... 28

3.1 Arsitektur Aplikasi ............................................................................................... 28

3.2 Fitur Utama ......................................................................................................... 31

3.3 Stack Teknologi .................................................................................................. 34

3.4 Alur Data dan Interaksi Antar Komponen ........................................................... 36

BAB IV PERANCANGAN LINGKUNGAN BUG BOUNTY ............................................. 39

4.1 Topologi Lingkungan ........................................................................................... 39

4.2 Spesifikasi Lingkungan Staging .......................................................................... 42

4.3 Tools yang Disediakan ........................................................................................ 45

4.4 Aturan Main (Rules of Engagement) ................................................................... 48

BAB V PROSES BUG BOUNTY .................................................................................. 52

5.1 Alur Program ....................................................................................................... 52

5.2 Registrasi Researcher ........................................................................................ 55

5.3 Tahap Reconnaissance ...................................................................................... 58

5.4 Tahap Testing ..................................................................................................... 62

5.5 Tahap Pelaporan ................................................................................................ 66

5.6 Tahap Triage dan Verifikasi ............................................................................... 72

5.7 Tahap Remediasi ............................................................................................... 75

5.8 Tahap Reward dan Disclosure ........................................................................... 78

BAB VI STRUKTUR TIM DAN PERAN ......................................................................... 81

6.1 Program Manager ............................................................................................... 81

6.2 Triage Lead ........................................................................................................ 83

6.3 Remediation Lead ............................................................................................... 85

6.4 Legal dan Compliance ........................................................................................ 87

BAB VII REWARD DAN INCENTIVE STRUCTURE ...................................................... 89

7.1 Skema Reward Berdasarkan Severity ................................................................ 89

7.2 Mekanisme Pembayaran .................................................................................... 92

7.3 Anggaran Tahunan ............................................................................................. 94

BAB VIII DISCLOSURE POLICY ................................................................................ 96

8.1 Coordinated Disclosure ...................................................................................... 96

8.2 Hall of Fame ...................................................................................................... 99

8.3 CVE Assignment ............................................................................................... 101

BAB IX USULAN PERBAIKAN DARI HASIL PENTEST ............................................ 103

9.1 Prioritas Tinggi ................................................................................................. 103

9.2 Prioritas Sedang ............................................................................................... 108

9.3 Prioritas Rendah ............................................................................................... 111

BAB X ANALISIS RISIKO PROGRAM ....................................................................... 114

10.1 Risiko Researcher Nakal ................................................................................ 114

10.2 Risiko False Positive Berlebihan .................................................................... 117

10.3 Risiko Budget Tidak Mencukupi ...................................................................... 119

10.4 Risiko Duplicate Reports ................................................................................ 121

BAB XI PENUTUP ...................................................................................................... 123

11.1 Kesimpulan ..................................................................................................... 123

11.2 Saran .............................................................................................................. 125

DAFTAR PUSTAKA .................................................................................................... 127

---

## DAFTAR GAMBAR

Gambar 4.1 Topologi Lingkungan Bug Bounty SHYNESv2 .......................................... 40

Gambar 4.2 Arsitektur Lingkungan Staging .................................................................. 44

Gambar 5.1 Flowchart Alur Program Bug Bounty ......................................................... 53

Gambar 5.2 Tampilan Halaman Registrasi Researcher ................................................. 57

Gambar 5.3 Contoh Output Alat Reconnaissance ......................................................... 61

Gambar 5.4 Contoh Skenario Manual Testing ................................................................ 65

Gambar 5.5 Format Template Pelaporan ...................................................................... 67

Gambar 5.6 Alur Triage dan Verifikasi .......................................................................... 73

Gambar 5.7 Timeline Remediasi .................................................................................. 77

Gambar 7.1 Diagram Alokasi Anggaran Bug Bounty ..................................................... 95

---

## DAFTAR TABEL

Tabel 2.1 Perbandingan Platform Bug Bounty .............................................................. 14

Tabel 2.2 Tingkat Severity Berdasarkan CVSS v3.1 ..................................................... 26

Tabel 3.1 Stack Teknologi SHYNESv2 ......................................................................... 35

Tabel 4.1 Spesifikasi Lingkungan Staging ..................................................................... 43

Tabel 4.2 Aturan Main Program Bug Bounty ................................................................. 49

Tabel 6.1 Struktur Tim Program Bug Bounty ................................................................. 82

Tabel 7.1 Skema Reward dan SLA Fix ......................................................................... 90

Tabel 9.1 Prioritas Perbaikan Berdasarkan Severity .................................................... 104

Tabel 10.1 Matriks Analisis Risiko .............................................................................. 115

---

## BAB I

## PENDAHULUAN

### 1.1 Latar Belakang

Perkembangan teknologi informasi dan komunikasi telah membawa perubahan fundamental dalam cara manusia bertransaksi dan berinteraksi di dunia digital. E-commerce menjadi salah satu sektor yang mengalami pertumbuhan paling pesat, terutama di Indonesia yang memiliki populasi pengguna internet yang sangat besar. Platform e-commerce seperti SHYNESv2 Fashion E-Commerce hadir untuk memenuhi kebutuhan masyarakat akan layanan belanja fashion secara online yang praktis, cepat, dan aman. Namun, seiring dengan meningkatnya adopsi e-commerce, ancaman keamanan siber juga mengalami peningkatan yang signifikan. Serangan siber terhadap platform e-commerce dapat mengakibatkan kerugian finansial yang besar, kebocoran data pengguna, hilangnya kepercayaan konsumen, dan dampak reputasi yang sulit dipulihkan.

Dalam beberapa tahun terakhir, industri teknologi global telah menyadari bahwa pendekatan keamanan tradisional yang hanya mengandalkan tim keamanan internal dan audit keamanan periodik tidak lagi memadai untuk menghadapi lanskap ancaman yang terus berkembang. Konsep crowdsourced security muncul sebagai solusi inovatif yang memungkinkan organisasi memanfaatkan ribuan peneliti keamanan independen dari seluruh dunia untuk mengidentifikasi kerentanan dalam sistem mereka. Salah satu implementasi paling populer dari crowdsourced security adalah program Bug Bounty, di mana organisasi menawarkan imbalan finansial kepada peneliti keamanan yang berhasil menemukan dan melaporkan kerentanan secara bertanggung jawab.

Perusahaan-perusahaan teknologi terkemuka dunia telah membuktikan efektivitas program Bug Bounty dalam meningkatkan postur keamanan mereka. Google melalui Vulnerability Reward Program-nya telah membayar lebih dari puluhan juta dolar kepada ribuan peneliti keamanan sejak program ini diluncurkan pada tahun 2010. Facebook (sekarang Meta) menjalankan program Bug Bounty yang sangat sukses dengan lebih dari 50.000 laporan yang telah diverifikasi dan jutaan dolar reward yang telah dibayarkan. Apple, Microsoft, GitHub, dan hampir semua perusahaan teknologi besar lainnya juga memiliki program Bug Bounty yang aktif. Keberhasilan program-program ini menunjukkan bahwa pendekatan crowdsourced security mampu mengidentifikasi kerentanan yang mungkin terlewatkan oleh tim keamanan internal, dengan biaya yang lebih efisien dibandingkan dengan model tradisional.

SHYNESv2 Fashion E-Commerce sebagai platform yang relatif baru namun berkembang pesat menghadapi tantangan keamanan yang kompleks. Aplikasi ini dibangun dengan menggunakan framework Laravel yang dihosting di Railway dan menggunakan PostgreSQL sebagai database. Arsitektur yang mencakup berbagai fitur seperti Point of Sale (POS), sistem e-commerce, integrasi payment gateway, dan contract management menciptakan permukaan serangan yang luas. Setiap fitur baru yang ditambahkan ke dalam aplikasi berpotensi membawa kerentanan baru yang dapat dieksploitasi oleh pihak yang tidak bertanggung jawab. Oleh karena itu, pendekatan keamanan yang komprehensif dan berkelanjutan sangat diperlukan.

Program Bug Bounty menawarkan solusi yang tepat untuk menjawab tantangan keamanan yang dihadapi oleh SHYNESv2. Dengan mengundang peneliti keamanan dari berbagai latar belakang dan tingkat keahlian untuk menguji aplikasi, SHYNESv2 dapat memperoleh perspektif yang beragam dalam mengidentifikasi kerentanan. Program ini juga menciptakan hubungan yang saling menguntungkan antara organisasi dan komunitas keamanan siber global. Organisasi mendapatkan keamanan yang lebih baik dengan biaya yang lebih terkendali, sementara peneliti keamanan mendapatkan pengakuan dan imbalan atas keahlian mereka.

Selain itu, program Bug Bounty juga mendorong budaya transparansi dan kolaborasi dalam keamanan siber. Dengan adanya kebijakan disclosure yang terkoordinasi, kerentanan yang ditemukan dapat diperbaiki sebelum dieksploitasi oleh pihak jahat. Hal ini sejalan dengan prinsip responsible disclosure yang dianjurkan oleh organisasi keamanan internasional seperti OWASP dan FIRST.

Laporan ini akan membahas secara mendalam perancangan program Bug Bounty untuk aplikasi SHYNESv2 Fashion E-Commerce, mencakup aspek topologi lingkungan pengujian, aturan main, proses dari registrasi hingga reward, struktur tim, skema insentif, kebijakan disclosure, analisis risiko, dan rekomendasi perbaikan berdasarkan temuan dari pengujian keamanan yang telah dilakukan.
### 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, maka rumusan masalah dalam penelitian ini adalah sebagai berikut:

Pertama, bagaimana merancang lingkungan Bug Bounty yang aman dan terkendali untuk aplikasi SHYNESv2 Fashion E-Commerce sehingga peneliti keamanan dapat melakukan pengujian tanpa mengganggu operasional aplikasi produksi? Lingkungan pengujian harus dirancang sedemikian rupa sehingga mencerminkan kondisi produksi namun tanpa menggunakan data riil pengguna dan tanpa risiko terhadap kelangsungan bisnis.

Kedua, bagaimana menyusun aturan main atau rules of engagement yang jelas dan komprehensif untuk mengatur perilaku peneliti keamanan yang berpartisipasi dalam program Bug Bounty SHYNESv2? Aturan ini harus mencakup aspek scope pengujian, metode yang diizinkan, batasan data yang dapat diekstraksi, serta sanksi atas pelanggaran aturan.

Ketiga, bagaimana merancang alur program Bug Bounty yang efektif mulai dari registrasi peneliti, proses pengujian, pelaporan temuan, verifikasi dan triase, remediasi, hingga pemberian reward dan disclosure? Alur program harus dirancang untuk memastikan setiap temuan ditangani dengan tepat dan efisien.

Keempat, bagaimana menentukan skema reward yang adil dan kompetitif berdasarkan tingkat keparahan kerentanan yang ditemukan, dengan mempertimbangkan anggaran yang tersedia dan standar industri yang berlaku?

Kelima, bagaimana menyusun kebijakan disclosure yang menyeimbangkan kepentingan organisasi dalam melindungi keamanan pengguna dengan hak peneliti untuk mendapatkan pengakuan atas temuan mereka?

Keenam, apa saja rekomendasi perbaikan keamanan yang perlu diterapkan pada aplikasi SHYNESv2 berdasarkan hasil pengujian keamanan yang telah dilakukan oleh peneliti sebelumnya?

### 1.3 Batasan Masalah

Agar pembahasan dalam laporan ini tetap terfokus dan tidak meluas, maka penelitian ini dibatasi pada beberapa aspek sebagai berikut:

Pertama, perancangan program Bug Bounty dalam laporan ini hanya difokuskan pada aplikasi SHYNESv2 Fashion E-Commerce, tidak mencakup aplikasi atau sistem lain yang mungkin dimiliki oleh organisasi. Lingkungan pengujian yang dirancang terbatas pada domain staging.shynesv2.up.railway.app dan subdomain di bawahnya yang telah ditentukan sebagai in-scope.

Kedua, laporan ini membahas perancangan program secara konseptual dan teknis, tidak mencakup implementasi aktual dari program Bug Bounty pada infrastruktur yang sudah berjalan. Perancangan meliputi aspek topologi lingkungan, aturan main, alur program, dan kebijakan pendukung.

Ketiga, aturan main yang ditetapkan dalam perancangan ini bersifat umum dan dapat disesuaikan lebih lanjut berdasarkan kebutuhan spesifik organisasi. Aturan main mencakup scope pengujian, metode yang diizinkan, batasan ekstraksi data, dan sanksi pelanggaran.

Keempat, rekomendasi perbaikan keamanan yang diusulkan dalam laporan ini didasarkan pada temuan dari pengujian keamanan yang dilakukan oleh peneliti lain (Orang 2 dan Orang 3) terhadap aplikasi SHYNESv2. Rekomendasi ini dibahas dari sudut pandang manajer program Bug Bounty, bukan dari sudut pandang penguji teknis.

Kelima, skema reward yang dirancang menggunakan mata uang dolar AS dengan referensi standar industri Bug Bounty global. Konversi ke mata uang lokal dan penyesuaian besaran reward dapat dilakukan sesuai dengan kebijakan organisasi.

Keenam, laporan ini tidak membahas aspek teknis mendalam mengenai teknik eksploitasi kerentanan, melainkan lebih berfokus pada aspek manajerial dan operasional dari program Bug Bounty.

### 1.4 Tujuan Penelitian

Tujuan dari penelitian ini adalah sebagai berikut:

Pertama, merancang lingkungan Bug Bounty yang aman dan representatif untuk aplikasi SHYNESv2 Fashion E-Commerce, termasuk spesifikasi lingkungan staging, tools yang disediakan, dan konfigurasi keamanan yang diperlukan. Lingkungan ini harus mampu memfasilitasi pengujian keamanan secara menyeluruh tanpa menimbulkan risiko terhadap sistem produksi.

Kedua, menyusun rules of engagement yang komprehensif dan jelas untuk program Bug Bounty SHYNESv2, mencakup scope in-scope dan out-of-scope, metode pengujian yang diizinkan, batasan operasional, dan mekanisme sanksi bagi pelanggar aturan. Dokumen rules of engagement ini akan menjadi acuan utama bagi seluruh peneliti yang berpartisipasi dalam program.

Ketiga, mendesain alur program Bug Bounty yang lengkap dan terstruktur, mencakup seluruh tahapan mulai dari registrasi peneliti, pengujian dan pelaporan temuan, triase dan verifikasi, remediasi, hingga pemberian reward dan disclosure publik. Alur ini dirancang untuk memastikan efisiensi dan efektivitas program.

Keempat, menentukan skema reward yang kompetitif dan proporsional berdasarkan severity kerentanan yang diukur menggunakan standar CVSS v3.1, dengan mempertimbangkan anggaran tahunan yang tersedia dan praktik terbaik industri.

Kelima, menyusun kebijakan disclosure yang seimbang antara kepentingan organisasi dan hak peneliti, mencakup coordinated disclosure, Hall of Fame, dan CVE assignment.

Keenam, mengidentifikasi dan merekomendasikan perbaikan keamanan prioritas untuk aplikasi SHYNESv2 berdasarkan analisis temuan dari pengujian keamanan yang telah dilakukan.

### 1.5 Manfaat Penelitian

Penelitian ini diharapkan dapat memberikan manfaat sebagai berikut:

Pertama, bagi pengembang aplikasi SHYNESv2 Fashion E-Commerce, laporan ini menyediakan panduan komprehensif untuk mengimplementasikan program Bug Bounty sebagai bagian dari strategi keamanan siber organisasi. Dengan mengikuti perancangan yang diusulkan, organisasi dapat meningkatkan postur keamanan aplikasi secara signifikan melalui partisipasi komunitas peneliti keamanan global.

Kedua, bagi komunitas peneliti keamanan di Indonesia, laporan ini memberikan gambaran tentang bagaimana sebuah program Bug Bounty dirancang dan dioperasikan, sehingga dapat menjadi referensi bagi peneliti yang ingin berpartisipasi dalam program serupa. Pemahaman tentang aturan main, format pelaporan, dan etika pengujian sangat penting bagi peneliti keamanan.

Ketiga, bagi akademisi dan mahasiswa yang mempelajari keamanan sistem informasi, laporan ini menyajikan studi kasus nyata tentang penerapan konsep crowdsourced security dalam konteks e-commerce. Materi dalam laporan ini dapat digunakan sebagai bahan pembelajaran dan referensi untuk penelitian lebih lanjut di bidang keamanan siber.

Keempat, bagi industri e-commerce di Indonesia secara umum, laporan ini memberikan contoh konkret tentang bagaimana program Bug Bounty dapat dirancang dan diimplementasikan. Hal ini diharapkan dapat mendorong lebih banyak platform e-commerce di Indonesia untuk mengadopsi pendekatan serupa dalam mengelola keamanan aplikasi mereka.

Kelima, bagi pengembangan ilmu pengetahuan di bidang keamanan sistem informasi, laporan ini berkontribusi dalam memperkaya literatur tentang perancangan program Bug Bounty, khususnya dalam konteks aplikasi yang dibangun dengan teknologi Laravel dan dihosting di platform cloud seperti Railway.
---

## BAB II

## LANDASAN TEORI

### 2.1 Definisi Bug Bounty

Bug Bounty adalah program insentif yang ditawarkan oleh organisasi, perusahaan, atau pengembang perangkat lunak kepada individu-individu di luar organisasi yang berhasil menemukan dan melaporkan kerentanan keamanan dalam sistem, aplikasi, atau perangkat lunak mereka. Istilah "Bug Bounty" sendiri berasal dari konsep "bounty" atau "hadiah" yang diberikan sebagai imbalan atas penemuan bug keamanan. Konsep ini merupakan implementasi dari crowdsourced security, di mana organisasi memanfaatkan kecerdasan kolektif dari ribuan peneliti keamanan independen di seluruh dunia untuk mengidentifikasi kelemahan keamanan yang mungkin terlewatkan oleh tim keamanan internal.

Dalam program Bug Bounty, peneliti keamanan yang berhasil mengidentifikasi kerentanan yang valid akan menerima kompensasi finansial yang besarnya bervariasi tergantung pada tingkat keparahan kerentanan yang ditemukan. Selain imbalan finansial, beberapa program juga menawarkan pengakuan publik melalui Hall of Fame, merchandise, atau undangan ke acara eksklusif. Program Bug Bounty berbeda dengan vulnerability disclosure program (VDP) biasa karena adanya insentif finansial yang ditawarkan. VDP biasanya hanya menyediakan saluran pelaporan tanpa kompensasi uang, sementara Bug Bounty secara eksplisit menjanjikan reward untuk temuan yang valid.

Bug Bounty beroperasi berdasarkan prinsip responsible disclosure, di mana peneliti keamanan diharuskan untuk melaporkan kerentanan yang mereka temukan secara pribadi kepada organisasi terlebih dahulu sebelum mempublikasikannya ke publik. Hal ini memberikan kesempatan kepada organisasi untuk memperbaiki kerentanan sebelum informasi tersebut diketahui oleh pihak-pihak yang mungkin akan mengeksploitasinya. Responsible disclosure merupakan kebalikan dari full disclosure, di mana peneliti langsung mempublikasikan kerentanan tanpa memberikan kesempatan kepada organisasi untuk memperbaikinya terlebih dahulu.

Program Bug Bounty memiliki beberapa karakteristik utama yang membedakannya dari metode pengujian keamanan tradisional. Pertama, program ini bersifat berkelanjutan atau continuous, berbeda dengan penetration testing tradisional yang biasanya dilakukan secara periodik. Kedua, program ini melibatkan banyak peneliti dengan latar belakang dan keahlian yang beragam, sehingga memberikan cakupan pengujian yang lebih luas. Ketiga, model pembayaran berbasis hasil atau result-based, di mana organisasi hanya membayar untuk temuan yang valid dan telah diverifikasi.

Efektivitas program Bug Bounty telah terbukti melalui berbagai penelitian dan studi kasus. Sebuah studi yang dilakukan oleh Google menunjukkan bahwa program Bug Bounty mereka mampu mengidentifikasi kerentanan dengan biaya yang lebih rendah dibandingkan dengan mempekerjakan tim keamanan internal untuk melakukan pengujian yang setara. Selain itu, waktu yang diperlukan untuk menemukan dan memperbaiki kerentanan juga lebih singkat karena melibatkan banyak peneliti yang bekerja secara paralel.

### 2.2 Sejarah dan Perkembangan Bug Bounty

Sejarah program Bug Bounty dimulai pada tahun 1983 ketika perusahaan komputer Hunter & Ready meluncurkan program yang menawarkan hadiah berupa Volkswagen Beetle kepada siapa saja yang dapat menemukan bug dalam sistem operasi mereka. Meskipun program ini tidak secara khusus berfokus pada keamanan, program ini dianggap sebagai cikal bakal dari program Bug Bounty modern. Namun, program ini tidak bertahan lama dan tidak banyak dikenal oleh publik.

Titik balik dalam sejarah Bug Bounty terjadi pada tahun 1995 ketika Netscape Communications meluncurkan Netscape Bugs Bounty program. Program ini menawarkan hadiah uang tunai dan kaos kepada siapa saja yang dapat menemukan bug keamanan dalam peramban web Netscape Navigator. Program Netscape dianggap sebagai program Bug Bounty keamanan pertama yang benar-benar terstruktur dan menjadi inspirasi bagi program-program serupa di kemudian hari. Meskipun program ini hanya berlangsung singkat, Netscape berhasil membuktikan bahwa pendekatan crowdsourced dapat efektif dalam mengidentifikasi kerentanan keamanan.

Memasuki era 2000-an, beberapa perusahaan besar mulai meluncurkan program Bug Bounty mereka sendiri. Mozilla Foundation meluncurkan program Bug Bounty untuk peramban Firefox mereka pada tahun 2004, menawarkan reward hingga  untuk setiap kerentanan kritis yang ditemukan. Program Mozilla berhasil menarik perhatian komunitas peneliti keamanan dan menghasilkan banyak temuan berharga yang membantu meningkatkan keamanan Firefox secara signifikan.

Tahun 2010 menjadi tonggak sejarah penting dalam perkembangan Bug Bounty ketika Google meluncurkan Chrome Vulnerability Reward Program. Google menawarkan reward yang jauh lebih besar dibandingkan program sebelumnya, dengan hadiah hingga .137,31 untuk kerentanan kritis. Angka ini dipilih sebagai representasi dari "eleet" dalam bahasa hacker (31337). Program Google menjadi sangat sukses dan menginspirasi banyak perusahaan lain untuk mengikuti jejak yang sama. Hingga saat ini, Google telah membayar puluhan juta dolar melalui program Bug Bounty mereka.

Facebook meluncurkan program Bug Bounty pada tahun 2011 dan telah menjadi salah satu program paling sukses di dunia. Facebook menawarkan reward minimal  untuk setiap temuan yang valid dan telah membayar lebih dari  juta kepada peneliti keamanan sejak program dimulai. Program Facebook terkenal dengan proses triase yang cepat dan responsif, menjadikannya salah satu program favorit di kalangan peneliti keamanan.

Microsoft mengikuti tren ini pada tahun 2013 dengan meluncurkan beberapa program Bug Bounty yang mencakup berbagai produk dan layanan mereka. Microsoft menawarkan reward yang sangat besar, hingga .000 untuk kerentanan tertentu dalam produk-produk kritis mereka. Program Bug Bounty Microsoft terus berkembang dan kini mencakup berbagai platform termasuk Azure, Office 365, dan Windows.

Perkembangan signifikan terjadi pada tahun 2012 dengan lahirnya platform Bug Bounty pihak ketiga seperti HackerOne dan Bugcrowd. Platform-platform ini bertindak sebagai perantara antara organisasi dan peneliti keamanan, menyediakan infrastruktur yang diperlukan untuk menjalankan program Bug Bounty. HackerOne, yang didirikan oleh para hacker ethical dan mantan eksekutif Facebook, dengan cepat menjadi platform Bug Bounty terbesar di dunia. Hingga saat ini, HackerOne telah memfasilitasi pembayaran lebih dari  juta kepada peneliti keamanan dan menjadi rumah bagi ribuan program Bug Bounty dari berbagai organisasi di seluruh dunia.

Di Indonesia, perkembangan Bug Bounty masih relatif baru namun menunjukkan tren yang positif. Beberapa perusahaan teknologi Indonesia seperti Gojek, Tokopedia, Bukalapak, dan Traveloka telah meluncurkan program Bug Bounty mereka sendiri. Pemerintah Indonesia melalui Badan Siber dan Sandi Negara (BSSN) juga telah mendorong penerapan program Bug Bounty untuk aplikasi-aplikasi pemerintahan. Program Bug Bounty Indonesia atau yang dikenal dengan "Buka Bug" merupakan salah satu inisiatif kolaboratif yang melibatkan berbagai platform e-commerce di Indonesia.

Perkembangan Bug Bounty juga didorong oleh meningkatnya kesadaran akan pentingnya keamanan siber dan meningkatnya frekuensi serta kompleksitas serangan siber. Organisasi semakin menyadari bahwa pengujian keamanan tradisional yang dilakukan setahun sekali tidak lagi memadai. Program Bug Bounty menawarkan pendekatan yang lebih dinamis dan berkelanjutan, di mana pengujian keamanan dilakukan secara terus-menerus oleh ribuan peneliti dari berbagai belahan dunia.
### 2.3 Platform Bug Bounty

Platform Bug Bounty adalah layanan pihak ketiga yang menyediakan infrastruktur untuk menghubungkan organisasi dengan peneliti keamanan independen. Platform-platform ini menawarkan berbagai fitur termasuk manajemen laporan, sistem triase, pemrosesan pembayaran, dan komunikasi antara organisasi dan peneliti. Berikut adalah platform Bug Bounty utama yang beroperasi saat ini:

HackerOne merupakan platform Bug Bounty terbesar dan paling populer di dunia. Didirikan pada tahun 2012 oleh Alex Rice (mantan kepala keamanan produk Facebook), Michiel Prins, dan Jobert Abma, HackerOne telah menjadi rumah bagi lebih dari 2.000 program Bug Bounty dari berbagai organisasi termasuk Google, Microsoft, Twitter, GitHub, Dropbox, dan Departemen Pertahanan Amerika Serikat. HackerOne memiliki lebih dari 600.000 peneliti keamanan terdaftar yang telah melaporkan lebih dari 300.000 kerentanan yang valid. Platform ini menawarkan berbagai fitur seperti managed bug bounty, private bug bounty, vulnerability disclosure program, penetration testing as a service, dan hacktivity feed untuk transparansi. HackerOne menggunakan sistem reputasi yang dikenal dengan "reputation points" yang membantu organisasi dalam mengidentifikasi peneliti yang kredibel.

Bugcrowd didirikan pada tahun 2012 oleh Casey Ellis dan merupakan salah satu pesaing utama HackerOne. Bugcrowd menawarkan berbagai layanan termasuk managed bug bounty, vulnerability disclosure program, dan penetration testing. Platform ini memiliki lebih dari 300.000 peneliti yang terdaftar dan telah memfasilitasi lebih dari 200.000 laporan kerentanan. Bugcrowd dikenal dengan pendekatan crowdsourced security yang komprehensif dan sistem rating peneliti yang canggih. Beberapa pelanggan Bugcrowd termasuk WhatsApp, Spotify, Fitbit, dan United Airlines. Bugcrowd juga menawarkan fitur Bugcrowd Researcher Platform yang memungkinkan peneliti untuk membangun profil dan portofolio mereka.

Synack merupakan platform Bug Bounty yang sedikit berbeda karena menggabungkan pendekatan crowdsourced dengan pengujian manual oleh peneliti yang telah diverifikasi. Synack hanya mengundang peneliti keamanan yang telah melalui proses verifikasi identitas yang ketat, sehingga kualitas temuan cenderung lebih tinggi. Platform ini didirikan pada tahun 2013 oleh Jay Kaplan dan Mark Kuhr, keduanya mantan analis keamanan NSA. Synack menggunakan teknologi AI dan machine learning untuk membantu dalam proses pengujian dan triase. Pelanggan Synack termasuk berbagai lembaga pemerintah Amerika Serikat dan perusahaan Fortune 500.

Intigriti didirikan pada tahun 2016 di Belgia dan merupakan platform Bug Bounty terkemuka di Eropa. Intigriti menawarkan managed bug bounty, vulnerability disclosure program, dan penetration testing. Platform ini memiliki lebih dari 65.000 peneliti keamanan yang terdaftar dan telah memfasilitasi pembayaran lebih dari 5 juta euro. Intigriti dikenal dengan fokusnya pada kepatuhan GDPR dan standar keamanan Eropa. Beberapa pelanggan Intigriti termasuk Unity, Puma, dan KPN. Intigriti juga menawarkan fitur Intigriti Academy yang menyediakan pelatihan dan sumber daya bagi peneliti keamanan pemula.

Sebagai perbandingan, platform Bugcrowd dan HackerOne menawarkan model self-service yang memungkinkan organisasi untuk menjalankan program secara mandiri, sementara Synack dan Intigriti cenderung menawarkan layanan yang lebih terkelola. Pemilihan platform yang tepat tergantung pada berbagai faktor termasuk ukuran organisasi, anggaran, kompleksitas aplikasi, dan tingkat dukungan yang dibutuhkan. Untuk program Bug Bounty SHYNESv2, penggunaan platform seperti HackerOne atau Bugcrowd dapat dipertimbangkan karena menyediakan infrastruktur yang lengkap dan komunitas peneliti yang besar.

### 2.4 Jenis-Jenis Bug Bounty Programs

Program Bug Bounty dapat diklasifikasikan ke dalam beberapa kategori berdasarkan berbagai kriteria. Pemahaman tentang jenis-jenis program ini penting untuk menentukan model yang paling sesuai dengan kebutuhan organisasi.

Berdasarkan visibilitas dan aksesibilitas, program Bug Bounty dibagi menjadi public program dan private program. Public program atau program publik adalah program yang terbuka untuk semua peneliti keamanan tanpa undangan khusus. Program ini dipublikasikan secara terbuka di platform Bug Bounty dan dapat diikuti oleh siapa saja yang terdaftar. Keuntungan dari public program adalah jumlah peneliti yang sangat besar sehingga cakupan pengujian menjadi lebih luas. Namun, kekurangannya adalah risiko menerima laporan yang tidak berkualitas atau spam dalam jumlah besar. Contoh public program adalah Bug Bounty Google dan Facebook. Private program atau program privat adalah program yang hanya dapat diikuti oleh peneliti yang diundang secara khusus oleh organisasi. Organisasi dapat memilih peneliti berdasarkan reputasi, keahlian, atau kriteria tertentu. Keuntungan dari private program adalah kualitas laporan yang lebih tinggi dan risiko yang lebih terkendali. Namun, cakupan pengujian mungkin lebih terbatas karena jumlah peneliti yang lebih sedikit. Private program biasanya merupakan tahap awal sebelum organisasi memutuskan untuk membuka program mereka ke publik.

Berdasarkan struktur imbalan, program Bug Bounty dibagi menjadi VDP dan paid bounty. VDP atau Vulnerability Disclosure Program adalah program yang menyediakan saluran pelaporan kerentanan tanpa menawarkan imbalan finansial. Organisasi yang menjalankan VDP biasanya berkomitmen untuk menanggapi dan memperbaiki kerentanan yang dilaporkan, namun tidak memberikan reward uang tunai. VDP cocok untuk organisasi dengan anggaran keamanan yang terbatas atau yang baru memulai program Bug Bounty. Paid bounty atau program berbayar adalah program yang menawarkan imbalan finansial untuk setiap kerentanan yang valid. Besaran reward biasanya bervariasi berdasarkan tingkat keparahan kerentanan. Program berbayar cenderung menarik lebih banyak peneliti dan menghasilkan lebih banyak temuan dibandingkan VDP.

Berdasarkan periode waktu, program Bug Bounty dibagi menjadi time-based program dan continuous program. Time-based program adalah program yang berlangsung dalam periode waktu tertentu, misalnya selama 30 hari atau 90 hari. Program ini biasanya diselenggarakan bersamaan dengan acara tertentu atau sebagai bagian dari kampanye keamanan. Keuntungan dari time-based program adalah biaya yang lebih terkendali dan fokus pengujian yang lebih terarah. Continuous program atau program berkelanjutan adalah program yang berlangsung tanpa batas waktu tertentu. Program ini menawarkan pengujian keamanan yang berkelanjutan sepanjang waktu. Keuntungan dari continuous program adalah deteksi kerentanan yang lebih cepat dan respons yang lebih proaktif terhadap ancaman baru.

Untuk aplikasi SHYNESv2 Fashion E-Commerce, model yang paling direkomendasikan adalah memulai dengan private program berbayar yang bersifat continuous. Model ini memungkinkan organisasi untuk mengontrol kualitas laporan dengan mengundang peneliti yang terpercaya, sambil tetap mendapatkan manfaat dari pengujian keamanan yang berkelanjutan. Setelah program privat berjalan dengan baik dan infrastruktur triase sudah matang, program dapat ditingkatkan menjadi public program untuk mendapatkan cakupan pengujian yang lebih luas.

### 2.5 Metodologi Bug Bounty

Metodologi Bug Bounty adalah pendekatan sistematis yang digunakan oleh peneliti keamanan dalam mengidentifikasi, menganalisis, dan melaporkan kerentanan keamanan. Metodologi yang baik memastikan bahwa pengujian dilakukan secara menyeluruh dan hasilnya dapat dipertanggungjawabkan. Berikut adalah tahapan utama dalam metodologi Bug Bounty:

Reconnaissance atau pengintaian merupakan tahap pertama dan salah satu tahap paling penting dalam proses Bug Bounty. Pada tahap ini, peneliti mengumpulkan informasi sebanyak mungkin tentang target yang akan diuji. Teknik reconnaissance mencakup subdomain enumeration menggunakan alat seperti Sublist3r, Amass, atau Subfinder untuk menemukan semua subdomain yang terkait dengan target. Technology fingerprinting dilakukan menggunakan alat seperti Wappalyzer, WhatWeb, atau BuiltWith untuk mengidentifikasi teknologi yang digunakan oleh target, termasuk framework, server web, dan library pihak ketiga. Endpoint discovery dilakukan untuk menemukan semua endpoint API dan halaman web yang mungkin menjadi titik masuk potensial. Information gathering juga mencakup pencarian informasi publik di mesin pencari, repositori kode publik seperti GitHub, dan platform media sosial. Peneliti juga dapat menggunakan teknik Google Dorking untuk menemukan informasi sensitif yang mungkin terekspos secara tidak sengaja.

Scanning merupakan tahap di mana peneliti menggunakan alat otomatis untuk mengidentifikasi kerentanan potensial. Alat scanning yang umum digunakan termasuk Nmap untuk port scanning dan service detection, OWASP ZAP untuk automated web vulnerability scanning, Burp Suite Scanner untuk identifikasi kerentanan web umum, Nuclei untuk template-based scanning, dan Nikto untuk web server scanning. Hasil dari tahap scanning memberikan gambaran awal tentang kerentanan potensial yang perlu divalidasi lebih lanjut pada tahap exploitation.

Exploitation atau eksploitasi merupakan tahap di mana peneliti mencoba untuk memvalidasi dan mengeksploitasi kerentanan yang telah diidentifikasi. Pada tahap ini, peneliti menggunakan teknik manual dan otomatis untuk membuktikan bahwa kerentanan benar-benar dapat dieksploitasi. Penting untuk dicatat bahwa dalam konteks Bug Bounty, eksploitasi hanya dilakukan sebatas yang diperlukan untuk membuktikan adanya kerentanan. Peneliti tidak boleh mengeksploitasi kerentanan secara berlebihan atau menyebabkan kerusakan pada sistem target. Proof of concept (PoC) dibuat pada tahap ini untuk mendokumentasikan langkah-langkah yang diperlukan untuk mengeksploitasi kerentanan.

Reporting merupakan tahap di mana peneliti menyusun laporan yang mendetail tentang kerentanan yang ditemukan. Laporan Bug Bounty yang baik harus mencakup informasi berikut: judul yang jelas dan deskriptif tentang kerentanan, endpoint atau lokasi kerentanan ditemukan, metode HTTP yang digunakan, parameter yang dieksploitasi, deskripsi singkat tentang kerentanan, langkah-langkah reproduksi yang jelas dan terperinci, proof of concept yang menunjukkan eksploitasi, dampak potensial jika kerentanan dieksploitasi, skor CVSS v3.1 yang menunjukkan tingkat keparahan, dan rekomendasi perbaikan. Laporan yang baik dan terstruktur akan mempercepat proses triase dan meningkatkan peluang untuk mendapatkan reward.

Disclosure merupakan tahap akhir dalam metodologi Bug Bounty. Setelah organisasi memperbaiki kerentanan, peneliti dan organisasi dapat mendiskusikan kapan dan bagaimana kerentanan akan diungkapkan ke publik. Responsible disclosure mengharuskan peneliti untuk memberikan waktu yang cukup kepada organisasi untuk memperbaiki kerentanan sebelum informasi tersebut dipublikasikan. Periode embargo biasanya berkisar antara 30 hingga 90 hari tergantung pada tingkat keparahan kerentanan. Setelah kerentanan diperbaiki dan periode embargo berakhir, peneliti dapat mempublikasikan temuan mereka melalui blog, presentasi konferensi, atau platform publikasi kerentanan.

### 2.6 OWASP Testing Guide v4.2

OWASP Testing Guide v4.2 merupakan panduan pengujian keamanan yang dikeluarkan oleh Open Web Application Security Project (OWASP), sebuah organisasi nirlaba internasional yang berfokus pada peningkatan keamanan perangkat lunak. OWASP Testing Guide v4.2 menyediakan kerangka kerja yang komprehensif untuk melakukan pengujian keamanan pada aplikasi web dan menyajikan pendekatan sistematis yang mencakup berbagai aspek keamanan aplikasi.

OWASP Testing Guide v4.2 terdiri dari beberapa fase pengujian yang mencakup Information Gathering, Configuration and Deployment Management Testing, Identity Management Testing, Authentication Testing, Authorization Testing, Session Management Testing, Input Validation Testing, Error Handling Testing, Cryptography Testing, Business Logic Testing, dan Client-Side Testing. Setiap fase berisi serangkaian test case yang dapat digunakan sebagai panduan dalam menguji aspek keamanan tertentu dari aplikasi.

Information Gathering merupakan fase pertama yang berfokus pada pengumpulan informasi tentang aplikasi target. Test case dalam fase ini mencakup pencarian search engine discovery, fingerprinting web server, analisis file robots.txt, dan enumerasi direktori. Fase ini penting untuk memahami permukaan serangan aplikasi sebelum memulai pengujian yang lebih mendalam.

Configuration and Deployment Management Testing berfokus pada pengujian konfigurasi server dan platform aplikasi. Test case mencakup pengujian manajemen konfigurasi, pengujian konfigurasi SSL/TLS, dan pengujian manajemen patch. Konfigurasi yang salah sering kali menjadi penyebab kerentanan yang dapat dieksploitasi dengan mudah.

Authentication Testing berfokus pada pengujian mekanisme autentikasi aplikasi. Test case mencakup pengujian credential transport, pengujian user enumeration, pengujian brute force, pengujian kebijakan password, dan pengujian fungsi remember me dan password reset. Kerentanan pada mekanisme autentikasi dapat menyebabkan akses tidak sah ke akun pengguna.

Authorization Testing berfokus pada pengujian mekanisme otorisasi aplikasi. Test case mencakup pengujian path traversal, pengujian privilege escalation, dan pengujian insecure direct object references (IDOR). Kerentanan otorisasi dapat memungkinkan pengguna untuk mengakses data atau fungsi yang seharusnya tidak dapat mereka akses.

Session Management Testing berfokus pada pengujian mekanisme manajemen sesi aplikasi. Test case mencakup pengujian session fixation, pengujian cookie attributes, dan pengujian session timeout. Manajemen sesi yang lemah dapat menyebabkan session hijacking dan akses tidak sah ke akun pengguna.

Input Validation Testing merupakan fase yang sangat penting karena kerentanan injeksi termasuk yang paling umum ditemukan. Test case mencakup pengujian Reflected Cross-Site Scripting (XSS), Stored Cross-Site Scripting (XSS), SQL Injection, LDAP Injection, ORM Injection, Command Injection, File Inclusion, dan Unvalidated Redirects and Forwards.

Business Logic Testing berfokus pada pengujian logika bisnis aplikasi yang mungkin memiliki kelemahan yang dapat dieksploitasi. Test case mencakup pengujian kemampuan untuk mem-bypass workflow, pengujian integritas data, dan pengujian fungsi yang dapat disalahgunakan. Kerentanan logika bisnis sering kali unik untuk setiap aplikasi dan memerlukan pemahaman mendalam tentang fungsionalitas aplikasi.

### 2.7 CVSS v3.1 Scoring System

Common Vulnerability Scoring System (CVSS) v3.1 adalah kerangka kerja terbuka yang digunakan untuk mengomunikasikan karakteristik dan tingkat keparahan kerentanan perangkat lunak. CVSS dikelola oleh Forum of Incident Response and Security Teams (FIRST) dan telah menjadi standar industri untuk menilai tingkat keparahan kerentanan keamanan. CVSS v3.1 merupakan versi terbaru dari standar ini dan memperkenalkan beberapa perbaikan dari versi sebelumnya.

CVSS v3.1 terdiri dari tiga kelompok metrik: Base Metrics, Temporal Metrics, dan Environmental Metrics. Base Metrics mencakup karakteristik intrinsik dari kerentanan yang tidak berubah seiring waktu dan tidak bergantung pada lingkungan pengguna. Temporal Metrics mencakup karakteristik kerentanan yang berubah seiring waktu, seperti ketersediaan eksploitasi atau patch. Environmental Metrics mencakup karakteristik kerentanan yang spesifik untuk lingkungan pengguna tertentu.

Base Metrics terdiri dari beberapa komponen yang masing-masing memiliki nilai tertentu. Attack Vector (AV) mengukur bagaimana kerentanan dapat dieksploitasi, dengan nilai Network, Adjacent Network, Local, atau Physical. Attack Complexity (AC) mengukur tingkat kesulitan eksploitasi, dengan nilai Low atau High. Privileges Required (PR) mengukur tingkat hak akses yang diperlukan untuk mengeksploitasi kerentanan, dengan nilai None, Low, atau High. User Interaction (UI) mengukur apakah eksploitasi memerlukan interaksi pengguna, dengan nilai None atau Required. Scope (S) mengukur apakah kerentanan mempengaruhi komponen di luar lingkup wewenang keamanan, dengan nilai Unchanged atau Changed.

Selain itu, Base Metrics juga mencakup dampak kerentanan pada tiga aspek keamanan utama. Confidentiality Impact (C) mengukur dampak terhadap kerahasiaan data, dengan nilai High, Low, atau None. Integrity Impact (I) mengukur dampak terhadap integritas data, dengan nilai High, Low, atau None. Availability Impact (A) mengukur dampak terhadap ketersediaan sistem, dengan nilai High, Low, atau None.

Berdasarkan kombinasi nilai-nilai ini, CVSS menghasilkan skor numerik antara 0,0 hingga 10,0. Skor ini kemudian dikategorikan ke dalam tingkat severity. Severity None memiliki skor 0,0 untuk kerentanan Informational. Severity Low memiliki skor 0,1 hingga 3,9. Severity Medium memiliki skor 4,0 hingga 6,9. Severity High memiliki skor 7,0 hingga 8,9. Severity Critical memiliki skor 9,0 hingga 10,0.

Contoh vektor CVSS untuk kerentanan SQL Injection yang memungkinkan akses database tanpa autentikasi adalah CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:H. Vektor ini menunjukkan kerentanan dapat dieksploitasi melalui jaringan (AV:N) dengan kompleksitas rendah (AC:L), tanpa memerlukan hak akses (PR:N) atau interaksi pengguna (UI:N), tidak mengubah scope (S:U), dan memiliki dampak tinggi pada kerahasiaan, integritas, dan ketersediaan (C:H/I:H/A:H). Skor untuk vektor ini adalah 9,8 yang termasuk dalam kategori Critical.

Penggunaan CVSS dalam program Bug Bounty sangat penting karena menyediakan bahasa yang seragam untuk mengomunikasikan tingkat keparahan kerentanan antara peneliti dan organisasi. Dengan menggunakan CVSS, organisasi dapat memprioritaskan perbaikan berdasarkan tingkat keparahan dan memastikan bahwa sumber daya dialokasikan secara efisien. Untuk program Bug Bounty SHYNESv2, CVSS v3.1 akan digunakan sebagai standar dalam menentukan severity setiap temuan dan sebagai dasar dalam menentukan besaran reward yang akan diberikan.
---

## BAB III

## GAMBARAN UMUM APLIKASI SHYNESv2

### 3.1 Arsitektur Aplikasi

SHYNESv2 Fashion E-Commerce merupakan aplikasi berbasis web yang dibangun dengan menggunakan framework Laravel, salah satu framework PHP paling populer yang dikenal dengan kemudahan penggunaannya, dokumentasi yang lengkap, dan fitur keamanan bawaan yang baik. Aplikasi ini dihosting di platform Railway, sebuah Platform as a Service (PaaS) modern yang menyediakan infrastruktur cloud yang mudah diskalakan. Pemilihan Railway sebagai platform hosting didasarkan pada kemudahan deployment, integrasi dengan berbagai service, dan model pricing yang transparan.

Arsitektur aplikasi SHYNESv2 mengikuti pola Model-View-Controller (MVC) yang merupakan standar dalam pengembangan aplikasi Laravel. Pada pola MVC, Model bertanggung jawab untuk mengelola data dan logika bisnis yang berhubungan dengan database, View bertanggung jawab untuk menampilkan antarmuka pengguna, dan Controller bertanggung jawab untuk menangani permintaan dari pengguna dan mengoordinasikan interaksi antara Model dan View. Laravel menyediakan struktur direktori yang terorganisir dengan baik yang memisahkan komponen-komponen ini secara jelas.

Aplikasi SHYNESv2 menggunakan PostgreSQL sebagai database management system (DBMS). PostgreSQL dipilih karena merupakan database relasional yang stabil, memiliki fitur keamanan yang baik, dan mendukung transaksi yang kompleks. PostgreSQL menyediakan fitur-fitur seperti row-level security, enkripsi data, dan kemampuan replikasi yang penting untuk aplikasi e-commerce yang membutuhkan integritas data yang tinggi. Database ini menyimpan seluruh data aplikasi termasuk data pengguna, data produk, data transaksi, data kontrak, dan data konfigurasi.

Arsitektur aplikasi SHYNESv2 terdiri dari beberapa lapisan yang saling terintegrasi. Lapisan presentasi merupakan antarmuka pengguna yang dibangun menggunakan Blade template engine milik Laravel, dikombinasikan dengan JavaScript, CSS, dan berbagai library frontend modern. Lapisan aplikasi berisi logika bisnis yang diimplementasikan dalam Controller, Service class, dan Repository pattern. Lapisan data berisi database PostgreSQL dan sistem caching seperti Redis untuk mempercepat akses data. Lapisan infrastruktur mencakup server web, load balancer, dan komponen jaringan yang mendukung operasional aplikasi.

Komunikasi antar komponen dalam arsitektur SHYNESv2 dilakukan melalui beberapa mekanisme. Permintaan HTTP dari pengguna masuk melalui web server dan diarahkan ke controller yang sesuai berdasarkan routing yang telah didefinisikan. Controller kemudian berinteraksi dengan Model untuk mengakses database dan dengan View untuk menghasilkan respons HTML. Untuk fungsionalitas realtime seperti notifikasi dan update status pesanan, aplikasi menggunakan WebSocket atau layanan push notification. Integrasi dengan payment gateway dan layanan eksternal lainnya dilakukan melalui API HTTP dengan menggunakan Guzzle HTTP client atau library serupa.

Alur data dalam aplikasi SHYNESv2 dimulai ketika pengguna melakukan permintaan melalui browser. Permintaan ini melewati middleware yang menangani autentikasi, otorisasi, validasi input, dan logging. Setelah lolos middleware, permintaan diteruskan ke controller yang sesuai. Controller kemudian memproses permintaan dengan melibatkan service layer dan model untuk mengakses database. Data yang diambil dari database kemudian diolah dan dikirim ke view untuk dirender menjadi halaman HTML. Halaman HTML yang telah dirender kemudian dikirim kembali ke browser pengguna sebagai respons.

### 3.2 Fitur Utama

SHYNESv2 Fashion E-Commerce menyediakan berbagai fitur yang dirancang untuk memenuhi kebutuhan berbagai jenis pengguna. Berikut adalah fitur utama yang terdapat dalam aplikasi SHYNESv2:

Point of Sale (POS) merupakan fitur yang memungkinkan transaksi penjualan secara langsung atau offline. Fitur POS dirancang untuk digunakan di toko fisik yang dimiliki oleh mitra SHYNESv2. Dengan fitur ini, kasir dapat memproses transaksi penjualan, mencetak struk, mengelola stok secara realtime, dan mencatat data penjualan. Fitur POS terintegrasi dengan sistem inventaris dan akuntansi sehingga data penjualan langsung tercatat dalam sistem. Fitur ini mencakup fungsionalitas pemindaian barcode, pemilihan produk, kalkulasi diskon otomatis, pembayaran multi-metode, dan pencetakan struk. Kerentanan pada fitur POS dapat menyebabkan manipulasi harga, diskon tidak sah, atau kebocoran data transaksi.

E-commerce merupakan fitur inti dari SHYNESv2 yang memungkinkan transaksi jual beli secara online. Melalui fitur ini, pembeli dapat menjelajahi katalog produk, menambahkan produk ke keranjang belanja, melakukan checkout, memilih metode pembayaran, dan melacak status pesanan. Fitur e-commerce mencakup fungsionalitas manajemen keranjang belanja, kupon diskon, ongkos kirim, wishlist, riwayat pesanan, dan ulasan produk. Kerentanan pada fitur e-commerce sangat beragam dan mencakup kerentanan injeksi, kerentanan logika bisnis pada proses checkout, penyalahgunaan kupon, dan akses tidak sah ke data pesanan pengguna lain.

Payment Gateway merupakan fitur yang menangani integrasi dengan berbagai penyedia layanan pembayaran. SHYNESv2 mendukung berbagai metode pembayaran termasuk transfer bank, kartu kredit, e-wallet, dan pembayaran melalui gerai ritel. Fitur payment gateway menangani proses pembuatan transaksi pembayaran, verifikasi pembayaran, penanganan callback dari penyedia layanan pembayaran, dan pengelolaan refund. Kerentanan pada fitur payment gateway sangat kritis karena dapat menyebabkan kerugian finansial langsung. Kerentanan umum pada fitur ini mencakup parameter manipulation pada jumlah pembayaran, webhook signature bypass, dan race condition pada proses verifikasi pembayaran.

Contract Management merupakan fitur yang memungkinkan pengelolaan kontrak antara SHYNESv2 dengan mitra bisnis. Fitur ini mencakup pembuatan kontrak, penandatanganan digital, pengelolaan termin pembayaran, monitoring kepatuhan kontrak, dan perpanjangan kontrak. Fitur contract management digunakan untuk mengelola hubungan dengan supplier, distributor, dan mitra bisnis lainnya. Kerentanan pada fitur ini dapat menyebabkan kebocoran data kontrak yang bersifat rahasia, manipulasi ketentuan kontrak, atau akses tidak sah ke dokumen kontrak.

Manajemen Pengguna merupakan fitur yang menangani registrasi, autentikasi, dan manajemen profil pengguna. SHYNESv2 memiliki tiga jenis pengguna utama yaitu admin, supplier, dan pembeli. Admin memiliki akses penuh ke seluruh fitur aplikasi, supplier dapat mengelola produk dan melihat pesanan, sementara pembeli dapat berbelanja dan mengelola akun mereka. Fitur manajemen pengguna mencakup registrasi, login, logout, reset password, manajemen profil, dan manajemen alamat. Kerentanan pada fitur ini mencakup brute force login, weak password policy, user enumeration, dan session hijacking.

Manajemen Produk merupakan fitur yang memungkinkan pengelolaan katalog produk fashion. Fitur ini mencakup penambahan produk baru, pengeditan informasi produk, pengelolaan stok, pengaturan harga, pengelolaan kategori, dan pengelolaan gambar produk. Fitur ini terutama digunakan oleh admin dan supplier. Kerentanan pada fitur ini mencakup insecure direct object references (IDOR) pada akses produk, stored XSS pada deskripsi produk, dan manipulasi harga atau stok.

Manajemen Pesanan merupakan fitur yang menangani siklus hidup pesanan dari pembuatan hingga pengiriman. Fitur ini mencakup pembuatan pesanan, konfirmasi pembayaran, pemrosesan pesanan, pengiriman, dan pengelolaan retur. Kerentanan pada fitur ini mencakup IDOR pada akses pesanan, manipulasi status pesanan, dan kebocoran data pengguna melalui pesanan.

### 3.3 Stack Teknologi

SHYNESv2 Fashion E-Commerce dibangun di atas stack teknologi modern yang dipilih berdasarkan kebutuhan fungsional, keamanan, skalabilitas, dan kemudahan pemeliharaan. Berikut adalah stack teknologi yang digunakan oleh aplikasi SHYNESv2:

Framework Laravel merupakan fondasi utama dari aplikasi SHYNESv2. Laravel versi terbaru digunakan karena menyediakan berbagai fitur keamanan bawaan seperti prepared statements untuk mencegah SQL injection, CSRF protection, XSS protection melalui Blade templating, enkripsi data, hashing password menggunakan bcrypt atau Argon2, dan authentication scaffolding yang terstruktur dengan baik. Laravel juga menyediakan fitur Queue, Scheduler, dan Event System yang mendukung fungsionalitas kompleks aplikasi e-commerce.

PHP sebagai bahasa pemrograman utama yang digunakan oleh Laravel. PHP versi 8.x digunakan karena menyediakan peningkatan performa, fitur bahasa modern seperti attributes, named arguments, match expression, dan constructor property promotion. PHP juga memiliki ekosistem yang luas dengan ribuan paket yang tersedia melalui Composer.

PostgreSQL berfungsi sebagai database management system utama. PostgreSQL digunakan dalam versi terbaru untuk memanfaatkan fitur-fitur seperti partitioning, parallelism, logical replication, dan peningkatan performa query. Penggunaan PostgreSQL memberikan jaminan ACID compliance yang penting untuk transaksi e-commerce.

Railway merupakan platform hosting yang digunakan untuk mendeploy aplikasi SHYNESv2. Railway menyediakan infrastruktur cloud dengan fitur automatic deployment, horizontal scaling, built-in monitoring, dan integrasi dengan GitHub. Railway menggunakan arsitektur container-based yang memungkinkan isolasi lingkungan yang baik antara aplikasi dan database.

Redis digunakan sebagai caching server dan message broker. Redis menyimpan data sesi pengguna, cache query, dan data sementara lainnya yang memerlukan akses cepat. Redis juga digunakan untuk mengelola antrian pekerjaan melalui fitur Queue Laravel.

Nginx atau Apache berfungsi sebagai web server yang melayani permintaan HTTP dan bertindak sebagai reverse proxy. Web server dikonfigurasi dengan pengaturan keamanan standar termasuk pembatasan ukuran request, timeout configuration, dan SSL/TLS termination.

Node.js dan NPM digunakan untuk manajemen aset frontend seperti CSS, JavaScript, dan gambar. Laravel Mix atau Vite digunakan untuk membangun dan mengoptimalkan aset frontend. Beberapa library frontend yang mungkin digunakan termasuk Alpine.js untuk interaktivitas ringan, Tailwind CSS untuk styling, dan library JavaScript lainnya sesuai kebutuhan.

### 3.4 Alur Data dan Interaksi Antar Komponen

Alur data dalam aplikasi SHYNESv2 Fashion E-Commerce melibatkan interaksi yang kompleks antara berbagai komponen aplikasi. Pemahaman tentang alur data ini penting untuk mengidentifikasi titik-titik potensial kerentanan dan merancang pengujian keamanan yang efektif.

Alur data dimulai ketika pengguna mengakses aplikasi melalui browser web. Browser mengirimkan permintaan HTTP ke server yang dihosting di Railway. Permintaan ini pertama kali diterima oleh Nginx atau Apache yang bertindak sebagai web server. Web server melakukan pemeriksaan awal seperti validasi SSL/TLS, pembatasan rate, dan pemeriksaan header keamanan. Setelah lolos pemeriksaan awal, permintaan diteruskan ke aplikasi Laravel.

Aplikasi Laravel menerima permintaan melalui entry point public/index.php. Laravel kemudian memproses permintaan melalui serangkaian middleware. Middleware autentikasi memeriksa apakah pengguna telah login, middleware otorisasi memeriksa apakah pengguna memiliki hak akses yang diperlukan, middleware validasi input memeriksa format data yang dikirimkan, dan middleware logging mencatat aktivitas pengguna. Setelah melewati semua middleware, permintaan diteruskan ke router yang akan menentukan controller dan method yang sesuai.

Controller menerima permintaan yang telah diproses oleh middleware dan router. Controller kemudian memanggil service layer untuk mengeksekusi logika bisnis. Service layer dapat berinteraksi dengan Model untuk mengakses database, dengan Redis untuk mengambil atau menyimpan data cache, dan dengan layanan eksternal melalui API client.

Interaksi dengan database melibatkan serangkaian operasi yang dikelola oleh Laravel Query Builder atau Eloquent ORM. Eloquent ORM menyediakan lapisan abstraksi yang melindungi aplikasi dari SQL injection melalui penggunaan parameter binding. Namun, implementasi yang tidak tepat masih dapat menyebabkan kerentanan seperti N+1 query problem atau mass assignment vulnerability.

Untuk fitur yang memerlukan pemrosesan asynchronous, aplikasi menggunakan Laravel Queue. Pekerjaan seperti pengiriman email notifikasi, pemrosesan pembayaran, dan pembaruan stok ditempatkan dalam antrian dan diproses oleh worker secara background. Redis atau database digunakan sebagai driver antrian.

Integrasi dengan payment gateway melibatkan komunikasi HTTP antara aplikasi SHYNESv2 dengan server penyedia layanan pembayaran. Aplikasi mengirimkan permintaan pembayaran yang berisi informasi transaksi dan menerima respons yang berisi status pembayaran. Callback dari payment gateway diterima melalui webhook endpoint yang telah dikonfigurasi. Keamanan komunikasi ini sangat penting untuk mencegah serangan man-in-the-middle dan manipulasi data pembayaran.

Alur data untuk fitur contract management melibatkan penyimpanan dan pengambilan dokumen kontrak. Dokumen dapat disimpan dalam database sebagai BLOB atau dalam file storage seperti cloud storage. Akses ke dokumen kontrak harus dibatasi hanya untuk pengguna yang memiliki otorisasi yang sesuai.

Secara keseluruhan, alur data dalam aplikasi SHYNESv2 melibatkan banyak titik interaksi yang berpotensi menjadi vektor serangan. Setiap titik di mana data pengguna diterima, diproses, disimpan, atau ditampilkan harus diamankan dengan baik. Pemahaman tentang alur data ini menjadi dasar dalam merancang program Bug Bounty yang efektif.
---

## BAB IV

## PERANCANGAN LINGKUNGAN BUG BOUNTY

### 4.1 Topologi Lingkungan

Perancangan topologi lingkungan Bug Bounty untuk aplikasi SHYNESv2 Fashion E-Commerce merupakan langkah kritis yang menentukan keberhasilan program. Lingkungan yang dirancang harus mampu memfasilitasi pengujian keamanan yang menyeluruh tanpa menimbulkan risiko terhadap operasional aplikasi produksi dan data pengguna yang sebenarnya. Topologi lingkungan yang diusulkan terdiri dari dua lingkungan terpisah yaitu lingkungan produksi dan lingkungan staging.

Lingkungan produksi merupakan lingkungan tempat aplikasi SHYNESv2 berjalan secara live dan melayani transaksi pengguna yang sebenarnya. Lingkungan ini diakses melalui domain utama shynesv2.up.railway.app dan seluruh subdomain di bawahnya yang digunakan untuk fungsionalitas bisnis. Lingkungan produksi menggunakan database PostgreSQL yang berisi data pengguna riil, data transaksi, data pembayaran, dan data sensitif lainnya. Oleh karena itu, lingkungan produksi ditetapkan sebagai out of scope dalam program Bug Bounty. Peneliti keamanan dilarang keras melakukan pengujian pada lingkungan produksi untuk menghindari gangguan terhadap layanan dan risiko kebocoran data pengguna.

Lingkungan staging merupakan lingkungan khusus yang disediakan untuk keperluan pengujian keamanan. Lingkungan ini diakses melalui domain staging.shynesv2.up.railway.app dan merupakan salinan dari lingkungan produksi dengan beberapa penyesuaian penting. Lingkungan staging menggunakan database PostgreSQL yang berisi data dummy atau data anonim yang tidak mengandung informasi pengguna sebenarnya. Lingkungan staging menjadi satu-satunya target yang termasuk dalam scope program Bug Bounty.

[Screenshot: Gambar 4.1 Topologi Lingkungan Bug Bounty SHYNESv2]

`
┌──────────────────────────────────────────┐
│              INTERNET                      │
└─────────┬──────────────┬─────────────────┘
          │              │
          ▼              ▼
┌─────────────────┐ ┌─────────────────┐
│   Production     │ │    Staging       │
│   (Real Data)    │ │  (Dummy Data)    │
│   https://       │ │  https://        │
│   shynesv2.up.   │ │  staging.shynesv2│
│   railway.app    │ │  .up.railway.app │
│   [OUT OF SCOPE] │ │  [IN SCOPE]      │
└─────────────────┘ └─────────────────┘
`

Selain pemisahan domain, lingkungan staging juga dipisahkan secara infrastruktur dari lingkungan produksi. Lingkungan staging dihosting di project Railway yang berbeda atau di service Railway yang terisolasi. Database staging terpisah secara fisik dari database produksi. Konfigurasi network security group atau firewall memastikan bahwa lingkungan staging tidak dapat mengakses lingkungan produksi dan sebaliknya. Isolasi ini mencegah kemungkinan seorang peneliti yang berhasil mendapatkan akses ke server staging dapat menggunakan akses tersebut untuk menyerang server produksi.

Lingkungan staging juga dikonfigurasi dengan logging yang lebih ekstensif dibandingkan lingkungan produksi. Semua aktivitas pengujian dicatat dalam log akses, log aplikasi, dan log database. Log ini dapat digunakan untuk audit dan investigasi jika terjadi pelanggaran aturan oleh peneliti. Logging juga membantu tim keamanan dalam memverifikasi klaim peneliti tentang kerentanan yang ditemukan.

### 4.2 Spesifikasi Lingkungan Staging

Lingkungan staging untuk program Bug Bounty SHYNESv2 dirancang dengan spesifikasi yang mencerminkan lingkungan produksi namun dengan data dummy dan konfigurasi keamanan yang disesuaikan untuk kebutuhan pengujian. Spesifikasi ini mencakup aspek domain, database, aplikasi, akun pengguna, dan konfigurasi keamanan.

Domain yang digunakan untuk lingkungan staging adalah staging.shynesv2.up.railway.app. Domain ini merupakan subdomain dari domain utama yang digunakan untuk mengakses aplikasi staging. Seluruh endpoint API dan halaman web yang tersedia di lingkungan produksi juga tersedia di lingkungan staging dengan fungsionalitas yang identik. SSL/TLS dikonfigurasi menggunakan sertifikat yang valid untuk memastikan komunikasi yang aman antara peneliti dan server staging.

Database PostgreSQL yang digunakan di lingkungan staging berisi data dummy yang telah dianonimkan. Data dummy ini mencakup data pengguna fiktif, data produk fashion, data transaksi simulasi, data kontrak contoh, dan data konfigurasi sistem. Data dummy dibuat dengan menggunakan script seeding Laravel yang menghasilkan data dalam jumlah yang cukup untuk pengujian namun tidak mengandung informasi pribadi yang sebenarnya. Semua data pengguna dalam database staging menggunakan nama, alamat, email, dan nomor telepon fiktif. Data pembayaran menggunakan nomor kartu kredit test yang disediakan oleh payment gateway dalam mode sandbox.

Aplikasi Laravel di lingkungan staging menggunakan konfigurasi khusus yang berbeda dari lingkungan produksi. Mode debug diaktifkan hanya jika diperlukan untuk pengujian tertentu. Cache aplikasi dinonaktifkan atau dikonfigurasi dengan waktu yang pendek untuk memudahkan pengujian. Konfigurasi email diarahkan ke mailtrap atau printer log daripada mengirim email yang sebenarnya.

Payment gateway di lingkungan staging dikonfigurasi dalam mode sandbox atau test mode. Dalam mode ini, transaksi pembayaran tidak benar-benar diproses dan tidak ada uang yang berpindah. Payment gateway test menyediakan nomor kartu kredit test dan skenario pembayaran yang telah ditentukan untuk memfasilitasi pengujian berbagai skenario pembayaran termasuk pembayaran sukses, gagal, dan pending.

Akun test telah disediakan untuk berbagai jenis pengguna dalam lingkungan staging. Akun admin dengan hak akses penuh disediakan dengan kredensial admin_staging/shynes_staging_admin_2026. Akun supplier disediakan dengan kredensial supplier_staging/shynes_staging_supplier_2026. Akun pembeli disediakan dengan kredensial buyer_staging/shynes_staging_buyer_2026. Akun-akun ini memiliki data dummy yang telah diisi sebelumnya untuk memudahkan pengujian.

[Screenshot: Gambar 4.2 Arsitektur Lingkungan Staging]

Konfigurasi keamanan lingkungan staging diatur untuk memungkinkan pengujian keamanan yang komprehensif. Rate limiting dikurangi atau dinonaktifkan untuk memudahkan pengujian brute force dan fuzzing. WAF (Web Application Firewall) dikonfigurasi dalam mode detection saja sehingga tidak memblokir payload pengujian. Beberapa mekanisme keamanan seperti CSRF protection mungkin dinonaktifkan secara selektif untuk memungkinkan pengujian menggunakan alat automated.

### 4.3 Tools yang Disediakan

Untuk mendukung program Bug Bounty SHYNESv2, organisasi menyediakan berbagai tools keamanan yang dapat digunakan oleh peneliti dalam melakukan pengujian. Tools ini dipilih berdasarkan fungsionalitas, popularitas di kalangan peneliti keamanan, dan kemampuannya dalam mengidentifikasi berbagai jenis kerentanan.

Burp Suite Professional merupakan salah satu tools keamanan web paling populer dan komprehensif yang disediakan dalam program ini. Burp Suite menyediakan berbagai fitur termasuk proxy interceptor untuk memodifikasi request dan response, scanner untuk automated vulnerability detection, repeater untuk request modification dan resending, intruder untuk automated parameter fuzzing dan brute force, decoder untuk encoding dan decoding data, dan extender untuk menambahkan fungsionalitas melalui plugin. Lisensi Burp Suite Professional disediakan dalam jumlah terbatas yang dapat digunakan oleh peneliti selama mengikuti program. Burp Suite sangat efektif untuk pengujian kerentanan web umum seperti SQL injection, XSS, CSRF, SSRF, dan berbagai kerentanan lainnya.

OWASP ZAP (Zed Attack Proxy) merupakan tools keamanan web open source yang disediakan dengan konfigurasi awal yang sudah dioptimalkan untuk pengujian aplikasi SHYNESv2. ZAP menyediakan fitur automated scanner, passive scanner, active scanner, fuzzer, dan API untuk integrasi dengan pipeline CI/CD. ZAP telah dikonfigurasi sebelumnya dengan berbagai rules dan plugin yang relevan untuk teknologi yang digunakan oleh SHYNESv2 termasuk Laravel dan PostgreSQL. Alat ini sangat berguna untuk melakukan scanning awal yang cepat untuk mengidentifikasi kerentanan potensial.

Wordlist untuk fuzzing disediakan dalam bentuk koleksi wordlist yang telah dikurasi dan relevan untuk pengujian aplikasi Laravel dan e-commerce. Wordlist ini mencakup daftar direktori umum, parameter umum, payload injeksi, username dan password umum, dan endpoint API yang umum ditemukan dalam aplikasi Laravel. Wordlist disediakan dalam format teks yang dapat digunakan dengan berbagai tools fuzzing termasuk ffuf, gobuster, dirb, dan Burp Suite Intruder.

Dokumentasi API disediakan untuk memudahkan peneliti dalam memahami endpoint API yang tersedia dan parameter yang diperlukan. Dokumentasi API mencakup daftar lengkap endpoint API, metode HTTP yang didukung, parameter request, format response, dan contoh penggunaan. Dokumentasi API disusun dalam format OpenAPI/Swagger dan dapat diakses melalui endpoint /api/documentation di lingkungan staging. Dokumentasi ini membantu peneliti dalam mengidentifikasi potensi kerentanan pada API seperti IDOR, mass assignment, dan authentication bypass.

Selain tools utama di atas, peneliti juga didorong untuk menggunakan tools tambahan sesuai kebutuhan. Beberapa tools yang umum digunakan dalam Bug Bounty termasuk Nuclei untuk template-based scanning, ffuf untuk web fuzzing, Subfinder untuk subdomain enumeration, Amass untuk reconnaissance jaringan, Nmap untuk port scanning, dan Metasploit untuk exploitation testing. Peneliti juga dapat menggunakan browser developer tools untuk menginspeksi request dan response, serta berbagai ekstensi browser untuk keamanan.

### 4.4 Aturan Main (Rules of Engagement)

Aturan main atau Rules of Engagement (RoE) merupakan dokumen penting yang mengatur perilaku peneliti keamanan selama mengikuti program Bug Bounty. Aturan ini dirancang untuk melindungi kepentingan organisasi, menjaga integritas program, dan memastikan bahwa pengujian dilakukan secara etis dan bertanggung jawab. Setiap peneliti wajib membaca, memahami, dan menyetujui aturan ini sebelum memulai pengujian.

Scope pengujian atau cakupan pengujian ditetapkan secara jelas untuk menghindari kebingungan dan pelanggaran. Semua subdomain dan endpoint di bawah domain staging.shynesv2.up.railway.app ditetapkan sebagai in scope. Ini mencakup aplikasi web utama, API endpoint, dan seluruh fitur yang tersedia di lingkungan staging. Setiap kerentanan yang ditemukan di luar scope yang ditetapkan tidak akan dianggap valid dan tidak akan mendapatkan reward.

Out of scope atau hal-hal yang tidak termasuk dalam cakupan pengujian ditetapkan secara eksplisit. Lingkungan produksi di domain shynesv2.up.railway.app dan seluruh subdomainnya dilarang untuk diuji. Server infrastructure termasuk server Railway, database server, caching server, dan komponen infrastruktur lainnya tidak termasuk dalam scope. Layanan pihak ketiga yang diintegrasikan dengan SHYNESv2 seperti payment gateway, email service, dan cloud storage tidak boleh diuji. Serangan Denial of Service (DoS) dan Distributed Denial of Service (DDoS) dilarang keras. Social engineering terhadap karyawan dan pengguna SHYNESv2 juga dilarang. Physical security testing terhadap kantor atau fasilitas fisik organisasi tidak diizinkan.

Batasan operasional ditetapkan untuk mengontrol dampak pengujian terhadap sistem. Peneliti hanya diperbolehkan mengekstrak maksimal 10 record data sebagai bukti konsep (Proof of Concept). Data yang diekstrak tidak boleh berisi informasi pribadi pengguna yang sebenarnya, namun karena lingkungan staging hanya berisi data dummy, batasan ini lebih bersifat formalitas untuk menjaga praktik yang baik. Peneliti dilarang memodifikasi, menghapus, atau merusak data dalam database. Peneliti dilarang mengakses data pengguna lain di luar akun test yang disediakan. Peneliti dilarang melakukan tindakan yang dapat menurunkan performa sistem secara signifikan.

Prosedur pelaporan ditetapkan untuk memastikan setiap temuan dilaporkan dengan format yang konsisten dan lengkap. Peneliti harus melaporkan kerentanan melalui saluran yang telah ditentukan. Setiap laporan harus menyertakan informasi yang cukup untuk memungkinkan tim keamanan memverifikasi dan mereproduksi kerentanan. Peneliti dilarang mempublikasikan kerentanan sebelum organisasi memberikan izin.

Sanksi atas pelanggaran aturan ditetapkan untuk menjaga disiplin dan integritas program. Pelanggaran ringan seperti pelaporan yang tidak lengkap akan mendapatkan peringatan. Pelanggaran sedang seperti pengujian di luar scope tanpa sengaja akan mengakibatkan diskualifikasi temuan. Pelanggaran berat seperti DoS, social engineering, atau pencurian data akan mengakibatkan diskualifikasi permanen, banned dari program, dan potensi tindakan hukum.
---

## BAB V

## PROSES BUG BOUNTY

### 5.1 Alur Program

Alur program Bug Bounty SHYNESv2 dirancang sebagai proses yang terstruktur dan transparan, dimulai dari registrasi peneliti hingga pemberian reward dan disclosure. Setiap tahap dalam alur ini memiliki tujuan dan prosedur yang jelas untuk memastikan program berjalan dengan efektif dan efisien.

[Screenshot: Gambar 5.1 Flowchart Alur Program Bug Bounty]

`
Researcher → Register → Read Rules → Choose Target → Test
    ↓
Find Vulnerability?
    ├── Yes → Dokumentasi → Submit Report
    │         ↓
    │    Triage Team → Verify
    │         ↓
    │    Valid? → Yes → Severity Scoring (CVSS)
    │         ↓              ↓
    │    Reject with        Prioritize → Fix → Retest → Reward
    │    explanation
    │
    └── No → Test another area
`

Tahap pertama dalam alur program adalah registrasi peneliti. Calon peneliti harus mendaftar melalui platform Bug Bounty yang telah ditentukan. Proses registrasi mencakup pengisian data diri, verifikasi identitas, dan penandatanganan Non-Disclosure Agreement (NDA) secara elektronik. Setelah registrasi selesai, peneliti mendapatkan akses ke lingkungan staging dan dokumentasi program.

Tahap kedua adalah pembacaan aturan main. Peneliti wajib membaca dan memahami seluruh aturan yang tercantum dalam Rules of Engagement. Aturan ini mencakup scope pengujian, metode yang diizinkan, batasan operasional, prosedur pelaporan, dan sanksi pelanggaran. Peneliti harus menyetujui aturan ini secara eksplisit sebelum memulai pengujian.

Tahap ketiga adalah pemilihan target dan pengujian. Peneliti dapat memilih fitur atau area aplikasi yang akan diuji sesuai dengan keahlian dan preferensi mereka. Pengujian dilakukan sesuai dengan metodologi yang telah ditentukan, dimulai dari reconnaissance, scanning, hingga exploitation. Peneliti didorong untuk menggunakan tools yang telah disediakan maupun tools pribadi mereka.

Tahap keempat adalah pelaporan temuan. Jika peneliti menemukan kerentanan yang valid, mereka harus mendokumentasikan temuan tersebut dan mengirimkan laporan melalui sistem pelaporan yang telah ditentukan. Laporan harus mengikuti format template yang telah ditetapkan dan menyertakan informasi yang cukup untuk verifikasi.

Tahap kelima adalah triase dan verifikasi. Tim keamanan SHYNESv2 akan meninjau setiap laporan yang masuk, memverifikasi kebenaran temuan, dan mereproduksi kerentanan yang dilaporkan. Proses verifikasi dilakukan dengan cermat untuk memastikan bahwa temuan adalah kerentanan yang valid dan bukan false positive.

Tahap keenam adalah penentuan severity dan prioritas. Setelah temuan diverifikasi, tim keamanan akan menentukan tingkat keparahan kerentanan menggunakan standar CVSS v3.1. Skor CVSS yang dihasilkan akan menentukan prioritas perbaikan dan besaran reward yang akan diberikan.

Tahap ketujuh adalah remediasi. Tim development SHYNESv2 akan memperbaiki kerentanan yang telah diverifikasi sesuai dengan SLA yang telah ditetapkan. Setelah perbaikan selesai, dilakukan pengujian ulang untuk memastikan bahwa perbaikan efektif dan tidak menimbulkan kerentanan baru.

Tahap kedelapan adalah pemberian reward. Setelah perbaikan dikonfirmasi, peneliti akan menerima reward sesuai dengan skema yang telah ditetapkan. Reward dapat dibayarkan melalui transfer bank atau cryptocurrency sesuai dengan preferensi peneliti.

Tahap kesembilan adalah disclosure. Setelah kerentanan diperbaiki dan periode embargo berakhir, informasi tentang kerentanan dapat dipublikasikan. Peneliti dapat mencantumkan nama mereka dalam Hall of Fame dan, untuk temuan Critical dan High, dapat mengajukan permintaan CVE.

### 5.2 Registrasi Researcher

Registrasi peneliti merupakan tahap awal yang penting dalam program Bug Bounty SHYNESv2. Proses registrasi dirancang untuk memverifikasi identitas peneliti, memastikan kepatuhan terhadap aturan program, dan membangun hubungan kerja yang profesional antara organisasi dan peneliti.

Calon peneliti dapat mendaftar melalui platform Bug Bounty yang digunakan oleh SHYNESv2. Platform yang dipilih akan menyediakan formulir registrasi yang mencakup informasi dasar seperti nama lengkap, alamat email yang valid, nama pengguna atau alias yang akan digunakan dalam program, dan informasi kontak yang dapat dihubungi. Calon peneliti juga harus menyertakan portofolio atau bukti kompetensi di bidang keamanan siber, seperti sertifikasi keamanan, pengalaman dalam program Bug Bounty sebelumnya, atau kontribusi dalam komunitas keamanan.

Verifikasi identitas dilakukan untuk memastikan bahwa peneliti adalah individu yang sebenarnya dan bukan entitas fiktif. Proses verifikasi dapat dilakukan melalui beberapa metode, termasuk verifikasi email melalui tautan konfirmasi, verifikasi nomor telepon melalui kode OTP, verifikasi identitas melalui dokumen resmi seperti KTP atau paspor, dan verifikasi melalui akun media sosial profesional seperti LinkedIn. Untuk program privat, verifikasi identitas yang lebih ketat mungkin diperlukan, termasuk wawancara singkat atau referensi dari peneliti lain yang telah dikenal.

Non-Disclosure Agreement (NDA) merupakan dokumen hukum yang harus ditandatangani oleh peneliti sebelum diberikan akses ke lingkungan staging. NDA mengatur kerahasiaan informasi yang diperoleh peneliti selama mengikuti program, termasuk detail tentang kerentanan yang ditemukan, arsitektur aplikasi, data dummy, dan informasi internal lainnya. NDA ditandatangani secara elektronik melalui platform yang menyediakan tanda tangan digital yang sah secara hukum. Pelanggaran terhadap NDA dapat mengakibatkan sanksi hukum dan diskualifikasi dari program.

Setelah registrasi selesai dan NDA ditandatangani, peneliti akan menerima akses ke lingkungan staging. Akses diberikan dalam bentuk akun test dengan hak akses yang telah ditentukan. Peneliti juga akan menerima dokumentasi program yang mencakup Rules of Engagement, spesifikasi lingkungan staging, format pelaporan, dan informasi kontak tim keamanan.

[Screenshot: Gambar 5.2 Tampilan Halaman Registrasi Researcher]

Peneliti yang telah terdaftar akan mendapatkan profil dalam sistem program Bug Bounty. Profil ini mencatat riwayat partisipasi peneliti, termasuk laporan yang telah dikirimkan, reward yang diterima, dan reputasi dalam program. Profil ini dapat digunakan oleh tim keamanan untuk mengevaluasi kredibilitas peneliti dan memberikan akses ke program lanjutan atau program privat lainnya.

### 5.3 Tahap Reconnaissance

Tahap reconnaissance atau pengintaian merupakan fase pertama dan salah satu fase paling kritis dalam proses Bug Bounty. Pada tahap ini, peneliti mengumpulkan informasi sebanyak mungkin tentang target untuk memahami permukaan serangan dan mengidentifikasi titik masuk potensial. Kualitas reconnaissance sangat memengaruhi efektivitas tahap pengujian selanjutnya.

Subdomain enumeration merupakan teknik reconnaissance yang bertujuan untuk menemukan seluruh subdomain yang terkait dengan domain target. Untuk program Bug Bounty SHYNESv2, target utama adalah domain staging.shynesv2.up.railway.app. Peneliti dapat menggunakan alat seperti Sublist3r, Amass, Subfinder, atau dnsrecon untuk melakukan enumerasi subdomain. Hasil enumerasi dapat mengungkap subdomain yang tidak terdokumentasi yang mungkin memiliki fungsionalitas berbeda atau konfigurasi keamanan yang lebih lemah.

[Screenshot: Gambar 5.3 Contoh Output Alat Reconnaissance]

Technology fingerprinting bertujuan untuk mengidentifikasi teknologi yang digunakan oleh aplikasi target. Peneliti dapat menggunakan alat seperti Wappalyzer, WhatWeb, BuiltWith, atau browser developer tools untuk mengidentifikasi framework, server web, library JavaScript, dan teknologi lain yang digunakan. Informasi ini penting karena setiap teknologi memiliki kerentanan yang diketahui dan teknik eksploitasi yang spesifik. Untuk SHYNESv2, peneliti akan menemukan bahwa aplikasi menggunakan Laravel framework, PostgreSQL database, Nginx web server, dan berbagai library frontend.

Endpoint discovery bertujuan untuk menemukan seluruh endpoint API dan halaman web yang tersedia dalam aplikasi. Peneliti dapat menggunakan teknik crawling dengan alat seperti Burp Suite Spider, ZAP Spider, atau scrappy crawler. Selain crawling otomatis, peneliti juga dapat menganalisis file JavaScript, file sitemap.xml, file robots.txt, dan dokumentasi API untuk menemukan endpoint yang mungkin terlewatkan oleh crawler.

Parameter fuzzing merupakan teknik untuk menemukan parameter yang tidak terdokumentasi atau tersembunyi dalam endpoint aplikasi. Peneliti menggunakan alat seperti Burp Suite Intruder, ffuf, atau wfuzz untuk mengirimkan berbagai variasi parameter ke endpoint target dan menganalisis respons yang diterima. Parameter yang tidak terdokumentasi mungkin memberikan akses ke fungsionalitas tersembunyi atau dapat dieksploitasi untuk menyebabkan perilaku yang tidak diinginkan. Wordlist fuzzing yang disediakan dalam program ini mencakup parameter umum dalam aplikasi Laravel dan parameter spesifik untuk aplikasi e-commerce.

Information gathering dari sumber publik juga merupakan bagian penting dari reconnaissance. Peneliti dapat mencari informasi tentang aplikasi SHYNESv2 di mesin pencari menggunakan teknik Google Dorking. Repositori kode publik di GitHub dapat diperiksa untuk menemukan kredensial yang ter-expose, konfigurasi yang salah, atau komentar kode yang mengandung informasi sensitif. Forum diskusi, blog, dan media sosial juga dapat menjadi sumber informasi tentang aplikasi.
### 5.4 Tahap Testing

Tahap testing atau pengujian merupakan inti dari program Bug Bounty di mana peneliti secara aktif mencari dan mengidentifikasi kerentanan keamanan dalam aplikasi. Pengujian dilakukan melalui kombinasi teknik manual dan automated untuk memaksimalkan cakupan dan efektivitas.

Manual testing dilakukan oleh peneliti berdasarkan panduan OWASP Testing Guide v4.2 dan pengalaman pribadi. Pengujian manual memungkinkan peneliti untuk memahami konteks bisnis aplikasi dan mengidentifikasi kerentanan yang mungkin tidak terdeteksi oleh alat otomatis. Peneliti melakukan pengujian dengan berinteraksi langsung dengan aplikasi melalui browser, memodifikasi request menggunakan Burp Suite proxy, dan menganalisis respons yang diterima.

[Screenshot: Gambar 5.4 Contoh Skenario Manual Testing]

Pengujian autentikasi dilakukan untuk mengidentifikasi kelemahan dalam mekanisme login, registrasi, dan manajemen sesi. Peneliti menguji kerentanan brute force dengan mencoba berbagai kombinasi username dan password. Pengujian user enumeration dilakukan untuk menentukan apakah aplikasi mengungkapkan informasi tentang keberadaan akun pengguna. Pengujian session management mencakup pengujian session fixation, session timeout, dan keamanan cookie. Kerentanan pada fitur reset password dan remember me juga diuji.

Pengujian otorisasi dilakukan untuk mengidentifikasi kerentanan yang memungkinkan pengguna mengakses data atau fungsi yang seharusnya tidak dapat mereka akses. Pengujian IDOR (Insecure Direct Object References) dilakukan dengan memanipulasi parameter ID dalam URL atau request body untuk mencoba mengakses data milik pengguna lain. Pengujian privilege escalation dilakukan untuk mencoba meningkatkan hak akses dari pengguna biasa menjadi admin.

Pengujian input validation merupakan salah satu area pengujian yang paling penting dan produktif. Peneliti menguji berbagai jenis kerentanan injeksi termasuk SQL Injection dengan mencoba menyisipkan perintah SQL ke dalam parameter input. Cross-Site Scripting (XSS) diuji dengan mencoba menyisipkan skrip JavaScript ke dalam input yang akan dirender oleh browser pengguna lain. Command Injection, LDAP Injection, XML Injection, dan berbagai jenis injeksi lainnya juga diuji sesuai dengan teknologi yang digunakan oleh aplikasi.

Pengujian logika bisnis dilakukan untuk mengidentifikasi kelemahan dalam alur bisnis aplikasi yang dapat dieksploitasi. Peneliti menguji alur checkout untuk menemukan kerentanan seperti manipulasi harga, penggunaan kupon ganda, atau bypass pembayaran. Alur manajemen stok diuji untuk menemukan kerentanan seperti manipulasi jumlah stok atau over-selling. Alur manajemen pesanan diuji untuk menemukan kerentanan seperti akses tidak sah ke pesanan pengguna lain atau manipulasi status pesanan.

Automated scanning dilakukan menggunakan alat seperti OWASP ZAP dan Burp Suite Scanner untuk mengidentifikasi kerentanan umum dengan cepat. Alat-alat ini melakukan scanning secara otomatis terhadap aplikasi dan menghasilkan laporan kerentanan potensial. Namun, peneliti harus memvalidasi setiap temuan dari alat otomatis karena tingkat false positive yang cukup tinggi. Temuan dari alat otomatis harus diverifikasi secara manual sebelum dilaporkan.

### 5.5 Tahap Pelaporan

Tahap pelaporan merupakan tahap kritis di mana peneliti mengkomunikasikan temuan kerentanan kepada tim keamanan SHYNESv2. Kualitas laporan sangat memengaruhi kecepatan proses triase, validitas temuan, dan besaran reward yang akan diterima. Oleh karena itu, setiap peneliti wajib mengikuti format pelaporan yang telah ditetapkan.

Format laporan yang digunakan dalam program Bug Bounty SHYNESv2 dirancang untuk memastikan bahwa semua informasi yang diperlukan untuk verifikasi dan remediasi tercakup secara lengkap. Setiap laporan harus mencakup judul yang jelas dan deskriptif, severity kerentanan berdasarkan CVSS v3.1, endpoint tempat kerentanan ditemukan, metode HTTP yang digunakan, parameter yang dieksploitasi, deskripsi singkat, langkah-langkah reproduksi, proof of concept, dampak potensial, skor CVSS lengkap, rekomendasi perbaikan, dan lampiran pendukung.

[Screenshot: Gambar 5.5 Format Template Pelaporan]

Berikut adalah template pelaporan yang WAJIB digunakan oleh setiap peneliti:

`markdown
## [Severity] Judul Kerentanan

**Endpoint**: URL endpoint
**Metode**: GET/POST/PUT/DELETE
**Parameter**: parameter yang dieksploitasi

### Deskripsi
[Penjelasan singkat tentang kerentanan]

### Steps to Reproduce
1. [Langkah pertama]
2. [Langkah kedua]
3. [Langkah ketiga]

### Proof of Concept
`(Paste request/response yang relevan)`

### Impact
[Dampak jika kerentanan dieksploitasi]

### CVSS v3.1 Score
Base Score: X.X (Severity)
Vector: CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:N/A:N

### Suggested Fix
[Rekomendasi perbaikan]

### Attachment
- screenshot.png
- burp_request.txt
`

Judul laporan harus mencerminkan jenis kerentanan dan lokasinya secara jelas. Contoh judul yang baik adalah "SQL Injection pada Endpoint /api/products/search yang Memungkinkan Ekstraksi Database" atau "IDOR pada Endpoint /api/orders/detail yang Memungkinkan Akses Pesanan Pengguna Lain". Judul yang baik membantu tim triase untuk memahami temuan dengan cepat.

Deskripsi kerentanan harus menjelaskan secara singkat namun lengkap tentang kerentanan yang ditemukan, termasuk bagaimana kerentanan tersebut muncul dan mengapa hal tersebut merupakan masalah keamanan. Deskripsi harus ditulis dalam bahasa yang jelas dan teknis, namun dapat dipahami oleh pengembang yang mungkin tidak memiliki latar belakang keamanan yang mendalam.

Steps to Reproduce atau langkah-langkah reproduksi harus ditulis secara detail dan terstruktur sehingga tim keamanan dapat mengikuti langkah-langkah tersebut untuk memverifikasi temuan. Setiap langkah harus ditulis dengan jelas dan dalam urutan yang benar. Langkah-langkah reproduksi yang baik memungkinkan tim untuk mereproduksi kerentanan tanpa perlu bertanya lebih lanjut kepada peneliti.

Proof of Concept (PoC) harus menyertakan request dan response HTTP yang relevan, payload yang digunakan, dan bukti visual seperti screenshot yang menunjukkan dampak kerentanan. PoC harus cukup untuk membuktikan bahwa kerentanan benar-benar dapat dieksploitasi. Data sensitif dalam PoC harus dianonimkan jika perlu.

Impact atau dampak kerentanan harus menjelaskan konsekuensi jika kerentanan dieksploitasi oleh pihak jahat. Dampak dapat mencakup kebocoran data, modifikasi data, gangguan layanan, atau dampak bisnis lainnya. Penjelasan dampak membantu tim keamanan dalam memprioritaskan perbaikan.

CVSS v3.1 Score harus mencantumkan base score dan vektor CVSS yang lengkap. Skor CVSS harus dihitung dengan benar berdasarkan karakteristik kerentanan. Peneliti harus menggunakan CVSS calculator resmi untuk memastikan keakuratan perhitungan.

Suggested Fix atau rekomendasi perbaikan harus memberikan saran yang konkret dan dapat ditindaklanjuti oleh tim pengembang. Rekomendasi dapat mencakup penggunaan prepared statements untuk SQL injection, implementasi otorisasi yang tepat untuk IDOR, atau penggunaan output encoding untuk XSS. Rekomendasi yang baik membantu tim pengembang dalam memperbaiki kerentanan dengan cepat dan efektif.

### 5.6 Tahap Triage dan Verifikasi

Tahap triage dan verifikasi merupakan proses di mana tim keamanan SHYNESv2 meninjau setiap laporan yang masuk, memverifikasi kebenaran temuan, dan menentukan tingkat keparahan kerentanan. Proses ini sangat penting untuk memastikan bahwa hanya temuan yang valid yang mendapatkan reward dan bahwa sumber daya perbaikan dialokasikan secara efisien.

Proses triage dimulai ketika laporan diterima oleh sistem pelaporan. Laporan akan masuk ke dalam antrian triase dan diberikan nomor identifikasi unik. Tim triase kemudian akan meninjau laporan secara berurutan berdasarkan waktu penerimaan. Laporan dengan severity Critical dan High akan mendapatkan prioritas lebih tinggi dalam proses triase.

[Screenshot: Gambar 5.6 Alur Triage dan Verifikasi]

Langkah pertama dalam triase adalah pemeriksaan kelengkapan laporan. Tim triase memeriksa apakah laporan menyertakan semua informasi yang diperlukan sesuai dengan format pelaporan. Jika ada informasi yang kurang atau tidak jelas, tim akan menghubungi peneliti untuk meminta klarifikasi atau informasi tambahan. Laporan yang tidak lengkap akan ditunda sampai informasi yang diperlukan diterima.

Langkah kedua adalah verifikasi temuan. Tim triase akan mencoba mereproduksi kerentanan dengan mengikuti langkah-langkah reproduksi yang disediakan oleh peneliti. Jika tim berhasil mereproduksi kerentanan, temuan dianggap valid dan masuk ke tahap selanjutnya. Jika tim tidak dapat mereproduksi kerentanan, mereka akan memeriksa apakah ada kesalahan dalam langkah reproduksi atau apakah kerentanan bergantung pada kondisi tertentu yang tidak disebutkan oleh peneliti. Jika setelah klarifikasi temuan masih tidak dapat direproduksi, laporan akan ditolak dengan penjelasan yang jelas.

Langkah ketiga adalah pengecekan duplikasi. Tim triase memeriksa apakah kerentanan yang dilaporkan sudah pernah ditemukan dan dilaporkan oleh peneliti lain sebelumnya. Program Bug Bounty SHYNESv2 menerapkan kebijakan first-come-first-served, di mana hanya peneliti yang pertama kali melaporkan kerentanan yang akan mendapatkan reward. Jika temuan adalah duplikat, peneliti akan diberitahu dan laporan akan ditutup tanpa reward.

Langkah keempat adalah penentuan severity menggunakan CVSS v3.1. Tim triase akan menghitung skor CVSS berdasarkan karakteristik kerentanan yang telah diverifikasi. Skor CVSS yang dihitung oleh tim triase adalah skor final yang akan digunakan untuk menentukan besaran reward, bukan skor yang dihitung oleh peneliti. Perbedaan skor antara peneliti dan tim triase dapat terjadi dan akan dikomunikasikan kepada peneliti.

Langkah kelima adalah prioritisasi. Berdasarkan severity yang telah ditentukan, kerentanan akan diprioritaskan untuk perbaikan. Kerentanan Critical mendapatkan prioritas tertinggi dan harus segera diperbaiki, diikuti oleh High, Medium, dan Low.

### 5.7 Tahap Remediasi

Tahap remediasi merupakan proses perbaikan kerentanan yang telah diverifikasi oleh tim keamanan. Proses ini melibatkan koordinasi antara tim keamanan, tim development, dan quality assurance untuk memastikan bahwa kerentanan diperbaiki dengan benar dan efektif.

Setelah kerentanan diverifikasi dan severity ditentukan, tim keamanan akan membuat tiket perbaikan dalam sistem manajemen proyek yang digunakan oleh tim development. Tiket ini berisi deskripsi kerentanan, langkah-langkah reproduksi, dampak, rekomendasi perbaikan, dan tenggat waktu perbaikan berdasarkan SLA yang telah ditetapkan.

Remediation Lead bertanggung jawab untuk mengoordinasikan proses perbaikan dengan tim development. Remediation Lead akan memastikan bahwa tiket perbaikan ditugaskan kepada pengembang yang tepat, bahwa perbaikan dilakukan sesuai dengan SLA, dan bahwa tidak ada hambatan yang menghalangi proses perbaikan.

Tim development akan memperbaiki kerentanan sesuai dengan rekomendasi yang diberikan oleh peneliti atau tim keamanan. Perbaikan dapat mencakup modifikasi kode sumber, perubahan konfigurasi, pembaruan library, atau implementasi mekanisme keamanan tambahan. Setiap perbaikan harus di-review oleh pengembang lain untuk memastikan kualitas dan keamanan.

Setelah perbaikan selesai, tim quality assurance akan melakukan pengujian ulang untuk memverifikasi bahwa kerentanan telah diperbaiki dan bahwa perbaikan tidak menimbulkan kerentanan baru atau efek samping yang tidak diinginkan. Pengujian ulang mencakup pengujian fungsional untuk memastikan fitur masih berjalan dengan benar dan pengujian keamanan untuk memastikan kerentanan tidak dapat lagi dieksploitasi.

[Screenshot: Gambar 5.7 Timeline Remediasi]

Setelah pengujian ulang selesai dan perbaikan dikonfirmasi efektif, tim keamanan akan memberitahu peneliti bahwa kerentanan telah diperbaiki. Peneliti dapat melakukan pengujian ulang independen untuk memverifikasi perbaikan. Jika peneliti menemukan bahwa perbaikan tidak efektif, mereka dapat melaporkan temuan tersebut sebagai laporan baru atau sebagai lampiran pada laporan yang sudah ada.

Proses remediasi harus didokumentasikan secara lengkap untuk keperluan audit dan pelaporan. Dokumentasi mencakup tiket perbaikan, perubahan kode yang dilakukan, hasil pengujian ulang, dan komunikasi dengan peneliti. Dokumentasi ini juga berguna untuk mencegah kerentanan serupa di masa mendatang.

### 5.8 Tahap Reward dan Disclosure

Tahap reward dan disclosure merupakan tahap akhir dari siklus Bug Bounty di mana peneliti menerima imbalan atas temuan mereka dan kerentanan dapat diungkapkan ke publik setelah diperbaiki. Tahap ini penting untuk mempertahankan motivasi peneliti dan membangun reputasi program.

Proses pembayaran reward dimulai setelah perbaikan kerentanan dikonfirmasi. Tim keamanan akan mengirimkan notifikasi kepada peneliti bahwa reward telah disetujui dan menanyakan preferensi metode pembayaran. Program Bug Bounty SHYNESv2 mendukung pembayaran melalui transfer bank dan cryptocurrency.

Untuk transfer bank, peneliti harus menyediakan informasi rekening bank yang valid termasuk nama bank, nomor rekening, dan nama pemilik rekening. Pembayaran akan diproses dalam waktu 14 hari kerja setelah semua informasi diterima. Biaya transfer bank ditanggung oleh organisasi.

Untuk cryptocurrency, peneliti dapat memilih untuk menerima pembayaran dalam Bitcoin, Ethereum, atau stablecoin USDT. Peneliti harus menyediakan alamat wallet cryptocurrency yang valid. Pembayaran akan diproses dalam waktu 7 hari kerja setelah alamat wallet dikonfirmasi.

Setelah pembayaran selesai, peneliti akan menerima konfirmasi pembayaran melalui email. Peneliti juga dapat melihat status pembayaran melalui dashboard program Bug Bounty.

Tahap disclosure dimulai setelah kerentanan diperbaiki dan periode embargo berakhir. Periode embargo adalah waktu yang diberikan kepada organisasi untuk memperbaiki kerentanan sebelum informasi tersebut dipublikasikan. Untuk kerentanan Critical dan High, periode embargo adalah 90 hari. Untuk kerentanan Medium dan Low, periode embargo adalah 60 hari. Periode embargo dihitung sejak kerentanan dilaporkan.

Setelah periode embargo berakhir, peneliti diizinkan untuk mempublikasikan temuan mereka melalui blog, presentasi konferensi, atau platform publikasi kerentanan. Peneliti harus memastikan bahwa publikasi tidak mengungkapkan informasi yang dapat membahayakan pengguna atau organisasi. Publikasi harus menyebutkan bahwa kerentanan telah diperbaiki oleh SHYNESv2.

Peneliti juga akan dicantumkan dalam Hall of Fame SHYNESv2 jika mereka menginginkannya. Hall of Fame dipublikasikan di halaman /security/hall-of-fame di aplikasi SHYNESv2. Hall of Fame berisi nama atau alias peneliti, jenis kerentanan yang ditemukan, dan tingkat severity. Peneliti dapat memilih untuk tetap anonim jika mereka tidak ingin nama mereka dipublikasikan.

Untuk kerentanan dengan severity Critical dan High, tim keamanan SHYNESv2 akan mengajukan permintaan CVE (Common Vulnerabilities and Exposures) ke MITRE atau platform CNA yang ditunjuk. CVE memberikan identifikasi standar untuk kerentanan yang memudahkan referensi dan pelacakan di seluruh industri. Peneliti akan dicantumkan sebagai penemu dalam CVE.
---

## BAB VI

## STRUKTUR TIM DAN PERAN

### 6.1 Program Manager

Program Manager merupakan posisi kunci dalam struktur tim program Bug Bounty SHYNESv2. Program Manager bertanggung jawab secara keseluruhan atas perencanaan, pelaksanaan, dan evaluasi program Bug Bounty. Posisi ini memerlukan kombinasi keahlian teknis di bidang keamanan siber dan kemampuan manajemen yang baik.

Tanggung jawab utama Program Manager mencakup perencanaan strategis program yang meliputi penentuan tujuan program, penetapan anggaran, pemilihan platform Bug Bounty, dan perancangan aturan main. Program Manager juga bertanggung jawab atas komunikasi dengan peneliti, termasuk merespons pertanyaan, memberikan klarifikasi tentang aturan, dan menangani keluhan atau masalah yang mungkin timbul.

Program Manager berperan sebagai penghubung utama antara tim Bug Bounty dengan manajemen organisasi. Program Manager secara berkala melaporkan perkembangan program kepada manajemen, termasuk jumlah laporan yang diterima, jumlah kerentanan yang diverifikasi, reward yang dibayarkan, dan perbaikan yang telah dilakukan. Laporan ini membantu manajemen dalam mengevaluasi efektivitas program dan membuat keputusan tentang alokasi sumber daya.

Dalam hal terjadi pelanggaran aturan oleh peneliti, Program Manager bertanggung jawab untuk memutuskan sanksi yang akan diberikan. Keputusan ini harus didasarkan pada aturan yang telah ditetapkan dan diambil setelah mempertimbangkan semua fakta yang relevan. Program Manager juga bertanggung jawab untuk memastikan bahwa program berjalan sesuai dengan kebijakan organisasi dan peraturan hukum yang berlaku.

Program Manager juga bertanggung jawab untuk melakukan evaluasi berkala terhadap program Bug Bounty. Evaluasi mencakup analisis efektivitas program dalam mengidentifikasi kerentanan, analisis biaya-manfaat, dan identifikasi area yang perlu ditingkatkan. Hasil evaluasi digunakan untuk menyempurnakan program di masa mendatang.

### 6.2 Triage Lead

Triage Lead merupakan posisi teknis yang bertanggung jawab atas proses verifikasi dan validasi semua laporan kerentanan yang masuk. Posisi ini memerlukan keahlian teknis yang mendalam di bidang keamanan aplikasi web dan kemampuan analisis yang baik.

Tanggung jawab utama Triage Lead mencakup penerimaan dan pencatatan semua laporan kerentanan yang masuk. Setiap laporan harus dicatat dalam sistem pelacakan dengan informasi yang lengkap termasuk tanggal penerimaan, identitas peneliti, jenis kerentanan, dan status terkini. Sistem pelacakan memungkinkan tim untuk memonitor progres setiap laporan dan memastikan tidak ada laporan yang terlewatkan.

Triage Lead bertanggung jawab untuk memverifikasi setiap laporan dengan mereproduksi kerentanan yang dilaporkan. Proses verifikasi dilakukan dengan mengikuti langkah-langkah reproduksi yang disediakan oleh peneliti. Triage Lead harus memiliki pemahaman yang mendalam tentang aplikasi SHYNESv2 dan teknologi yang digunakan untuk dapat mereproduksi kerentanan dengan akurat.

Setelah kerentanan berhasil direproduksi, Triage Lead bertanggung jawab untuk menentukan tingkat keparahan menggunakan standar CVSS v3.1. Penentuan severity harus dilakukan secara objektif berdasarkan karakteristik kerentanan, bukan berdasarkan opini subjektif. Triage Lead harus memiliki pemahaman yang baik tentang CVSS dan penggunaannya.

Triage Lead juga bertanggung jawab untuk mengidentifikasi laporan duplikat. Sistem pelacakan harus memungkinkan pencarian laporan sebelumnya berdasarkan jenis kerentanan, endpoint, atau parameter yang dieksploitasi. Jika ditemukan duplikasi, Triage Lead akan menutup laporan yang lebih baru dan memberitahu peneliti bahwa temuan mereka adalah duplikat.

Triage Lead bertanggung jawab untuk berkomunikasi dengan peneliti selama proses verifikasi. Jika ada informasi yang kurang atau tidak jelas dalam laporan, Triage Lead akan menghubungi peneliti untuk meminta klarifikasi. Triage Lead juga akan memberitahu peneliti tentang hasil verifikasi, baik temuan diterima atau ditolak beserta alasannya.

### 6.3 Remediation Lead

Remediation Lead merupakan posisi yang bertanggung jawab atas koordinasi proses perbaikan kerentanan yang telah diverifikasi. Posisi ini memerlukan pemahaman tentang proses pengembangan perangkat lunak dan kemampuan koordinasi yang baik.

Tanggung jawab utama Remediation Lead mencakup pembuatan tiket perbaikan untuk setiap kerentanan yang telah diverifikasi. Tiket perbaikan harus mencakup deskripsi kerentanan, langkah-langkah reproduksi, dampak, rekomendasi perbaikan, dan tenggat waktu berdasarkan SLA. Tiket kemudian ditugaskan kepada pengembang yang tepat berdasarkan area aplikasi yang terdampak.

Remediation Lead bertanggung jawab untuk memonitor progres perbaikan dan memastikan bahwa perbaikan selesai sesuai dengan SLA. Jika ada hambatan yang menghalangi proses perbaikan, Remediation Lead akan mengidentifikasi dan menyelesaikan hambatan tersebut. Remediation Lead juga bertanggung jawab untuk mengomunikasikan status perbaikan kepada tim keamanan dan manajemen.

Setelah perbaikan selesai dilakukan oleh tim development, Remediation Lead bertanggung jawab untuk mengoordinasikan pengujian ulang dengan tim quality assurance. Pengujian ulang harus memastikan bahwa kerentanan telah diperbaiki dan tidak ada efek samping yang tidak diinginkan. Remediation Lead juga akan memberitahu Triage Lead bahwa perbaikan telah selesai sehingga peneliti dapat diberitahu.

Remediation Lead bertanggung jawab untuk mendokumentasikan seluruh proses remediasi. Dokumentasi mencakup tiket perbaikan, perubahan kode yang dilakukan, hasil pengujian ulang, dan komunikasi dengan tim development. Dokumentasi ini penting untuk keperluan audit dan sebagai referensi untuk perbaikan di masa mendatang.

Remediation Lead juga bertanggung jawab untuk mengidentifikasi pola kerentanan yang berulang dan merekomendasikan perbaikan sistemik. Misalnya, jika ditemukan banyak kerentanan SQL injection, Remediation Lead dapat merekomendasikan penggunaan ORM yang lebih ketat atau implementasi query parameterization di seluruh aplikasi.

### 6.4 Legal dan Compliance

Legal dan Compliance merupakan posisi yang bertanggung jawab atas aspek hukum dan kepatuhan program Bug Bounty. Posisi ini memerlukan pemahaman tentang hukum siber, kontrak, dan regulasi perlindungan data.

Tanggung jawab utama Legal dan Compliance mencakup penyusunan dan review dokumen hukum program, termasuk Non-Disclosure Agreement (NDA), Rules of Engagement, dan kebijakan disclosure. Dokumen-dokumen ini harus disusun dengan bahasa hukum yang tepat dan sesuai dengan peraturan perundang-undangan yang berlaku.

Legal dan Compliance bertanggung jawab untuk memastikan bahwa program Bug Bounty mematuhi semua peraturan dan hukum yang berlaku, termasuk Undang-Undang Informasi dan Transaksi Elektronik (UU ITE), Peraturan Perlindungan Data Pribadi, dan peraturan lainnya. Kepatuhan hukum sangat penting untuk melindungi organisasi dari risiko hukum.

Legal dan Compliance juga bertanggung jawab untuk menangani masalah hukum yang mungkin timbul selama program berlangsung, termasuk pelanggaran NDA oleh peneliti, sengketa pembayaran, atau klaim hukum lainnya. Legal dan Compliance akan memberikan nasihat hukum kepada tim program dan, jika diperlukan, mengoordinasikan dengan pihak hukum eksternal.

Legal dan Compliance bertanggung jawab untuk mereview kebijakan disclosure dan memastikan bahwa publikasi kerentanan tidak melanggar hukum atau membahayakan kepentingan organisasi. Legal dan Compliance juga akan memberikan persetujuan akhir sebelum kerentanan dipublikasikan.

Dalam hal terjadi pelanggaran aturan yang serius, Legal dan Compliance akan memberikan rekomendasi tentang tindakan hukum yang dapat diambil terhadap peneliti yang melanggar, termasuk potensi tuntutan pidana atau perdata. Namun, pendekatan yang diutamakan adalah penyelesaian secara damai dan diskualifikasi dari program, kecuali untuk pelanggaran yang sangat serius.
---

## BAB VII

## REWARD DAN INCENTIVE STRUCTURE

### 7.1 Skema Reward Berdasarkan Severity

Skema reward dalam program Bug Bounty SHYNESv2 dirancang berdasarkan tingkat keparahan kerentanan yang diukur menggunakan standar CVSS v3.1. Skema ini bertujuan untuk memberikan insentif yang proporsional dengan tingkat risiko kerentanan, sehingga peneliti termotivasi untuk mencari dan melaporkan kerentanan yang paling kritis.

Kerentanan dengan severity Critical mencakup kerentanan dengan skor CVSS 9,0 hingga 10,0. Contoh kerentanan Critical termasuk SQL injection yang memungkinkan akses penuh ke database, remote code execution yang memungkinkan eksekusi kode di server, dan authentication bypass yang memungkinkan akses tanpa kredensial. Reward untuk kerentanan Critical ditetapkan sebesar .500 per temuan. SLA perbaikan untuk kerentanan Critical adalah 7 hari kalender.

Kerentanan dengan severity High mencakup kerentanan dengan skor CVSS 7,0 hingga 8,9. Contoh kerentanan High termasuk IDOR yang memungkinkan akses ke data pengguna lain, stored XSS yang memungkinkan eksekusi skrip di browser pengguna, dan privilege escalation yang memungkinkan peningkatan hak akses. Reward untuk kerentanan High ditetapkan sebesar  per temuan. SLA perbaikan untuk kerentanan High adalah 14 hari kalender.

Kerentanan dengan severity Medium mencakup kerentanan dengan skor CVSS 4,0 hingga 6,9. Contoh kerentanan Medium termasuk reflected XSS, CSRF pada fungsi yang tidak kritis, dan information disclosure yang tidak sensitif. Reward untuk kerentanan Medium ditetapkan sebesar  per temuan. SLA perbaikan untuk kerentanan Medium adalah 30 hari kalender.

Kerentanan dengan severity Low mencakup kerentanan dengan skor CVSS 0,1 hingga 3,9. Contoh kerentanan Low termasuk missing security headers, informasi yang ter-expose namun tidak sensitif, dan improper input validation yang tidak dapat dieksploitasi secara langsung. Reward untuk kerentanan Low ditetapkan sebesar  per temuan. SLA perbaikan untuk kerentanan Low adalah 60 hari kalender.

Kerentanan Informational memiliki skor CVSS 0,0. Contoh kerentanan Informational termasuk saran perbaikan keamanan, best practice yang tidak diikuti, dan temuan yang tidak memiliki dampak keamanan langsung. Tidak ada reward finansial untuk kerentanan Informational. Sebagai gantinya, peneliti akan mendapatkan pengakuan dalam Hall of Fame.

| Severity | CVSS Score | Reward | SLA Fix |
|----------|-----------|--------|---------|
| Critical | 9.0 - 10.0 | .500 | 7 hari |
| High | 7.0 - 8.9 |  | 14 hari |
| Medium | 4.0 - 6.9 |  | 30 hari |
| Low | 0.1 - 3.9 |  | 60 hari |
| Informational | 0.0 | Hall of Fame | 90 hari |

Kebijakan khusus diterapkan untuk kerentanan yang ditemukan pada fitur yang sama atau kerentanan yang saling terkait. Jika seorang peneliti menemukan beberapa kerentanan yang merupakan varian dari kerentanan yang sama pada endpoint yang berbeda, tim keamanan berhak untuk menggabungkan temuan tersebut menjadi satu laporan dengan satu reward. Keputusan ini diambil untuk mencegah abuse di mana peneliti melaporkan kerentanan yang sama berulang kali pada endpoint yang berbeda.

### 7.2 Mekanisme Pembayaran

Mekanisme pembayaran reward dalam program Bug Bounty SHYNESv2 dirancang untuk memberikan fleksibilitas kepada peneliti dalam memilih metode pembayaran yang paling sesuai dengan kebutuhan mereka. Program ini mendukung dua metode pembayaran utama yaitu transfer bank dan cryptocurrency.

Transfer bank merupakan metode pembayaran tradisional yang tersedia untuk semua peneliti. Peneliti harus menyediakan informasi rekening bank yang valid, termasuk nama bank, nomor rekening, nama pemilik rekening, dan kode SWIFT atau IBAN jika diperlukan untuk transfer internasional. Pembayaran melalui transfer bank akan diproses dalam waktu 14 hari kerja setelah semua informasi yang diperlukan diterima. Biaya transfer bank, termasuk biaya transfer internasional jika ada, ditanggung sepenuhnya oleh SHYNESv2. Peneliti tidak akan dikenakan biaya apapun untuk menerima pembayaran.

Cryptocurrency merupakan metode pembayaran modern yang menawarkan kecepatan dan anonimitas yang lebih baik. Peneliti dapat memilih untuk menerima pembayaran dalam Bitcoin (BTC), Ethereum (ETH), atau USDT (ERC-20 atau BEP-20). Peneliti harus menyediakan alamat wallet cryptocurrency yang valid. Pembayaran melalui cryptocurrency akan diproses dalam waktu 7 hari kerja setelah alamat wallet dikonfirmasi. SHYNESv2 akan menanggung biaya transaksi atau gas fee untuk pengiriman cryptocurrency.

Peneliti dapat mengubah preferensi metode pembayaran mereka setiap saat dengan memberitahu tim keamanan. Perubahan metode pembayaran akan berlaku untuk reward berikutnya, tidak untuk reward yang sudah dalam proses pembayaran.

Pembayaran dilakukan setelah kerentanan berhasil diverifikasi dan diperbaiki. Dalam situasi tertentu, tim keamanan dapat memutuskan untuk membayarkan reward sebelum perbaikan selesai, terutama untuk kerentanan dengan severity Critical yang memerlukan waktu perbaikan yang lebih lama. Keputusan ini diambil untuk memastikan peneliti tetap termotivasi meskipun perbaikan memerlukan waktu.

Semua pembayaran dicatat dalam sistem keuangan SHYNESv2 untuk keperluan audit dan pelaporan pajak. Peneliti bertanggung jawab untuk melaporkan reward yang diterima sebagai penghasilan sesuai dengan peraturan perpajakan di negara masing-masing. SHYNESv2 tidak akan memotong pajak dari reward yang dibayarkan, kecuali diwajibkan oleh hukum yang berlaku.

### 7.3 Anggaran Tahunan

Anggaran tahunan program Bug Bounty SHYNESv2 ditetapkan sebesar .000. Anggaran ini dialokasikan untuk membayar reward kepada peneliti, biaya operasional program, dan biaya platform Bug Bounty jika menggunakan platform pihak ketiga.

Alokasi anggaran dirancang untuk memaksimalkan efektivitas program dalam mengidentifikasi kerentanan kritis. Berdasarkan data dari program Bug Bounty lain, mayoritas anggaran biasanya terserap untuk membayar reward kerentanan Critical dan High yang memiliki dampak keamanan paling signifikan. Alokasi anggaran diperkirakan sebagai berikut: 50% untuk kerentanan Critical (.500), 30% untuk kerentanan High (.500), 15% untuk kerentanan Medium (), dan 5% untuk kerentanan Low ().

[Screenshot: Gambar 7.1 Diagram Alokasi Anggaran Bug Bounty]

Anggaran ini ditetapkan untuk program privat tahap awal. Jika program terbukti efektif dan memberikan nilai tambah yang signifikan bagi keamanan aplikasi, anggaran dapat ditingkatkan pada tahun berikutnya. Peningkatan anggaran akan memungkinkan program untuk diperluas menjadi program publik yang melibatkan lebih banyak peneliti.

Untuk mengelola anggaran dengan efektif, program Bug Bounty SHYNESv2 menerapkan sistem monthly budget cap. Setiap bulan, anggaran yang tersedia untuk reward dibatasi. Jika cap bulanan tercapai, laporan baru yang masuk akan tetap diproses dan diverifikasi, namun pembayaran reward akan ditunda ke bulan berikutnya. Sistem ini memastikan bahwa anggaran tidak habis di awal program dan program dapat berjalan sepanjang tahun.

Program Manager bertanggung jawab untuk memonitor penggunaan anggaran dan membuat laporan keuangan bulanan. Laporan ini mencakup total reward yang dibayarkan, jumlah laporan per severity, rata-rata waktu pemrosesan, dan proyeksi penggunaan anggaran untuk sisa tahun. Laporan ini digunakan sebagai dasar untuk pengambilan keputusan tentang alokasi anggaran.

Jika anggaran tahunan tidak terserap seluruhnya, sisa anggaran dapat dialokasikan untuk program Bug Bounty di tahun berikutnya atau digunakan untuk keperluan keamanan lainnya. Jika anggaran tahunan tidak mencukupi, Program Manager dapat mengajukan permintaan tambahan anggaran ke manajemen dengan menyertakan justifikasi dan data pendukung.
---

## BAB VIII

## DISCLOSURE POLICY

### 8.1 Coordinated Disclosure

Coordinated Disclosure merupakan kebijakan disclosure yang diterapkan dalam program Bug Bounty SHYNESv2. Kebijakan ini mengatur bagaimana dan kapan kerentanan yang ditemukan dapat diungkapkan ke publik. Tujuan dari coordinated disclosure adalah untuk menyeimbangkan kepentingan organisasi dalam melindungi keamanan pengguna dengan hak peneliti untuk mendapatkan pengakuan atas temuan mereka.

Prinsip dasar dari coordinated disclosure adalah bahwa peneliti harus melaporkan kerentanan terlebih dahulu kepada tim keamanan SHYNESv2 sebelum mempublikasikannya ke publik. Hal ini memberikan kesempatan kepada organisasi untuk memperbaiki kerentanan sebelum informasi tersebut diketahui oleh pihak-pihak yang mungkin akan mengeksploitasinya. Peneliti dilarang keras untuk mempublikasikan kerentanan sebelum organisasi memberikan izin.

Proses coordinated disclosure dimulai ketika peneliti melaporkan kerentanan melalui saluran yang telah ditentukan. Tim keamanan SHYNESv2 akan memverifikasi kerentanan dan memulai proses perbaikan. Selama proses perbaikan, peneliti dan tim keamanan dapat berkomunikasi untuk mendiskusikan detail kerentanan dan rencana perbaikan.

Setelah kerentanan diperbaiki, tim keamanan akan memberitahu peneliti bahwa perbaikan telah selesai. Peneliti dapat melakukan pengujian ulang untuk memverifikasi efektivitas perbaikan. Setelah perbaikan dikonfirmasi, periode embargo dimulai.

Periode embargo adalah waktu yang diberikan kepada organisasi untuk memastikan bahwa perbaikan telah diterapkan dengan benar di seluruh infrastruktur sebelum informasi kerentanan dipublikasikan. Untuk kerentanan dengan severity Critical dan High, periode embargo adalah 90 hari sejak laporan diterima. Untuk kerentanan dengan severity Medium dan Low, periode embargo adalah 60 hari sejak laporan diterima. Periode embargo dapat diperpanjang jika diperlukan atas kesepakatan kedua belah pihak.

Setelah periode embargo berakhir, peneliti diizinkan untuk mempublikasikan temuan mereka. Publikasi harus mematuhi pedoman berikut: tidak mengungkapkan informasi yang dapat membahayakan pengguna atau organisasi, menyebutkan bahwa kerentanan telah diperbaiki oleh SHYNESv2, tidak mengungkapkan data pengguna yang sebenarnya, dan menggunakan bahasa yang profesional dan tidak provokatif.

### 8.2 Hall of Fame

Hall of Fame merupakan halaman penghargaan yang dipublikasikan di aplikasi SHYNESv2 yang menampilkan nama atau alias peneliti yang telah berkontribusi dalam program Bug Bounty. Hall of Fame bertujuan untuk memberikan pengakuan publik kepada peneliti atas kontribusi mereka dalam meningkatkan keamanan aplikasi SHYNESv2.

Hall of Fame diakses melalui halaman /security/hall-of-fame di aplikasi SHYNESv2. Halaman ini menampilkan daftar peneliti yang telah melaporkan kerentanan yang valid, beserta informasi tentang temuan mereka. Informasi yang ditampilkan mencakup nama atau alias peneliti, jenis kerentanan yang ditemukan, tingkat severity, dan tanggal laporan. Halaman Hall of Fame diperbarui secara berkala setiap kali ada peneliti baru yang memenuhi syarat.

Peneliti dapat memilih untuk tetap anonim jika mereka tidak ingin nama atau alias mereka dipublikasikan. Peneliti yang memilih anonim akan dicantumkan sebagai "Anonymous Researcher" dalam Hall of Fame. Keputusan untuk anonim atau tidak dapat diubah setiap saat dengan memberitahu tim keamanan.

Peneliti yang namanya tercantum dalam Hall of Fame mendapatkan hak istimewa tertentu dalam program. Hak istimewa ini mencakup undangan ke program privat lanjutan, prioritas dalam proses triase laporan, dan akses ke fitur atau environment pengujian tambahan. Hak istimewa ini diberikan sebagai bentuk apresiasi tambahan atas kontribusi peneliti.

Selain Hall of Fame online, SHYNESv2 juga dapat memberikan penghargaan tambahan kepada peneliti dengan kontribusi luar biasa. Penghargaan ini dapat berupa sertifikat penghargaan, merchandise eksklusif, atau undangan ke acara khusus. Penghargaan tambahan diberikan berdasarkan kebijakan diskresi tim keamanan.

### 8.3 CVE Assignment

CVE (Common Vulnerabilities and Exposures) merupakan sistem identifikasi standar untuk kerentanan keamanan yang dikelola oleh MITRE Corporation. CVE memberikan identifikasi unik untuk setiap kerentanan yang memudahkan referensi dan pelacakan di seluruh industri. Dalam program Bug Bounty SHYNESv2, CVE assignment dilakukan untuk kerentanan dengan severity Critical dan High.

Proses CVE assignment dimulai ketika kerentanan dengan severity Critical atau High telah diverifikasi dan diperbaiki. Tim keamanan SHYNESv2 akan mengajukan permintaan CVE ke MITRE atau ke CNA (CVE Numbering Authority) yang telah ditunjuk. Pengajuan CVE mencakup informasi tentang kerentanan termasuk deskripsi, produk yang terdampak, versi yang terdampak, dampak, dan referensi.

Setelah CVE diterbitkan, kerentanan akan mendapatkan nomor identifikasi unik dalam format CVE-YYYY-NNNNN. Nomor CVE ini akan digunakan dalam semua komunikasi tentang kerentanan, termasuk dalam publikasi disclosure, laporan keamanan, dan database kerentanan. Peneliti akan dicantumkan sebagai penemu dalam entri CVE.

CVE assignment memberikan beberapa manfaat bagi program Bug Bounty SHYNESv2. Pertama, CVE meningkatkan kredibilitas program dengan menunjukkan bahwa organisasi serius dalam menangani keamanan. Kedua, CVE memudahkan pelacakan kerentanan di seluruh industri dan membantu organisasi lain yang mungkin menggunakan teknologi serupa. Ketiga, CVE memberikan pengakuan tambahan kepada peneliti yang dapat digunakan untuk membangun portofolio dan reputasi mereka.

Untuk kerentanan dengan severity Medium dan Low, CVE assignment tidak dilakukan secara otomatis. Tim keamanan akan mengevaluasi kasus per kasus apakah CVE diperlukan. Keputusan untuk memberikan CVE untuk kerentanan Medium atau Low didasarkan pada faktor-faktor seperti keunikan kerentanan, potensi dampak luas, dan permintaan dari peneliti.
---

## BAB IX

## USULAN PERBAIKAN DARI HASIL PENTEST

### 9.1 Prioritas Tinggi

Usulan perbaikan prioritas tinggi merupakan rekomendasi yang wajib diperbaiki sebelum program Bug Bounty dimulai atau segera setelah ditemukan. Kerentanan dalam kategori ini memiliki dampak keamanan yang signifikan dan dapat menyebabkan kerugian besar jika dieksploitasi. Rekomendasi ini didasarkan pada temuan dari pengujian keamanan yang dilakukan oleh peneliti sebelumnya (Orang 2 dan Orang 3).

Race condition pada proses checkout merupakan kerentanan kritis yang memungkinkan pengguna untuk memproses beberapa pesanan secara simultan, yang dapat mengakibatkan manipulasi stok, pembayaran ganda, atau pengguna mendapatkan keuntungan yang tidak sah. Kerentanan ini terjadi karena kurangnya mekanisme penguncian yang memadai pada proses checkout. Rekomendasi perbaikannya adalah mengimplementasikan pessimistic locking pada database saat pemrosesan checkout. Dengan pessimistic locking, ketika seorang pengguna sedang memproses checkout, data terkait akan dikunci sehingga pengguna lain tidak dapat memproses checkout pada produk yang sama secara bersamaan. Implementasi pessimistic locking dapat dilakukan dengan menggunakan fitur SELECT FOR UPDATE pada PostgreSQL yang akan mengunci baris data yang sedang diakses hingga transaksi selesai. Selain itu, penggunaan database transactions dengan isolation level SERIALIZABLE juga dapat mencegah race condition.

Brute force login merupakan kerentanan yang memungkinkan penyerang untuk menebak password pengguna dengan mencoba berbagai kombinasi username dan password secara otomatis. Kerentanan ini terjadi karena tidak adanya mekanisme pembatasan percobaan login yang gagal. Rekomendasi perbaikannya adalah mengimplementasikan rate limiting dan captcha pada halaman login. Rate limiting dapat diimplementasikan dengan membatasi jumlah percobaan login dari satu alamat IP dalam periode waktu tertentu, misalnya maksimal 5 percobaan dalam 1 menit. Setelah melebihi batas, pengguna harus menunggu sebelum dapat mencoba lagi. Captcha dapat diimplementasikan menggunakan Google reCAPTCHA atau solusi captcha lainnya yang memerlukan verifikasi manusia sebelum login. Selain itu, implementasi account locking setelah sejumlah percobaan login yang gagal juga dapat diterapkan, misalnya mengunci akun setelah 10 percobaan login gagal selama 30 menit.

Weak password policy merupakan kerentanan yang memungkinkan pengguna untuk memilih password yang lemah dan mudah ditebak. Kerentanan ini terjadi karena tidak adanya validasi kekuatan password pada saat registrasi dan perubahan password. Rekomendasi perbaikannya adalah mengimplementasikan validasi password yang ketat sesuai dengan standar keamanan. Password harus memiliki panjang minimal 8 karakter, mengandung kombinasi huruf besar, huruf kecil, angka, dan karakter khusus. Password tidak boleh sama dengan username, email, atau informasi pribadi pengguna lainnya. Laravel menyediakan fitur validasi password melalui rule Password::min() yang dapat dikonfigurasi sesuai kebutuhan. Selain itu, penggunaan library password strength estimator seperti zxcvbn juga dapat membantu dalam mengevaluasi kekuatan password.

Webhook signature verification merupakan kerentanan yang memungkinkan penyerang untuk memalsukan notifikasi webhook dari payment gateway. Kerentanan ini terjadi karena tidak adanya atau lemahnya verifikasi signature pada webhook endpoint. Rekomendasi perbaikannya adalah mengimplementasikan verifikasi HMAC (Hash-based Message Authentication Code) pada setiap webhook request. Payment gateway biasanya mengirimkan signature dalam header request yang dihasilkan dari kombinasi payload request dan secret key yang hanya diketahui oleh SHYNESv2 dan payment gateway. Server harus memverifikasi signature ini dengan menghitung HMAC dari payload request menggunakan secret key yang sama dan membandingkannya dengan signature yang diterima. Jika signature tidak cocok, request harus ditolak. Implementasi verifikasi signature harus dilakukan untuk semua webhook endpoint, termasuk notifikasi pembayaran sukses, gagal, refund, dan notifikasi lainnya.

### 9.2 Prioritas Sedang

Usulan perbaikan prioritas sedang merupakan rekomendasi yang penting untuk diperbaiki namun tidak bersifat kritis. Kerentanan dalam kategori ini memiliki dampak keamanan yang terbatas atau memerlukan kondisi tertentu untuk dapat dieksploitasi.

Multiple coupon usage merupakan kerentanan yang memungkinkan pengguna untuk menggunakan kupon diskon lebih dari sekali, baik oleh pengguna yang sama maupun oleh pengguna yang berbeda secara tidak sah. Kerentanan ini terjadi karena kurangnya validasi penggunaan kupon per pengguna. Rekomendasi perbaikannya adalah mengimplementasikan unique constraint pada database untuk penggunaan kupon per pengguna, misalnya dengan menambahkan unique index pada kolom user_id dan coupon_id dalam tabel coupon_usage. Selain itu, validasi penggunaan kupon juga harus dilakukan di sisi server sebelum kupon diterapkan, bukan hanya di sisi klien. Pengecekan harus memastikan bahwa kupon belum pernah digunakan oleh pengguna yang sama, bahwa kupon masih dalam periode berlaku, dan bahwa kupon belum mencapai batas maksimum penggunaan.

Rate limiting API merupakan kerentanan yang memungkinkan penyerang untuk melakukan request berlebihan ke API endpoint, yang dapat menyebabkan penurunan performa atau denial of service. Kerentanan ini terjadi karena tidak adanya atau lemahnya pembatasan rate pada API endpoint. Rekomendasi perbaikannya adalah mengimplementasikan throttle middleware pada route API. Laravel menyediakan middleware throttle bawaan yang dapat dengan mudah dikonfigurasi untuk membatasi jumlah request per menit dari satu alamat IP. Konfigurasi throttle harus disesuaikan untuk setiap endpoint berdasarkan fungsionalitasnya. Endpoint yang sensitif seperti login, registrasi, dan reset password harus memiliki batas yang lebih ketat dibandingkan endpoint yang kurang sensitif. Implementasi throttle juga harus mencakup pengiriman header HTTP yang sesuai seperti X-RateLimit-Limit, X-RateLimit-Remaining, dan Retry-After untuk memberi tahu klien tentang batas rate.

Session timeout merupakan kerentanan yang memungkinkan sesi pengguna tetap aktif untuk waktu yang lama, meningkatkan risiko session hijacking. Kerentanan ini terjadi karena konfigurasi session lifetime yang terlalu panjang. Rekomendasi perbaikannya adalah mengkonfigurasi session lifetime yang sesuai dengan tingkat risiko aplikasi. Untuk aplikasi e-commerce, session lifetime maksimal 30 menit adalah konfigurasi yang umum. Setelah session timeout, pengguna harus login kembali untuk melanjutkan aktivitas. Konfigurasi session lifetime dapat diatur dalam file config/session.php di Laravel. Selain itu, implementasi session timeout juga harus mencakup timeout absolut di mana sesi akan berakhir setelah periode waktu tertentu meskipun pengguna aktif, misalnya 8 jam. Fitur remember me jika diimplementasikan harus menggunakan token yang aman dan memiliki masa berlaku yang terbatas.

### 9.3 Prioritas Rendah

Usulan perbaikan prioritas rendah merupakan rekomendasi yang baik untuk diperbaiki namun tidak mendesak. Kerentanan dalam kategori ini biasanya tidak dapat dieksploitasi secara langsung atau memiliki dampak keamanan yang minimal.

HSTS header merupakan header keamanan HTTP yang memberitahu browser untuk hanya mengakses situs melalui HTTPS, tidak melalui HTTP yang tidak aman. Kerentanan ini terjadi karena header HSTS tidak dikonfigurasi pada respons server. Rekomendasi perbaikannya adalah mengimplementasikan middleware yang menambahkan header Strict-Transport-Security pada setiap respons HTTP. Laravel menyediakan mekanisme untuk menambahkan header keamanan melalui middleware kustom. Konfigurasi HSTS harus mencakup max-age yang cukup panjang, misalnya 31536000 detik (1 tahun), dan includeSubDomains untuk menerapkan HSTS ke semua subdomain. Penerapan HSTS memastikan bahwa browser tidak akan pernah mengirimkan request HTTP ke domain SHYNESv2, mencegah serangan downgrade dan man-in-the-middle.

Information disclosure merupakan kerentanan yang menyebabkan informasi sensitif atau internal terekspos melalui respons server. Kerentanan ini terjadi karena debug fields atau informasi teknis yang tidak sengaja disertakan dalam respons API atau halaman error. Rekomendasi perbaikannya adalah menghapus semua debug fields dari respons di lingkungan produksi. Informasi yang mungkin terekspos termasuk stack trace, query SQL, path file, konfigurasi server, dan informasi internal lainnya. Laravel menyediakan konfigurasi APP_DEBUG di file .env yang harus diatur ke false di lingkungan produksi. Selain itu, custom error handling harus diimplementasikan untuk menampilkan halaman error yang generik tanpa mengungkapkan informasi teknis. Semua informasi sensitif harus dihapus dari respons API, termasuk header server, header X-Powered-By, dan informasi versi.

Overstock validation merupakan kerentanan yang memungkinkan pengguna untuk membeli produk dalam jumlah yang melebihi stok yang tersedia. Kerentanan ini terjadi karena validasi stok yang tidak memadai atau tidak dilakukan di sisi server. Rekomendasi perbaikannya adalah mengimplementasikan validasi stok yang ketat di sisi server sebelum pesanan diproses. Validasi harus memeriksa ketersediaan stok untuk setiap item dalam pesanan dan memastikan bahwa jumlah yang dipesan tidak melebihi stok yang tersedia. Validasi harus dilakukan dalam database transaction untuk mencegah race condition. Jika stok tidak mencukupi, proses checkout harus ditolak dan pengguna harus diberitahu tentang item yang tidak tersedia. Selain itu, sistem harus mengimplementasikan mekanisme pengurangan stok sementara (temporary hold) ketika pengguna memulai proses checkout untuk mencegah pengguna lain membeli produk yang sama.
---

## BAB X

## ANALISIS RISIKO PROGRAM

### 10.1 Risiko Researcher Nakal

Risiko researcher nakal merupakan salah satu risiko terbesar dalam program Bug Bounty. Researcher nakal adalah peneliti yang dengan sengaja melanggar aturan program, melakukan tindakan yang merugikan organisasi, atau menyalahgunakan akses yang diberikan. Risiko ini mencakup berbagai skenario termasuk serangan Denial of Service (DoS) terhadap aplikasi staging, ekstraksi data berlebihan yang melebihi batas yang ditentukan, eksfiltrasi data dummy yang mungkin mengandung informasi sensitif, dan pengungkapan kerentanan sebelum waktunya.

Mitigasi utama untuk risiko researcher nakal adalah penetapan aturan yang jelas dan tegas dalam Rules of Engagement. Aturan harus mencakup definisi yang jelas tentang tindakan yang dilarang, batasan yang ketat tentang apa yang boleh dan tidak boleh dilakukan, dan sanksi yang tegas bagi pelanggar. Aturan ini harus ditandatangani secara elektronik oleh peneliti sebagai bentuk persetujuan.

Selain aturan, mitigasi teknis juga diperlukan untuk mendeteksi dan mencegah tindakan nakal. Logging yang ekstensif harus diimplementasikan di lingkungan staging untuk mencatat semua aktivitas peneliti. Log ini harus mencakup log akses, log aplikasi, log database, dan log keamanan. Sistem deteksi anomali dapat digunakan untuk mengidentifikasi pola aktivitas yang mencurigakan, seperti request dalam jumlah besar yang mengindikasikan serangan DoS atau akses ke data dalam jumlah besar yang mengindikasikan eksfiltrasi data.

Implementasi IP banning dan rate limiting di lingkungan staging dapat mencegah serangan DoS. Jika seorang peneliti terbukti melakukan serangan DoS, alamat IP mereka akan diblokir dan akses ke lingkungan staging akan dicabut. Untuk pelanggaran serius, peneliti akan didiskualifikasi secara permanen dari program dan dilarang mengikuti program di masa mendatang.

Perjanjian Non-Disclosure Agreement (NDA) yang mengikat secara hukum memberikan perlindungan hukum bagi organisasi jika peneliti melanggar kerahasiaan. NDA harus mencakup ketentuan tentang kerahasiaan informasi yang diperoleh selama program, larangan pengungkapan tanpa izin, dan konsekuensi hukum atas pelanggaran. Meskipun NDA tidak dapat sepenuhnya mencegah pelanggaran, NDA menyediakan dasar hukum untuk tindakan hukum jika diperlukan.

### 10.2 Risiko False Positive Berlebihan

Risiko false positive berlebihan merupakan risiko yang umum terjadi dalam program Bug Bounty, terutama ketika program dibuka untuk publik. False positive adalah laporan kerentanan yang ternyata bukan kerentanan setelah diverifikasi. Laporan false positive yang berlebihan dapat menghabiskan sumber daya tim triase yang terbatas dan memperlambat proses verifikasi laporan yang valid.

Penyebab utama false positive meliputi kurangnya pemahaman peneliti tentang aplikasi yang diuji, penggunaan alat automated yang menghasilkan banyak false positive tanpa verifikasi manual, kesalahan interpretasi dari respons server, dan pengujian yang dilakukan pada konfigurasi yang tidak relevan.

Mitigasi utama untuk risiko false positive adalah pembentukan tim triase yang kompeten dan berpengalaman. Tim triase harus memiliki pemahaman yang mendalam tentang aplikasi SHYNESv2 dan teknologi yang digunakan, serta mampu membedakan antara kerentanan nyata dan false positive. Tim triase juga harus memiliki kemampuan untuk mereproduksi laporan dengan cepat dan akurat.

Penerapan quality gate pada laporan yang masuk juga dapat mengurangi false positive. Quality gate adalah serangkaian pemeriksaan otomatis yang dilakukan pada setiap laporan sebelum masuk ke antrian triase. Pemeriksaan ini mencakup validasi format laporan, pemeriksaan kelengkapan informasi, dan deteksi pola laporan yang mencurigakan. Laporan yang tidak memenuhi quality gate akan ditolak secara otomatis dengan permintaan perbaikan.

Edukasi peneliti juga merupakan strategi mitigasi yang efektif. Menyediakan dokumentasi yang jelas tentang aplikasi, aturan program, dan format pelaporan dapat membantu peneliti dalam menyusun laporan yang lebih akurat. Contoh laporan yang baik dan buruk juga dapat membantu peneliti memahami ekspektasi kualitas laporan.

### 10.3 Risiko Budget Tidak Mencukupi

Risiko budget tidak mencukupi merupakan risiko finansial yang dapat terjadi jika jumlah temuan valid melebihi anggaran yang telah dialokasikan. Risiko ini terutama relevan untuk program Bug Bounty publik di mana jumlah peneliti dan laporan dapat meningkat secara signifikan tanpa peringatan.

Penyebab utama risiko ini meliputi underestimasi jumlah temuan yang akan diterima, kerentanan dengan severity tinggi yang tidak terduga dalam jumlah besar, peningkatan partisipasi peneliti yang tidak terduga, dan fluktuasi nilai tukar mata uang untuk program yang menggunakan mata uang asing.

Mitigasi utama untuk risiko budget adalah memulai program dengan model private bug bounty terlebih dahulu. Program privat memungkinkan organisasi untuk mengontrol jumlah peneliti yang berpartisipasi dan memperkirakan jumlah laporan yang akan diterima dengan lebih akurat. Setelah program privat berjalan stabil dan organisasi memiliki data yang cukup tentang biaya program, program dapat ditingkatkan menjadi publik secara bertahap.

Penerapan monthly budget cap juga merupakan strategi mitigasi yang efektif. Dengan menetapkan batas pengeluaran bulanan, organisasi dapat mengontrol aliran kas dan memastikan bahwa anggaran tidak habis di awal program. Jika cap bulanan tercapai, reward untuk laporan baru dapat ditunda ke bulan berikutnya. Kebijakan ini harus dikomunikasikan secara jelas kepada peneliti untuk menghindari kekecewaan.

Program Manager harus secara aktif memonitor penggunaan anggaran dan membuat proyeksi pengeluaran secara berkala. Jika proyeksi menunjukkan bahwa anggaran akan tidak mencukupi, Program Manager dapat mengajukan permintaan tambahan anggaran ke manajemen. Permintaan ini harus disertai dengan justifikasi yang kuat, termasuk data tentang jumlah dan severity temuan, dampak keamanan dari temuan, dan perbandingan dengan biaya pengujian keamanan tradisional.

### 10.4 Risiko Duplicate Reports

Risiko duplicate reports merupakan risiko yang terjadi ketika beberapa peneliti melaporkan kerentanan yang sama secara independen. Dalam program Bug Bounty, hanya peneliti pertama yang melaporkan kerentanan yang berhak mendapatkan reward. Laporan duplikat yang tidak tertangani dengan baik dapat menyebabkan kekecewaan peneliti dan menurunkan motivasi mereka.

Penyebab utama duplicate reports meliputi kurangnya koordinasi antar peneliti, kerentanan yang jelas dan mudah ditemukan oleh banyak peneliti, ketiadaan sistem yang memadai untuk mendeteksi duplikasi, dan keterlambatan dalam pemrosesan laporan yang menyebabkan peneliti lain melaporkan kerentanan yang sama.

Mitigasi utama untuk risiko duplicate reports adalah penerapan kebijakan first-come-first-served yang jelas dan transparan. Kebijakan ini menyatakan bahwa hanya peneliti pertama yang melaporkan kerentanan yang akan mendapatkan reward. Peneliti yang melaporkan kerentanan yang sama setelahnya akan diberitahu bahwa temuan mereka adalah duplikat dan tidak akan mendapatkan reward.

Sistem pelacakan laporan yang baik sangat penting untuk mendeteksi duplikasi. Sistem harus memiliki fitur pencarian yang memungkinkan tim triase untuk mencari laporan yang sudah ada berdasarkan endpoint, parameter, jenis kerentanan, atau kata kunci lainnya. Sistem juga harus memberikan notifikasi kepada peneliti jika laporan mereka terdeteksi sebagai duplikat.

Komunikasi yang baik dengan peneliti juga penting dalam menangani duplicate reports. Peneliti yang melaporkan kerentanan duplikat harus diberitahu dengan sopan dan diberi penjelasan yang jelas mengapa laporan mereka dianggap duplikat. Informasi tentang laporan asli, seperti tanggal pelaporan dan peneliti yang pertama melaporkan, dapat diberikan untuk transparansi.

Untuk mengurangi risiko duplikasi, organisasi dapat mempertimbangkan untuk memberikan partial credit atau duplicate bonus kepada peneliti yang melaporkan kerentanan dalam waktu singkat setelah laporan pertama. Meskipun kebijakan ini tidak umum diterapkan, kebijakan ini dapat membantu mempertahankan motivasi peneliti dan mengurangi dampak negatif dari duplikasi.
---

## BAB XI

## PENUTUP

### 11.1 Kesimpulan

Berdasarkan pembahasan yang telah diuraikan dalam laporan ini, dapat ditarik beberapa kesimpulan sebagai berikut:

Pertama, perancangan lingkungan Bug Bounty untuk aplikasi SHYNESv2 Fashion E-Commerce memerlukan pemisahan yang jelas antara lingkungan produksi dan lingkungan staging. Lingkungan staging harus menjadi salinan fungsional dari lingkungan produksi namun menggunakan data dummy yang telah dianonimkan. Pemisahan ini penting untuk memastikan bahwa pengujian keamanan tidak mengganggu operasional bisnis dan tidak membahayakan data pengguna yang sebenarnya. Topologi lingkungan yang diusulkan mencakup domain staging.shynesv2.up.railway.app sebagai target in-scope dan domain produksi sebagai out-of-scope.

Kedua, aturan main atau Rules of Engagement yang komprehensif sangat penting untuk mengatur perilaku peneliti dan melindungi kepentingan organisasi. Aturan ini mencakup scope pengujian yang jelas, metode yang diizinkan, batasan operasional seperti maksimal 10 record data yang boleh diekstrak, prosedur pelaporan yang terstandarisasi, dan sanksi yang tegas bagi pelanggar. Aturan yang jelas dan transparan membantu menciptakan lingkungan pengujian yang profesional dan etis.

Ketiga, alur program Bug Bounty yang terstruktur terdiri dari sembilan tahap utama: registrasi peneliti, pembacaan aturan, pemilihan target dan pengujian, pelaporan temuan, triase dan verifikasi, penentuan severity dan prioritas, remediasi, pemberian reward, dan disclosure. Setiap tahap memiliki prosedur yang jelas dan melibatkan peran-peran tertentu dalam tim program. Alur ini dirancang untuk memastikan efisiensi dan efektivitas program.

Keempat, skema reward yang ditetapkan didasarkan pada tingkat severity kerentanan yang diukur menggunakan standar CVSS v3.1. Skema ini mencakup reward untuk kerentanan Critical sebesar .500, High sebesar , Medium sebesar , Low sebesar , dan Hall of Fame untuk temuan Informational. Anggaran tahunan program ditetapkan sebesar .000 dengan mekanisme monthly budget cap untuk mengontrol pengeluaran.

Kelima, kebijakan coordinated disclosure diterapkan untuk menyeimbangkan kepentingan organisasi dan hak peneliti. Periode embargo ditetapkan selama 90 hari untuk kerentanan Critical dan High, dan 60 hari untuk kerentanan Medium dan Low. Hall of Fame dan CVE assignment memberikan pengakuan tambahan kepada peneliti atas kontribusi mereka.

Keenam, berdasarkan hasil pengujian keamanan yang dilakukan oleh peneliti sebelumnya, terdapat beberapa kerentanan prioritas tinggi yang wajib diperbaiki sebelum program Bug Bounty dimulai, termasuk race condition pada checkout, brute force login, weak password policy, dan webhook signature verification. Perbaikan kerentanan-kerentanan ini penting untuk memastikan bahwa aplikasi dalam kondisi yang cukup aman sebelum diuji oleh komunitas peneliti yang lebih luas.

### 11.2 Saran

Berdasarkan kesimpulan di atas, berikut adalah beberapa saran yang dapat diberikan:

Pertama, disarankan agar SHYNESv2 Fashion E-Commerce memulai program Bug Bounty dengan model private program terlebih dahulu. Model ini memungkinkan organisasi untuk mengontrol jumlah peneliti, memastikan kualitas laporan, dan memperkirakan biaya program dengan lebih akurat. Setelah program privat berjalan selama 6 hingga 12 bulan dan infrastruktur triase sudah matang, program dapat ditingkatkan menjadi public program.

Kedua, organisasi harus berinvestasi dalam pembentukan tim keamanan yang kompeten untuk menjalankan program Bug Bounty. Tim ini harus terdiri dari Program Manager, Triage Lead, Remediation Lead, dan Legal serta Compliance. Setiap anggota tim harus memiliki keahlian yang sesuai dengan peran mereka dan mendapatkan pelatihan yang memadai.

Ketiga, perbaikan kerentanan prioritas tinggi yang telah diidentifikasi harus segera dilakukan sebelum program Bug Bounty dimulai. Perbaikan ini mencakup implementasi pessimistic locking untuk mencegah race condition, implementasi rate limiting dan captcha untuk mencegah brute force, implementasi password policy yang ketat, dan implementasi verifikasi HMAC untuk webhook. Perbaikan kerentanan prioritas sedang dan rendah dapat dilakukan secara bertahap setelah program berjalan.

Keempat, organisasi harus melakukan evaluasi berkala terhadap program Bug Bounty, minimal setiap tiga bulan. Evaluasi mencakup analisis efektivitas program, analisis biaya-manfaat, dan identifikasi area yang perlu ditingkatkan. Hasil evaluasi harus digunakan untuk menyempurnakan program di masa mendatang.

Kelima, disarankan untuk membangun hubungan yang baik dengan komunitas peneliti keamanan, baik di tingkat nasional maupun internasional. Partisipasi dalam acara-acara keamanan siber, dukungan terhadap penelitian keamanan, dan komunikasi yang transparan dengan peneliti dapat membantu membangun reputasi program yang positif.

Keenam, organisasi harus mempertimbangkan untuk mendapatkan CNA (CVE Numbering Authority) status untuk memudahkan proses CVE assignment. Status CNA memungkinkan organisasi untuk menerbitkan CVE secara mandiri tanpa harus melalui MITRE, yang dapat mempercepat proses publikasi CVE.

Ketujuh, dokumentasi program yang lengkap dan mudah diakses harus disediakan untuk peneliti. Dokumentasi mencakup arsitektur aplikasi, spesifikasi lingkungan staging, aturan main, format pelaporan, dan informasi kontak tim. Dokumentasi yang baik membantu peneliti dalam memahami aplikasi dan menyusun laporan yang berkualitas.
---

## DAFTAR PUSTAKA

Alrawi, O., Cha, S., Davidson, D., & Shastry, B. (2024). Bug Bounty Programs: A Systematic Literature Review. ACM Computing Surveys, 56(4), 1-35.

Assal, H., & Chiasson, S. (2019). An Exploratory Study of Bug Bounty Programs. Proceedings of the 2019 CHI Conference on Human Factors in Computing Systems, 1-12.

Bugcrowd. (2024). Bugcrowd Annual State of Bug Bounty Report 2024. Diakses dari https://www.bugcrowd.com/resources/reports/state-of-bug-bounty/

FIRST. (2019). Common Vulnerability Scoring System v3.1: Specification Document. Forum of Incident Response and Security Teams. Diakses dari https://www.first.org/cvss/v3-1/

Finifter, M., Akhawe, D., & Wagner, D. (2013). An Empirical Study of Vulnerability Rewards Programs. Proceedings of the 22nd USENIX Security Symposium, 273-288.

Google. (2024). Google Vulnerability Reward Program Rules and Rewards. Diakses dari https://bughunters.google.com/

HackerOne. (2024). HackerOne 2024 Hacker-Powered Security Report. HackerOne Inc. Diakses dari https://www.hackerone.com/resources/report/2024-hacker-powered-security-report

Kotler, Y., & Karp, R. (2022). Web Application Security: Exploitation and Countermeasures for Modern Web Applications. O'Reilly Media.

Maillart, T., Zhao, M., Grossklags, J., & Chuang, J. (2017). Given Enough Eyeballs, All Bugs Are Shallow? Revisiting Eric Raymond's Dictum in the Context of Bug Bounty Programs. Proceedings of the Workshop on the Economics of Information Security (WEIS), 1-15.

Munaiah, N., & Meneely, A. (2024). Understanding the Vulnerability Discovery Process in Bug Bounty Programs. IEEE Transactions on Software Engineering, 50(2), 215-232.

OWASP Foundation. (2020). OWASP Testing Guide v4.2. Open Web Application Security Project. Diakses dari https://owasp.org/www-project-web-security-testing-guide/

OWASP Foundation. (2021). OWASP Top Ten 2021: The Ten Most Critical Web Application Security Risks. Open Web Application Security Project. Diakses dari https://owasp.org/www-project-top-ten/

Penny, C., & Schumacher, R. (2023). Bug Bounty Bootcamp: The Guide to Finding and Reporting Web Vulnerabilities. No Starch Press.

Railway. (2024). Railway Documentation: Deploy and Scale Your Applications. Diakses dari https://docs.railway.app/

Ransbotham, S., Mitra, S., & Ramsey, J. (2022). Are Markets for Vulnerabilities Effective? An Empirical Study of Bug Bounty Programs. MIS Quarterly, 46(1), 205-236.

Stuttard, D., & Pinto, M. (2023). The Web Application Hacker's Handbook: Finding and Exploiting Security Flaws. 3rd Edition. John Wiley & Sons.

Zetter, K. (2020). The Rise of Bug Bounty Programs and the Hunt for Software Vulnerabilities. Wired Magazine. Diakses dari https://www.wired.com/story/bug-bounty-programs-rise/

---

*Laporan ini disusun sebagai bagian dari Ujian Akhir Semester mata kuliah Keamanan Sistem Informasi.*

*[Nama Mahasiswa 4] - [NIM.4]*
### 1.6 Sistematika Penulisan

Sistematika penulisan laporan ini disusun untuk memberikan gambaran yang jelas dan terstruktur tentang perancangan program Bug Bounty pada aplikasi SHYNESv2 Fashion E-Commerce. Laporan ini terdiri dari sebelas bab yang saling terkait dan membentuk satu kesatuan yang utuh.

Bab I merupakan pendahuluan yang berisi latar belakang, rumusan masalah, batasan masalah, tujuan penelitian, manfaat penelitian, dan sistematika penulisan. Bab ini memberikan gambaran umum tentang konteks dan ruang lingkup penelitian.

Bab II merupakan landasan teori yang membahas konsep-konsep dasar yang relevan dengan program Bug Bounty, termasuk definisi Bug Bounty, sejarah dan perkembangannya, platform-platform Bug Bounty yang ada, jenis-jenis program, metodologi yang digunakan, OWASP Testing Guide v4.2, dan CVSS v3.1 Scoring System.

Bab III memberikan gambaran umum tentang aplikasi SHYNESv2 Fashion E-Commerce, mencakup arsitektur aplikasi, fitur-fitur utama, stack teknologi yang digunakan, serta alur data dan interaksi antar komponen.

Bab IV membahas perancangan lingkungan Bug Bounty, termasuk topologi lingkungan, spesifikasi lingkungan staging, tools yang disediakan untuk peneliti, dan aturan main yang mengatur pelaksanaan program.

Bab V menjelaskan secara rinci proses Bug Bounty dari awal hingga akhir, mencakup alur program, registrasi peneliti, tahap reconnaissance, tahap testing, tahap pelaporan, tahap triase dan verifikasi, tahap remediasi, serta tahap reward dan disclosure.

Bab VI membahas struktur tim dan peran masing-masing anggota dalam program Bug Bounty, termasuk Program Manager, Triage Lead, Remediation Lead, dan Legal serta Compliance.

Bab VII menjelaskan skema reward dan incentive structure, mencakup besaran reward berdasarkan severity, mekanisme pembayaran, dan anggaran tahunan program.

Bab VIII membahas kebijakan disclosure yang diterapkan, termasuk coordinated disclosure, Hall of Fame, dan CVE assignment.

Bab IX berisi usulan perbaikan berdasarkan hasil pentest yang telah dilakukan, dikelompokkan menjadi prioritas tinggi, sedang, dan rendah.

Bab X berisi analisis risiko program, mencakup risiko researcher nakal, false positive berlebihan, budget tidak mencukupi, dan duplicate reports beserta mitigasinya.

Bab XI merupakan penutup yang berisi kesimpulan dari seluruh pembahasan dan saran untuk pengembangan program ke depannya.
**Additional detail on Industry Standards and Best Practices for Bug Bounty Programs**

Program Bug Bounty yang efektif harus mengacu pada berbagai standar industri dan praktik terbaik yang telah terbukti berhasil di berbagai organisasi global. Standar-standar ini mencakup aspek teknis, operasional, dan legal dari program Bug Bounty. Pemahaman dan implementasi standar-standar ini sangat penting untuk memastikan bahwa program berjalan dengan profesional, kredibel, dan sesuai dengan harapan komunitas peneliti keamanan.

ISO/IEC 27001 merupakan standar internasional untuk sistem manajemen keamanan informasi (ISMS) yang menyediakan kerangka kerja untuk mengelola keamanan informasi secara sistematis. Meskipun ISO/IEC 27001 tidak secara khusus membahas Bug Bounty, program Bug Bounty yang terintegrasi dengan ISMS yang baik akan lebih efektif karena didukung oleh kebijakan keamanan yang matang, proses manajemen risiko yang terstruktur, dan budaya keamanan yang kuat. Organisasi yang telah bersertifikasi ISO/IEC 27001 memiliki keunggulan dalam menjalankan program Bug Bounty karena infrastruktur keamanan yang sudah mapan.

NIST Cybersecurity Framework merupakan kerangka kerja yang dikembangkan oleh National Institute of Standards and Technology (NIST) Amerika Serikat yang mencakup lima fungsi utama: Identify, Protect, Detect, Respond, dan Recover. Program Bug Bounty SHYNESv2 dapat diintegrasikan ke dalam kerangka kerja NIST pada fungsi Identify (identifikasi aset dan risiko), Protect (implementasi kontrol keamanan), Detect (deteksi kerentanan melalui pengujian), dan Respond (respons terhadap temuan kerentanan). Integrasi ini memastikan bahwa program Bug Bounty menjadi bagian dari strategi keamanan yang komprehensif, bukan sekadar program yang berdiri sendiri.

PCI DSS (Payment Card Industry Data Security Standard) merupakan standar keamanan yang berlaku untuk organisasi yang memproses, menyimpan, atau mentransmisikan data kartu kredit. SHYNESv2 sebagai platform e-commerce yang mengintegrasikan payment gateway perlu mempertimbangkan kepatuhan terhadap PCI DSS dalam perancangan program Bug Bounty. Lingkungan staging harus dikonfigurasi sedemikian rupa sehingga tidak menyimpan atau memproses data kartu kredit yang sebenarnya. Penggunaan data dummy untuk pengujian payment gateway memastikan bahwa peneliti tidak memiliki akses ke data kartu kredit yang sensitif.

OWASP ASVS (Application Security Verification Standard) merupakan standar yang menyediakan kerangka kerja untuk memverifikasi keamanan aplikasi web. ASVS mendefinisikan tiga tingkat verifikasi keamanan: Level 1 (opportunistic), Level 2 (standard), dan Level 3 (advanced). Program Bug Bounty SHYNESv2 dapat menggunakan ASVS sebagai acuan untuk menentukan target keamanan yang ingin dicapai dan sebagai alat untuk memprioritaskan perbaikan kerentanan berdasarkan tingkat keparahannya.

SOC 2 (System and Organization Controls 2) merupakan standar pelaporan keamanan yang dikembangkan oleh American Institute of CPAs (AICPA). SOC 2 mencakup lima prinsip layanan kepercayaan: Security, Availability, Processing Integrity, Confidentiality, dan Privacy. Program Bug Bounty dapat membantu organisasi dalam memenuhi persyaratan SOC 2, terutama dalam prinsip Security yang mensyaratkan perlindungan terhadap akses tidak sah. Laporan dari program Bug Bounty dapat digunakan sebagai bukti kepatuhan terhadap prinsip-prinsip SOC 2.
**Additional detail on Legal Framework for Bug Bounty Programs in Indonesia**

Pelaksanaan program Bug Bounty di Indonesia harus mempertimbangkan kerangka hukum yang berlaku untuk memastikan kepatuhan dan melindungi kepentingan semua pihak yang terlibat. Pemahaman tentang aspek hukum ini sangat penting untuk merancang program yang sah secara hukum dan menghindari potensi masalah hukum di kemudian hari.

Undang-Undang Informasi dan Transaksi Elektronik (UU ITE) Nomor 11 Tahun 2008 beserta perubahannya dalam UU Nomor 19 Tahun 2016 merupakan landasan hukum utama yang mengatur kegiatan elektronik di Indonesia, termasuk aktivitas pengujian keamanan siber. Beberapa pasal dalam UU ITE relevan dengan program Bug Bounty, terutama Pasal 30 yang melarang akses ilegal ke sistem elektronik, Pasal 31 yang melarang intersepsi ilegal, dan Pasal 32 yang melarang perubahan data ilegal. Penting untuk dicatat bahwa program Bug Bounty yang dijalankan dengan izin tertulis dari pemilik sistem dan sesuai dengan aturan yang telah ditetapkan berada dalam koridor hukum yang dilindungi. Dokumen izin tertulis dalam bentuk Rules of Engagement dan NDA menjadi bukti bahwa peneliti memiliki otorisasi untuk melakukan pengujian.

Undang-Undang Perlindungan Data Pribadi (UU PDP) Nomor 27 Tahun 2022 merupakan regulasi terbaru yang mengatur perlindungan data pribadi di Indonesia. UU PDP memiliki implikasi signifikan terhadap program Bug Bounty, terutama yang berkaitan dengan akses dan pemrosesan data pribadi selama pengujian. Program Bug Bounty SHYNESv2 harus memastikan bahwa lingkungan staging tidak menggunakan data pribadi yang sebenarnya. Penggunaan data dummy dan data anonim merupakan praktik yang sesuai dengan prinsip data minimization yang diatur dalam UU PDP. Jika peneliti secara tidak sengaja menemukan data pribadi selama pengujian, mereka wajib melaporkan temuan tersebut tanpa menyebarluaskan data tersebut.

Peraturan Pemerintah tentang Penyelenggaraan Sistem dan Transaksi Elektronik (PP PSTE) Nomor 71 Tahun 2019 mengatur lebih lanjut tentang penyelenggaraan sistem elektronik yang aman dan andal. PP PSTE mewajibkan penyelenggara sistem elektronik untuk melakukan pengamanan sistem secara berkelanjutan, termasuk melalui pengujian keamanan secara berkala. Program Bug Bounty dapat menjadi salah satu mekanisme yang digunakan untuk memenuhi kewajiban ini. PP PSTE juga mengatur tentang kewajiban pelaporan insiden keamanan siber yang dapat diintegrasikan dengan prosedur disclosure dalam program Bug Bounty.

Peraturan Badan Siber dan Sandi Negara (BSSN) tentang Tata Kelola Keamanan Siber memberikan panduan teknis tentang pelaksanaan pengujian keamanan siber di Indonesia. BSSN mendorong penerapan program Bug Bounty sebagai salah satu metode pengujian keamanan yang efektif. Organisasi yang menjalankan program Bug Bounty disarankan untuk berkoordinasi dengan BSSN dan CERT nasional untuk memastikan kepatuhan terhadap regulasi yang berlaku.

Aspek hukum kontrak juga penting dalam program Bug Bounty. NDA dan perjanjian partisipasi program merupakan dokumen hukum yang mengikat antara organisasi dan peneliti. Dokumen ini harus disusun dengan bahasa hukum yang tepat dan mencakup ketentuan tentang ruang lingkup pengujian, kerahasiaan, kepemilikan temuan, pembayaran reward, penyelesaian sengketa, dan hukum yang berlaku. Disarankan untuk melibatkan ahli hukum yang berpengalaman dalam hukum siber untuk menyusun dokumen-dokumen ini.

Perlindungan hukum bagi peneliti Bug Bounty juga perlu dipertimbangkan. Peneliti yang berpartisipasi dalam program Bug Bounty dengan itikad baik dan sesuai dengan aturan yang telah ditetapkan harus mendapatkan perlindungan hukum dari tuntutan pidana atau perdata. Safe harbor provision dalam Rules of Engagement menyatakan bahwa organisasi tidak akan menuntut peneliti secara hukum selama mereka mematuhi aturan program. Ketentuan ini memberikan rasa aman kepada peneliti dan mendorong partisipasi yang lebih luas dalam program.
**Additional detail on Communication and Community Management**

Komunikasi dan manajemen komunitas merupakan aspek penting yang sering kali terabaikan dalam perancangan program Bug Bounty. Program yang sukses tidak hanya bergantung pada infrastruktur teknis yang baik, tetapi juga pada kemampuan organisasi untuk membangun dan memelihara hubungan yang positif dengan komunitas peneliti keamanan. Komunikasi yang transparan, responsif, dan profesional sangat penting untuk membangun reputasi program yang baik.

Saluran komunikasi yang efektif harus disediakan untuk memfasilitasi interaksi antara tim program dan peneliti. Saluran komunikasi ini mencakup email khusus untuk program Bug Bounty, forum diskusi atau group chat platform seperti Discord atau Slack, sistem ticketing pada platform Bug Bounty, dan saluran pelaporan darurat untuk kerentanan kritis. Setiap saluran komunikasi harus dimonitor secara aktif oleh tim program selama jam kerja yang telah ditentukan. Respons terhadap pertanyaan atau laporan harus diberikan dalam waktu maksimal 24 jam untuk memastikan peneliti tidak merasa diabaikan.

Transparansi dalam komunikasi sangat penting untuk membangun kepercayaan dengan komunitas peneliti. Organisasi harus secara terbuka mengomunikasikan status program, termasuk perubahan aturan, pembaruan sistem, jadwal maintenance, dan pencapaian program. Laporan triwulanan yang merangkum aktivitas program, termasuk jumlah laporan yang diterima, jumlah kerentanan yang diverifikasi, reward yang dibayarkan, dan perbaikan yang telah dilakukan, dapat dipublikasikan untuk meningkatkan transparansi.

Penghargaan dan pengakuan terhadap peneliti tidak hanya terbatas pada reward finansial. Pengakuan non-finansial seperti pengakuan publik melalui Hall of Fame, sertifikat penghargaan, undangan ke acara eksklusif, dan kesempatan untuk menjadi beta tester untuk program baru juga sangat dihargai oleh komunitas peneliti. Program Bug Bounty SHYNESv2 harus memiliki strategi yang komprehensif untuk memberikan penghargaan kepada peneliti yang berkontribusi.

Edukasi dan pemberdayaan komunitas juga merupakan investasi jangka panjang yang bermanfaat bagi program Bug Bounty. Organisasi dapat menyelenggarakan workshop, webinar, atau pelatihan tentang keamanan aplikasi web yang terbuka untuk komunitas peneliti. Konten edukasi seperti write-up teknis, studi kasus, dan panduan pengujian dapat dipublikasikan untuk membantu peneliti pemula mengembangkan keterampilan mereka. Investasi dalam edukasi komunitas akan mendorong pertumbuhan ekosistem Bug Bounty secara keseluruhan dan pada akhirnya akan menguntungkan program itu sendiri.

Penanganan konflik dan sengketa harus dilakukan secara profesional dan adil. Jika terjadi perselisihan antara peneliti dan tim program, mekanisme penyelesaian sengketa yang jelas harus tersedia. Mekanisme ini dapat mencakup eskalasi ke manajemen yang lebih tinggi, mediasi oleh pihak ketiga yang netral, atau arbitrase sesuai dengan hukum yang berlaku. Semua keputusan terkait sengketa harus didokumentasikan dengan baik dan dikomunikasikan secara transparan kepada pihak-pihak yang terlibat.

Feedback dari peneliti merupakan sumber informasi yang berharga untuk perbaikan program. Survei kepuasan peneliti dapat dilakukan secara berkala untuk mengumpulkan masukan tentang berbagai aspek program, termasuk kejelasan aturan, responsivitas tim, keadilan penentuan reward, dan kemudahan pelaporan. Feedback yang diterima harus dianalisis dan digunakan untuk menyempurnakan program secara berkelanjutan.
**Additional detail on Integration with CI/CD Pipeline and DevSecOps**

Program Bug Bounty tidak dapat berdiri sendiri sebagai satu-satunya metode pengujian keamanan. Untuk mencapai efektivitas maksimal, program Bug Bounty harus diintegrasikan dengan pipeline CI/CD (Continuous Integration/Continuous Deployment) dan praktik DevSecOps yang lebih luas. Integrasi ini memastikan bahwa temuan dari program Bug Bounty dapat ditindaklanjuti dengan cepat dan bahwa kerentanan serupa dapat dicegah di masa mendatang melalui automated security testing.

Integrasi dengan pipeline CI/CD memungkinkan temuan dari program Bug Bounty untuk secara otomatis diterjemahkan menjadi test case keamanan yang dijalankan secara otomatis pada setiap deployment. Misalnya, jika seorang peneliti menemukan kerentanan SQL injection pada endpoint tertentu, tim pengembangan dapat menulis automated test yang memeriksa endpoint tersebut untuk kerentanan SQL injection. Test case ini kemudian diintegrasikan ke dalam pipeline CI/CD sehingga setiap perubahan kode akan diuji terhadap kerentanan yang sama. Pendekatan ini mencegah regresi keamanan di mana kerentanan yang telah diperbaiki muncul kembali karena perubahan kode di masa mendatang.

Automated security scanning menggunakan alat seperti OWASP ZAP atau SonarQube harus diintegrasikan ke dalam pipeline CI/CD sebagai quality gate. Sebelum kode dideploy ke lingkungan produksi, automated scanner akan memeriksa kerentanan keamanan umum. Jika scanner mendeteksi kerentanan dengan severity di atas ambang batas yang telah ditentukan, deployment akan ditolak dan tim development harus memperbaiki kerentanan terlebih dahulu. Langkah ini memastikan bahwa kerentanan dasar dapat dideteksi dan diperbaiki sebelum kode mencapai lingkungan staging atau produksi.

Integrasi dengan sistem manajemen proyek dan issue tracker seperti Jira atau GitHub Issues memungkinkan temuan dari program Bug Bounty untuk secara otomatis dibuatkan tiket perbaikan. Setiap laporan yang diverifikasi akan menghasilkan tiket di sistem manajemen proyek dengan informasi yang relevan, termasuk deskripsi kerentanan, langkah reproduksi, severity, tenggat waktu perbaikan, dan pengembang yang ditugaskan. Integrasi ini memastikan bahwa temuan tidak terlewatkan dan bahwa proses remediasi dapat dilacak dengan baik.

Penerapan konsep \"security as code\" memungkinkan kebijakan keamanan, aturan deteksi, dan konfigurasi alat keamanan untuk dikelola sebagai kode dalam repository version control. Pendekatan ini memastikan bahwa konfigurasi keamanan konsisten di seluruh lingkungan dan dapat di-audit dengan mudah. Ketika seorang peneliti menemukan kerentanan yang melibatkan konfigurasi keamanan yang salah, perbaikan dapat dilakukan dengan mengubah konfigurasi dalam repository dan mendeploynya melalui pipeline CI/CD.

Monitoring keamanan yang berkelanjutan juga penting untuk melengkapi program Bug Bounty. Sistem deteksi intrusi, log monitoring, dan security information and event management (SIEM) harus diimplementasikan untuk mendeteksi aktivitas mencurigakan secara realtime. Program Bug Bounty dapat menghasilkan informasi berhajar tentang vektor serangan yang dapat digunakan untuk meningkatkan aturan deteksi dalam sistem monitoring. Kolaborasi antara tim Bug Bounty dan tim Security Operations Center (SOC) memastikan bahwa pembelajaran dari program Bug Bounty dapat diterapkan untuk meningkatkan deteksi dan respons insiden.

Bug bounty program juga harus diintegrasikan dengan proses manajemen kerentanan yang lebih luas. Setiap temuan dari Bug Bounty harus dicatat dalam database kerentanan organisasi, dilacak melalui siklus hidupnya, dan dilaporkan dalam metrik keamanan organisasi. Integrasi ini memungkinkan manajemen untuk melihat gambaran lengkap tentang postur keamanan organisasi, termasuk kerentanan yang ditemukan melalui Bug Bounty, pengujian penetrasi tradisional, automated scanning, dan audit keamanan.
**Additional detail on Metrics and Key Performance Indicators (KPIs)**

Pengukuran kinerja program Bug Bounty sangat penting untuk mengevaluasi efektivitas program, mengidentifikasi area yang perlu ditingkatkan, dan memberikan justifikasi untuk alokasi sumber daya. Metrik dan KPI yang tepat harus ditetapkan sejak awal program dan dimonitor secara berkala untuk memastikan program berjalan sesuai dengan tujuan yang telah ditetapkan.

Metrik volume dan aktivitas program mencakup jumlah peneliti yang terdaftar dan aktif dalam program, jumlah laporan yang diterima per bulan, jumlah laporan yang diverifikasi sebagai valid versus yang ditolak, persentase laporan yang merupakan false positive, jumlah kerentanan yang ditemukan per severity (Critical, High, Medium, Low, Informational), dan jumlah laporan duplikat. Metrik-metrik ini memberikan gambaran tentang seberapa aktif program Bug Bounty dan seberapa banyak temuan yang dihasilkan. Peningkatan jumlah peneliti aktif menunjukkan bahwa program menarik minat komunitas, sementara peningkatan jumlah laporan valid menunjukkan bahwa program efektif dalam mengidentifikasi kerentanan.

Metrik waktu pemrosesan mencakup waktu rata-rata untuk first response setelah laporan diterima, waktu rata-rata untuk verifikasi dan triase, waktu rata-rata untuk perbaikan berdasarkan SLA severity, dan waktu rata-rata untuk pembayaran reward. Metrik waktu pemrosesan sangat penting karena menunjukkan responsivitas tim program terhadap peneliti. Waktu respons yang cepat menunjukkan bahwa organisasi serius dalam menangani temuan dan menghargai kontribusi peneliti. Waktu perbaikan yang sesuai dengan SLA menunjukkan bahwa organisasi memiliki proses remediasi yang efisien.

Metrik finansial mencakup total reward yang dibayarkan per periode, rata-rata reward per laporan, biaya per kerentanan yang ditemukan, Return on Investment (ROI) program, dan perbandingan biaya dengan pengujian penetrasi tradisional. Metrik finansial digunakan untuk mengevaluasi efisiensi biaya program Bug Bounty dibandingkan dengan metode pengujian keamanan lainnya. Program Bug Bounty yang efektif harus memiliki biaya per kerentanan yang lebih rendah dibandingkan dengan pengujian penetrasi tradisional, terutama untuk kerentanan dengan severity rendah dan medium yang dapat diidentifikasi secara efisien oleh peneliti crowdsourced.

Metrik kualitas mencakup tingkat kepuasan peneliti yang diukur melalui survei, tingkat kepuasan tim internal terhadap proses program, akurasi severity scoring dengan membandingkan skor peneliti dan tim triase, dan jumlah sengketa yang timbul. Metrik kualitas memberikan gambaran tentang bagaimana program diterima oleh peneliti dan tim internal. Tingkat kepuasan yang tinggi menunjukkan bahwa program berjalan dengan baik dan hubungan antara organisasi dan peneliti terjaga dengan baik.

Metrik dampak keamanan mencakup jumlah kerentanan kritis dan high yang ditemukan per periode, jumlah CVE yang diterbitkan dari temuan program, jumlah kerentanan yang berhasil diperbaiki sebelum dieksploitasi, dan pengurangan permukaan serangan aplikasi. Metrik dampak keamanan digunakan untuk mengevaluasi kontribusi program Bug Bounty terhadap peningkatan postur keamanan organisasi secara keseluruhan. Program yang efektif harus mampu mengidentifikasi dan memperbaiki kerentanan kritis sebelum kerentanan tersebut ditemukan dan dieksploitasi oleh pihak jahat.

Laporan metrik harus disusun secara berkala, misalnya bulanan untuk metrik operasional dan triwulanan untuk metrik strategis. Laporan ini harus didistribusikan kepada pemangku kepentingan yang relevan, termasuk manajemen, tim keamanan, tim pengembangan, dan tim program. Visualisasi data dalam bentuk dashboard juga sangat membantu untuk memonitor metrik secara realtime dan mengidentifikasi tren yang perlu mendapatkan perhatian.

Evaluasi program secara menyeluruh harus dilakukan setiap tahun sebagai bagian dari siklus perencanaan program. Evaluasi mencakup analisis semua metrik yang telah ditetapkan, identifikasi keberhasilan dan kegagalan, pelajaran yang dipetik, dan rekomendasi untuk perbaikan di tahun mendatang. Hasil evaluasi digunakan untuk menyempurnakan aturan program, menyesuaikan skema reward, mengoptimalkan alur proses, dan mengalokasikan sumber daya dengan lebih efektif.
**Additional detail on Incident Response Integration**

Program Bug Bounty tidak hanya berfungsi sebagai alat untuk menemukan kerentanan, tetapi juga dapat diintegrasikan dengan proses incident response organisasi. Ketika seorang peneliti melaporkan kerentanan yang kritis atau sedang dieksploitasi secara aktif, respons yang cepat dan terkoordinasi sangat diperlukan untuk mencegah kerusakan yang lebih luas. Integrasi antara program Bug Bounty dan incident response memastikan bahwa temuan kritis ditangani dengan prioritas tertinggi.

Prosedur eskalasi darurat harus ditetapkan untuk menangani kerentanan yang memerlukan respons segera. Kerentanan yang masuk dalam kategori eskalasi darurat mencakup remote code execution yang sedang dieksploitasi secara aktif, kebocoran data pengguna yang sedang berlangsung, kerentanan yang memungkinkan akses administratif tanpa otorisasi, dan kerentanan yang melibatkan payment gateway yang dapat menyebabkan kerugian finansial langsung. Peneliti yang menemukan kerentanan dalam kategori ini harus memiliki saluran komunikasi darurat yang terpisah dari saluran pelaporan reguler.

Tim incident response harus dilibatkan sejak awal ketika kerentanan darurat dilaporkan. Tim incident response akan melakukan analisis dampak untuk menentukan sejauh mana kerentanan telah dieksploitasi, mengidentifikasi data atau sistem yang terdampak, dan merekomendasikan tindakan mitigasi jangka pendek. Mitigasi jangka pendek dapat mencakup penonaktifan sementara fitur yang terdampak, pemblokiran alamat IP yang mencurigakan, atau implementasi rule WAF sementara untuk memblokir eksploitasi.

Setelah situasi darurat dapat dikendalikan, proses remediasi reguler dapat dilakukan sesuai dengan SLA yang telah ditetapkan. Investigasi forensik mungkin diperlukan untuk kerentanan yang telah dieksploitasi secara aktif untuk memahami bagaimana eksploitasi terjadi, data apa yang diakses, dan tindakan apa yang perlu diambil untuk memulihkan keamanan sistem.

Komunikasi dengan peneliti selama proses incident response harus tetap dijaga. Peneliti harus diberi update secara berkala tentang status penanganan kerentanan, baik yang dilaporkan melalui saluran darurat maupun saluran reguler. Penghargaan khusus dapat diberikan kepada peneliti yang melaporkan kerentanan kritis yang sedang dieksploitasi secara aktif, mengingat kontribusi mereka dalam mencegah kerusakan yang lebih besar.

Pelajaran yang dipetik dari setiap insiden harus didokumentasikan dan digunakan untuk meningkatkan proses keamanan secara keseluruhan. Post-mortem analysis harus dilakukan setelah setiap insiden untuk mengidentifikasi akar penyebab, kelemahan dalam proses deteksi atau respons, dan rekomendasi untuk perbaikan. Temuan dari post-mortem analysis harus diintegrasikan ke dalam program Bug Bounty dan proses keamanan lainnya untuk mencegah insiden serupa di masa mendatang.
**Additional detail on Ethical Considerations and Responsible Disclosure Ethics**

Etika dalam program Bug Bounty merupakan aspek fundamental yang mendasari seluruh operasional program. Peneliti keamanan yang berpartisipasi dalam program Bug Bounty memiliki tanggung jawab etis untuk melakukan pengujian secara bertanggung jawab, menghormati batasan yang telah ditetapkan, dan memprioritaskan keamanan pengguna di atas kepentingan pribadi. Pemahaman dan penerapan prinsip-prinsip etika ini penting untuk menjaga integritas program dan melindungi kepentingan semua pihak.

Prinsip dasar etika dalam Bug Bounty mencakup beberapa hal. Pertama, peneliti harus mendapatkan izin eksplisit dari pemilik sistem sebelum melakukan pengujian keamanan. Izin ini diberikan melalui partisipasi dalam program Bug Bounty dan persetujuan terhadap Rules of Engagement. Kedua, peneliti harus membatasi pengujian sesuai dengan scope yang telah ditetapkan. Pengujian di luar scope tanpa izin merupakan pelanggaran etika yang serius. Ketiga, peneliti harus meminimalkan dampak pengujian terhadap sistem dan data. Eksploitasi yang berlebihan atau merusak tidak dapat diterima secara etis. Keempat, peneliti harus melaporkan kerentanan yang ditemukan secara bertanggung jawab dan memberikan waktu yang cukup kepada organisasi untuk memperbaiki kerentanan sebelum mempublikasikannya.

Responsible disclosure merupakan praktik etis yang mengharuskan peneliti untuk melaporkan kerentanan kepada organisasi terlebih dahulu sebelum mempublikasikannya ke publik. Praktik ini memberikan kesempatan kepada organisasi untuk memperbaiki kerentanan dan melindungi pengguna mereka sebelum informasi kerentanan tersebar luas. Responsible disclosure berbeda dengan full disclosure, di mana peneliti langsung mempublikasikan kerentanan tanpa memberikan kesempatan kepada organisasi untuk memperbaikinya terlebih dahulu. Sebagian besar program Bug Bounty menerapkan prinsip responsible disclosure.

Peneliti juga harus mematuhi kode etik profesional yang ditetapkan oleh organisasi keamanan siber seperti EC-Council, (ISC), atau Offensive Security. Kode etik ini mencakup prinsip-prinsip seperti integritas, objektivitas, kompetensi, dan kerahasiaan. Peneliti yang melanggar kode etik dapat kehilangan sertifikasi profesional mereka dan merusak reputasi mereka di komunitas keamanan.

Organisasi juga memiliki tanggung jawab etis dalam menjalankan program Bug Bounty. Organisasi harus menyediakan lingkungan pengujian yang aman, menetapkan aturan yang jelas dan adil, merespons laporan peneliti secara tepat waktu, memberikan reward yang sesuai, dan menghargai kontribusi peneliti. Organisasi tidak boleh menggunakan program Bug Bounty sebagai dalih untuk menghindari tanggung jawab keamanan atau untuk mengeksploitasi peneliti secara tidak adil.

Perlindungan terhadap peneliti yang bertindak dengan itikad baik merupakan aspek etis yang penting. Organisasi harus menyediakan safe harbor provision yang melindungi peneliti dari tuntutan hukum selama mereka mematuhi aturan program. Safe harbor provision ini penting karena banyak yurisdiksi memiliki undang-undang yang melarang akses tidak sah ke sistem komputer, yang secara teknis dapat mencakup aktivitas pengujian keamanan. Dengan menyediakan safe harbor, organisasi menunjukkan komitmen mereka terhadap praktik etis dalam Bug Bounty.

Konflik kepentingan juga harus dikelola secara etis dalam program Bug Bounty. Peneliti yang memiliki hubungan dengan organisasi, seperti karyawan, kontraktor, atau anggota keluarga, harus mengungkapkan hubungan tersebut dan mungkin tidak diizinkan untuk berpartisipasi dalam program untuk menghindari konflik kepentingan. Kebijakan yang jelas tentang konflik kepentingan harus ditetapkan dan dikomunikasikan kepada semua peneliti.
**Additional detail on Specific Vulnerability Categories and Testing Approaches**

Dalam konteks program Bug Bounty untuk aplikasi SHYNESv2 Fashion E-Commerce, pemahaman mendalam tentang kategori kerentanan spesifik dan pendekatan pengujian yang sesuai sangat penting. Berikut adalah penjelasan rinci tentang kerentanan yang paling relevan untuk aplikasi e-commerce berbasis Laravel dan metodologi pengujian yang direkomendasikan.

SQL Injection (SQLi) merupakan salah satu kerentanan paling kritis yang dapat ditemukan dalam aplikasi web. SQLi terjadi ketika input pengguna tidak divalidasi atau dibersihkan dengan benar sebelum digunakan dalam query SQL. Dalam aplikasi Laravel, penggunaan Eloquent ORM dan Query Builder dengan parameter binding seharusnya mencegah SQLi. Namun, penggunaan raw queries, DB::statement, atau DB::select dengan concatenation string dapat menyebabkan kerentanan SQLi. Peneliti harus menguji setiap endpoint yang berinteraksi dengan database, terutama endpoint dengan parameter pencarian, filter, sorting, dan autocomplete. Teknik pengujian SQLi mencakup pengujian berbasis error, berbasis boolean, berbasis time, dan out-of-band SQLi. Payload pengujian harus mencakup berbagai variasi seperti tanda kutip, komentar SQL, UNION-based injection, dan stacked queries. Dampak SQLi sangat serius karena dapat memungkinkan penyerang untuk membaca, memodifikasi, atau menghapus data dalam database, termasuk data pengguna dan data transaksi.

Cross-Site Scripting (XSS) merupakan kerentanan yang memungkinkan penyerang untuk menyisipkan skrip jahat ke dalam halaman web yang dilihat oleh pengguna lain. Dalam konteks e-commerce, XSS dapat digunakan untuk mencuri cookie sesi pengguna, mengarahkan pengguna ke situs phishing, memodifikasi tampilan halaman, atau mengeksekusi tindakan atas nama pengguna yang sah. Laravel menyediakan perlindungan terhadap XSS melalui Blade templating yang secara otomatis melakukan escape output menggunakan sintaks {{ }}. Namun, penggunaan sintaks {!! !!} untuk output yang tidak di-escape dapat menyebabkan kerentanan XSS jika data yang ditampilkan mengandung skrip jahat. Pengujian XSS mencakup Reflected XSS (di mana skrip dimasukkan melalui parameter URL dan langsung ditampilkan), Stored XSS (di mana skrip disimpan dalam database dan ditampilkan kepada pengguna lain), dan DOM-based XSS (di mana skrip dieksekusi melalui manipulasi DOM di sisi klien).

Insecure Direct Object References (IDOR) merupakan kerentanan otorisasi yang terjadi ketika aplikasi menggunakan identifier langsung (seperti ID numerik atau UUID) untuk merujuk ke objek data tanpa memverifikasi bahwa pengguna yang mengakses memiliki otorisasi yang tepat. Dalam aplikasi e-commerce, IDOR dapat ditemukan pada endpoint yang mengakses data pesanan, data pengguna, data produk, atau data kontrak. Peneliti dapat menguji IDOR dengan memanipulasi parameter ID dalam URL atau request body dan memeriksa apakah mereka dapat mengakses data milik pengguna lain. Contoh pengujian IDOR termasuk mengubah parameter order_id pada endpoint /api/orders/123 menjadi /api/orders/456 untuk melihat pesanan pengguna lain, atau mengubah parameter user_id pada endpoint /api/profile untuk melihat profil pengguna lain.

Server-Side Request Forgery (SSRF) merupakan kerentanan yang memungkinkan penyerang untuk memaksa server melakukan permintaan HTTP ke target internal atau eksternal yang tidak seharusnya dapat diakses. Dalam aplikasi Laravel, SSRF dapat terjadi ketika aplikasi menggunakan fungsi seperti file_get_contents, curl_exec, atau GuzzleHttp untuk mengambil konten dari URL yang disediakan oleh pengguna. SSRF dapat digunakan untuk memindai jaringan internal, mengakses layanan internal seperti server metadata cloud, atau melakukan serangan terhadap sistem internal. Pengujian SSRF dilakukan dengan menyediakan URL internal (seperti http://localhost, http://127.0.0.1, http://169.254.169.254) sebagai parameter input dan mengamati respons server.

Cross-Site Request Forgery (CSRF) merupakan kerentanan yang memungkinkan penyerang untuk memaksa pengguna yang telah diautentikasi untuk mengeksekusi tindakan yang tidak diinginkan. Laravel menyediakan perlindungan CSRF melalui token CSRF yang secara otomatis disertakan dalam setiap form. Namun, endpoint API yang tidak menggunakan token CSRF atau aplikasi yang menonaktifkan CSRF protection secara selektif dapat rentan terhadap CSRF. Pengujian CSRF dilakukan dengan mencoba mengirimkan request POST tanpa token CSRF atau dengan token yang tidak valid.

Business Logic Errors merupakan kerentanan yang unik untuk setiap aplikasi karena terkait dengan logika bisnis spesifik aplikasi. Dalam konteks e-commerce, business logic errors dapat mencakup manipulasi harga dengan memodifikasi parameter harga yang dikirimkan dari klien, penggunaan kupon diskon secara berulang, melakukan pemesanan dengan stok negatif, melewati langkah pembayaran, memanipulasi jumlah ongkos kirim, dan mengeksploitasi race condition pada proses checkout. Pengujian business logic errors memerlukan pemahaman mendalam tentang alur bisnis aplikasi dan kreativitas dalam menemukan cara untuk mengeksploitasi kelemahan dalam logika tersebut.

File Upload Vulnerabilities merupakan kerentanan yang terjadi ketika aplikasi mengizinkan pengguna untuk mengunggah file tanpa validasi yang memadai. Kerentanan ini dapat menyebabkan penyerang mengunggah file berbahaya seperti web shell, skrip PHP, atau file executable lainnya. Dalam aplikasi e-commerce, fitur upload gambar produk, upload dokumen kontrak, upload avatar pengguna, dan upload file lampiran pada pesanan merupakan titik masuk potensial. Pengujian file upload mencakup pengujian tipe file yang diizinkan, pengujian konten file, pengujian ukuran file, pengujian path traversal pada nama file, dan pengujian eksekusi file yang diunggah.
**Additional detail on Laravel-Specific Security Considerations**

Framework Laravel memiliki fitur keamanan bawaan yang baik, namun implementasi yang tidak tepat masih dapat menyebabkan kerentanan. Pemahaman tentang aspek keamanan spesifik Laravel sangat penting bagi peneliti yang menguji aplikasi SHYNESv2 dan bagi tim pengembangan yang memperbaiki kerentanan.

Mass Assignment Vulnerability merupakan kerentanan yang terjadi ketika pengguna dapat memodifikasi kolom database yang seharusnya tidak dapat mereka akses. Laravel menyediakan perlindungan terhadap mass assignment melalui properti  dan  pada model Eloquent. Jika properti ini tidak dikonfigurasi dengan benar, penyerang dapat memodifikasi kolom sensitif seperti is_admin, role, atau balance dengan menambahkan parameter tersebut ke dalam request. Pengujian mass assignment dilakukan dengan menambahkan parameter yang tidak seharusnya dapat dimodifikasi ke dalam request POST atau PUT dan memeriksa apakah parameter tersebut diterima oleh model.

Debug Mode Information Disclosure merupakan kerentanan yang terjadi ketika mode debug Laravel (APP_DEBUG=true) diaktifkan di lingkungan produksi. Mode debug menampilkan informasi sensitif seperti stack trace, query SQL, variabel lingkungan, dan konfigurasi aplikasi ketika terjadi error. Informasi ini sangat berharga bagi penyerang karena dapat mengungkapkan detail tentang arsitektur aplikasi, kredensial, dan kerentanan potensial. Peneliti dapat memicu error dengan sengaja dengan mengirimkan input yang tidak valid atau mengakses endpoint yang tidak ada untuk melihat apakah mode debug aktif.

Encryption and Hashing merupakan aspek keamanan penting dalam aplikasi Laravel. Laravel menggunakan enkripsi AES-256-CBC untuk data yang perlu didekripsi kembali dan hashing bcrypt atau Argon2 untuk password. Peneliti harus memeriksa apakah data sensitif seperti payment information, personal data, dan session data dienkripsi dengan benar. Pengujian enkripsi dilakukan dengan memeriksa data dalam database untuk memastikan data sensitif tidak disimpan dalam bentuk plaintext. Pengujian hashing dilakukan dengan memeriksa apakah password disimpan dalam bentuk hash dan apakah hash yang digunakan cukup kuat.

Session Management dalam Laravel menggunakan driver session yang dapat dikonfigurasi, termasuk file, cookie, database, Redis, dan Memcached. Peneliti harus menguji session fixation dengan mencoba memanipulasi session ID sebelum login, session timeout dengan memeriksa apakah session tetap aktif setelah periode tidak aktif yang lama, dan cookie security dengan memeriksa atribut secure, httpOnly, dan SameSite pada cookie session. Pengujian session management juga mencakup pemeriksaan apakah regenerasi session ID dilakukan setelah login dan logout.

API Rate Limiting merupakan fitur yang disediakan oleh Laravel melalui middleware throttle. Peneliti harus menguji apakah rate limiting diimplementasikan pada endpoint API yang sensitif seperti login, registrasi, reset password, dan checkout. Pengujian dilakukan dengan mengirimkan sejumlah besar request dalam waktu singkat dan memeriksa apakah server merespons dengan status 429 Too Many Requests. Ketiadaan rate limiting pada endpoint sensitif dapat menyebabkan brute force attack dan denial of service.

Queue and Job Security merupakan aspek yang sering terabaikan dalam pengujian keamanan Laravel. Aplikasi SHYNESv2 menggunakan Laravel Queue untuk pemrosesan asynchronous. Peneliti harus memeriksa apakah job dan queue dikonfigurasi dengan aman, termasuk apakah queue dashboard dilindungi dengan autentikasi, apakah serialized data dalam job tidak mengandung informasi sensitif, dan apakah failed jobs tidak terekspos ke publik. Queue yang tidak diamankan dapat menyebabkan information disclosure atau arbitrary code execution jika penyerang dapat memasukkan job berbahaya ke dalam antrian.

Blade Template Security berkaitan dengan cara Laravel merender template. Blade menggunakan sintaks {{ }} yang secara otomatis melakukan escape output HTML untuk mencegah XSS. Namun, penggunaan sintaks {!! !!} untuk raw output dan @php directive untuk kode PHP dapat menyebabkan kerentanan jika tidak digunakan dengan hati-hati. Peneliti harus mencari penggunaan {!! !!} dalam kode Blade dan memeriksa apakah data yang ditampilkan melalui sintaks ini telah dibersihkan dengan benar.
**Additional detail on Case Studies and Lessons Learned from Major Bug Bounty Programs**

Studi kasus dari program Bug Bounty perusahaan-perusahaan besar dunia dapat memberikan pelajaran berharga bagi perancangan program Bug Bounty SHYNESv2. Analisis terhadap keberhasilan dan kegagalan program-program ini membantu dalam mengidentifikasi praktik terbaik dan menghindari kesalahan yang umum terjadi.

Program Bug Bounty Google merupakan salah satu program paling sukses dan telah berjalan sejak tahun 2010. Google telah membayar lebih dari  juta kepada peneliti keamanan selama lebih dari satu dekade program berjalan. Pelajaran yang dapat dipetik dari program Google termasuk pentingnya respons yang cepat terhadap laporan peneliti dengan rata-rata waktu respons kurang dari 24 jam, penggunaan Chrome Vulnerability Reward Panel untuk memberikan update status yang transparan kepada peneliti, penyediaan reward yang kompetitif untuk mempertahankan minat peneliti top, dan pengakuan publik melalui Hall of Fame dan publikasi statistik program secara berkala. Google juga terkenal dengan kebijakan reward yang besar untuk kerentanan tertentu, termasuk hingga .000 untuk kerentanan sandbox escape pada Chrome.

Program Bug Bounty Facebook (Meta) juga merupakan salah satu program yang paling dihormati di komunitas peneliti keamanan. Facebook telah membayar lebih dari  juta sejak program diluncurkan pada tahun 2011. Pelajaran dari program Facebook termasuk pentingnya komunikasi yang personal dengan peneliti dengan menyediakan dedicated triage team yang berinteraksi langsung dengan peneliti, penggunaan platform HackerOne untuk manajemen program yang efisien, penyediaan reward tambahan (bonus) untuk temuan yang dilengkapi dengan patch atau saran perbaikan yang detail, dan penyelenggaraan acara Facebook Bug Bounty Meetup untuk membangun hubungan dengan komunitas peneliti.

Program Bug Bounty Microsoft mencakup berbagai produk dan layanan, dengan reward tertinggi hingga .000 untuk kerentanan tertentu di Hyper-V. Pelajaran dari program Microsoft termasuk pentingnya scope yang jelas dengan pemisahan yang tegas antara berbagai produk dan layanan yang dicakup oleh program, penggunaan Qualified researchers program yang memungkinkan peneliti terpercaya mendapatkan akses awal ke produk sebelum rilis publik, dan penerapan Coordinated Vulnerability Disclosure (CVD) policy yang terstruktur dengan timeline yang jelas.

Program Bug Bounty Departemen Pertahanan Amerika Serikat (DoD) melalui Hack the Pentagon merupakan contoh sukses program Bug Bounty di sektor pemerintahan. Program ini membuktikan bahwa pendekatan Bug Bounty dapat efektif bahkan untuk organisasi dengan persyaratan keamanan yang sangat ketat. Pelajaran dari program DoD termasuk pentingnya kejelasan hukum dengan menyediakan safe harbor provision yang jelas untuk melindungi peneliti, verifikasi identitas yang ketat untuk peneliti yang berpartisipasi dalam program yang melibatkan sistem sensitif, dan kolaborasi dengan platform Bug Bounty profesional untuk mengelola program.

Program Bug Bounty di Indonesia juga memberikan pelajaran berharga. GoTo Financial (sebelumnya Gojek) menjalankan program Bug Bounty yang sukses melalui platform HackerOne. Pelajaran dari program GoTo Financial termasuk pentingnya program yang disesuaikan dengan konteks lokal dengan bahasa Indonesia yang digunakan dalam aturan program, penyesuaian reward dengan standar biaya hidup di Indonesia sambil tetap kompetitif secara global, dan pembinaan komunitas peneliti lokal melalui workshop dan meetup.

Dari berbagai studi kasus ini, beberapa praktik terbaik yang dapat diterapkan dalam program Bug Bounty SHYNESv2 meliputi respons yang cepat terhadap laporan peneliti, komunikasi yang transparan dan personal, aturan program yang jelas dan mudah dipahami, skema reward yang kompetitif dan proporsional, safe harbor provision yang melindungi peneliti yang bertindak dengan itikad baik, pengakuan publik melalui Hall of Fame dan publikasi statistik, kolaborasi dengan platform Bug Bounty profesional, dan evaluasi program secara berkala untuk perbaikan berkelanjutan.
**Additional detail on Bug Bounty Program Launch Strategy and Phased Rollout**

Peluncuran program Bug Bounty SHYNESv2 harus dilakukan secara bertahap untuk meminimalkan risiko dan memaksimalkan efektivitas program. Strategi peluncuran bertahap memungkinkan organisasi untuk menguji infrastruktur program, membangun proses internal, dan menyesuaikan aturan berdasarkan pengalaman sebelum program dibuka untuk peneliti yang lebih luas.

Fase pertama adalah fase persiapan yang berlangsung selama 1 hingga 2 bulan. Pada fase ini, organisasi menyiapkan infrastruktur teknis termasuk lingkungan staging, tools yang akan disediakan, sistem pelaporan, dan dokumentasi program. Tim program dibentuk dan diberikan pelatihan tentang proses Bug Bounty. Perbaikan kerentanan prioritas tinggi yang telah diidentifikasi harus diselesaikan pada fase ini. Dokumen hukum termasuk NDA dan Rules of Engagement disusun dan direview oleh tim legal. Target yang akan diuji ditentukan dan distrukturkan dalam scope dokumen. Pada akhir fase ini, semua komponen program harus siap untuk diuji coba secara internal.

Fase kedua adalah fase uji coba internal yang berlangsung selama 2 hingga 4 minggu. Pada fase ini, program diuji coba dengan peneliti internal atau peneliti yang diundang secara terbatas dari kalangan terpercaya. Tujuan dari uji coba internal adalah untuk menguji alur program secara end-to-end, mengidentifikasi masalah dalam proses pelaporan dan triase, memvalidasi kejelasan aturan dan dokumentasi, menguji fungsionalitas lingkungan staging, dan mengkalibrasi skema reward. Feedback dari peserta uji coba internal digunakan untuk menyempurnakan program sebelum peluncuran ke peneliti eksternal.

Fase ketiga adalah fase private beta yang berlangsung selama 3 hingga 6 bulan. Pada fase ini, program dibuka untuk peneliti eksternal yang diundang secara selektif. Jumlah peneliti dibatasi antara 20 hingga 50 peneliti yang dipilih berdasarkan reputasi, keahlian, dan pengalaman dalam program Bug Bounty. Tujuan dari fase private beta adalah untuk membangun reputasi program di komunitas peneliti, menguji skalabilitas proses triase, mengumpulkan data tentang jumlah dan jenis temuan yang diharapkan, dan menyempurnakan aturan berdasarkan pengalaman dengan peneliti eksternal. Pada fase ini, reward dibayarkan sesuai dengan skema yang telah ditetapkan.

Fase keempat adalah fase perluasan private program yang berlangsung selama 6 bulan berikutnya. Pada fase ini, jumlah peneliti ditingkatkan secara bertahap dengan mengundang peneliti tambahan. Jumlah peneliti dapat ditingkatkan menjadi 100 hingga 200 peneliti. Tujuan dari fase ini adalah untuk meningkatkan cakupan pengujian, mengidentifikasi kerentanan yang lebih kompleks yang mungkin terlewatkan oleh jumlah peneliti yang lebih kecil, dan menguji ketahanan infrastruktur program terhadap peningkatan volume laporan. Pada fase ini, metrik program mulai dikumpulkan dan dianalisis untuk mengevaluasi efektivitas program.

Fase kelima adalah fase public launch yang dilakukan setelah program private berjalan stabil selama minimal 12 bulan. Keputusan untuk membuka program ke publik didasarkan pada evaluasi yang komprehensif, termasuk kesiapan tim untuk menangani peningkatan volume laporan, stabilitas proses triase dan remediasi, ketersediaan anggaran yang cukup untuk program publik, dan kesiapan infrastruktur teknis untuk menangani pengujian oleh peneliti dalam jumlah besar. Program publik akan menarik lebih banyak peneliti dan menghasilkan lebih banyak temuan, namun juga memerlukan sumber daya yang lebih besar untuk pengelolaan.

Setiap fase transisi harus didahului oleh evaluasi yang cermat dan persiapan yang matang. Jika pada suatu fase ditemukan masalah yang signifikan, program harus ditunda dan diperbaiki sebelum melanjutkan ke fase berikutnya. Pendekatan bertahap ini memungkinkan organisasi untuk belajar dan beradaptasi secara gradual, mengurangi risiko kegagalan program yang dapat merusak reputasi organisasi dan hubungan dengan komunitas peneliti.
**Additional detail on Quality Assurance and Program Auditing**

Quality assurance dan auditing merupakan komponen penting untuk memastikan bahwa program Bug Bounty berjalan sesuai dengan standar yang telah ditetapkan. Proses QA dan auditing yang terstruktur membantu mengidentifikasi kelemahan dalam program dan memastikan bahwa program memberikan nilai yang diharapkan.

Internal audit program harus dilakukan setiap triwulan oleh tim yang independen dari tim operasional program. Audit ini mencakup pemeriksaan kepatuhan terhadap aturan program dengan memeriksa apakah semua laporan ditangani sesuai dengan prosedur yang telah ditetapkan. Pemeriksaan akurasi severity scoring dilakukan dengan mengambil sampel laporan yang telah diproses dan memverifikasi bahwa severity scoring telah dilakukan dengan benar sesuai dengan standar CVSS v3.1. Audit juga mencakup pemeriksaan ketepatan pembayaran reward, di mana sampel pembayaran diperiksa untuk memastikan bahwa reward dibayarkan sesuai dengan skema yang telah ditetapkan dan bahwa pembayaran dilakukan tepat waktu.

Review dokumentasi program harus dilakukan secara berkala untuk memastikan bahwa dokumentasi tetap akurat dan up-to-date. Perubahan dalam aplikasi, infrastruktur, atau aturan program harus dicerminkan dalam dokumentasi. Dokumentasi yang usang dapat menyebabkan kebingungan dan pelanggaran aturan oleh peneliti. Setiap perubahan dalam aturan program harus dikomunikasikan kepada semua peneliti yang terdaftar dan dicatat dalam changelog program.

Survey kepuasan peneliti harus dilakukan secara berkala untuk mengumpulkan feedback tentang pengalaman mereka dalam program. Survey mencakup aspek seperti kejelasan aturan, kemudahan pelaporan, kecepatan respons tim, keadilan penentuan severity dan reward, profesionalisme komunikasi, dan kepuasan keseluruhan. Hasil survey dianalisis untuk mengidentifikasi area yang perlu ditingkatkan. Target kepuasan peneliti minimal 80% harus ditetapkan sebagai indikator kinerja.

Pengukuran Return on Investment (ROI) program harus dilakukan setiap tahun untuk mengevaluasi efektivitas biaya program. Perhitungan ROI mencakup perbandingan antara biaya program (reward, biaya platform, biaya operasional) dengan manfaat yang diperoleh. Manfaat dapat dihitung berdasarkan biaya yang seharusnya dikeluarkan jika kerentanan ditemukan melalui metode pengujian tradisional, potensi kerugian yang dapat dicegah dengan ditemukannya kerentanan kritis, dan nilai reputasi yang diperoleh dari program yang transparan. ROI yang positif menunjukkan bahwa program memberikan nilai tambah bagi organisasi.

Benchmarking dengan program Bug Bounty lain juga penting untuk mengevaluasi kinerja program secara relatif. Benchmarking mencakup perbandingan metrik seperti rata-rata reward per temuan, persentase temuan valid, waktu respons rata-rata, dan tingkat kepuasan peneliti dengan program serupa di industri yang sama. Data benchmarking dapat diperoleh dari laporan industri yang dipublikasikan oleh platform Bug Bounty atau dari diskusi dengan organisasi lain yang menjalankan program serupa.

Continuous improvement harus menjadi prinsip yang mendasari operasional program Bug Bounty. Setiap temuan dari audit, survey, dan benchmarking harus diterjemahkan menjadi action items yang spesifik dan dapat ditindaklanjuti. Action items ini harus ditugaskan kepada anggota tim yang bertanggung jawab dan dilacak penyelesaiannya. Review berkala terhadap implementasi action items harus dilakukan untuk memastikan bahwa perbaikan benar-benar diterapkan.

Pelaporan hasil audit dan evaluasi program harus disampaikan kepada manajemen secara berkala. Laporan ini harus mencakup ringkasan eksekutif, metrik kinerja utama, temuan audit, rekomendasi perbaikan, dan rencana tindak lanjut. Laporan yang komprehensif membantu manajemen dalam memahami nilai program dan membuat keputusan yang tepat tentang alokasi sumber daya.
**Additional detail on Disaster Recovery and Business Continuity for Bug Bounty Program**

Program Bug Bounty itu sendiri perlu memiliki rencana disaster recovery dan business continuity untuk memastikan program dapat terus beroperasi meskipun terjadi gangguan atau insiden yang tidak terduga. Rencana ini mencakup prosedur untuk menangani berbagai skenario yang dapat mengganggu operasional program.

Skenario pertama adalah kehilangan akses ke platform Bug Bounty. Platform Bug Bounty pihak ketiga dapat mengalami downtime karena berbagai alasan termasuk pemeliharaan terjadwal, serangan siber, atau kegagalan infrastruktur. Untuk mengantisipasi skenario ini, program harus memiliki saluran pelaporan alternatif, seperti alamat email khusus yang dapat digunakan peneliti untuk melaporkan kerentanan ketika platform utama tidak tersedia. Informasi kontak darurat harus dikomunikasikan kepada peneliti melalui dokumentasi program. Tim program harus memonitor status platform secara berkala dan memiliki prosedur untuk beralih ke saluran alternatif jika diperlukan.

Skenario kedua adalah kehilangan anggota tim kunci. Program Bug Bounty sangat bergantung pada keahlian dan pengalaman anggota tim, terutama Triage Lead dan Program Manager. Jika anggota tim kunci meninggalkan organisasi atau tidak tersedia untuk waktu yang lama, program dapat terganggu secara signifikan. Untuk mengantisipasi skenario ini, dokumentasi proses harus lengkap dan selalu diperbarui, knowledge transfer dilakukan secara berkala antar anggota tim, cross-training diberikan kepada anggota tim lain untuk memastikan setiap peran memiliki cadangan, dan standar operasional prosedur (SOP) didokumentasikan dengan baik.

Skenario ketiga adalah insiden keamanan yang melibatkan lingkungan staging. Lingkungan staging dapat diretas oleh pihak luar yang tidak berpartisipasi dalam program, mengalami kerusakan data karena kesalahan konfigurasi, atau menjadi tidak tersedia karena kegagalan infrastruktur. Untuk mengantisipasi skenario ini, backup lingkungan staging harus dilakukan secara berkala, prosedur restore yang teruji harus tersedia, environment staging yang kedua (staging-secondary) dapat disediakan sebagai cadangan, dan monitoring keamanan diimplementasikan pada lingkungan staging.

Skenario keempat adalah perubahan regulasi yang mempengaruhi program Bug Bounty. Perubahan dalam undang-undang siber atau perlindungan data pribadi dapat mempengaruhi legalitas atau operasional program Bug Bounty. Untuk mengantisipasi skenario ini, tim legal harus memonitor perkembangan regulasi yang relevan, program harus dirancang dengan fleksibilitas yang cukup untuk beradaptasi dengan perubahan regulasi, dan review legal dilakukan secara berkala untuk memastikan kepatuhan program.

Skenario kelima adalah krisis reputasi yang melibatkan program Bug Bounty. Insiden seperti pelanggaran aturan yang serius oleh peneliti, sengketa publik dengan peneliti, atau kegagalan dalam menangani kerentanan kritis dapat merusak reputasi program. Untuk mengantisipasi skenario ini, crisis communication plan harus disusun, juru bicara yang terlatih untuk menangani pertanyaan media dan publik harus ditunjuk, prosedur eskalasi untuk menangani situasi krisis harus ditetapkan, dan hubungan baik dengan komunitas peneliti harus dibina secara berkelanjutan.

Business continuity plan untuk program Bug Bounty harus diuji secara berkala melalui simulated drill. Drill ini mensimulasikan berbagai skenario gangguan dan menguji kemampuan tim untuk merespons dan memulihkan operasional program. Hasil drill digunakan untuk mengidentifikasi kelemahan dalam rencana dan melakukan perbaikan yang diperlukan.
**Additional detail on Community Building and Researcher Engagement**

Membangun komunitas peneliti yang loyal dan engaged merupakan investasi jangka panjang yang sangat berharga bagi program Bug Bounty. Komunitas yang kuat tidak hanya menghasilkan lebih banyak temuan yang berkualitas, tetapi juga membantu mempromosikan program secara organik melalui word-of-mouth di kalangan peneliti keamanan. Strategi community building yang efektif harus menjadi bagian integral dari program Bug Bounty SHYNESv2.

Program referral atau rujukan dapat diimplementasikan untuk mendorong peneliti yang sudah ada untuk mengundang rekan-rekan mereka untuk bergabung. Peneliti yang berhasil merujuk peneliti baru yang kemudian melaporkan temuan yang valid dapat mendapatkan bonus tambahan. Program referral membantu memperluas jaringan peneliti secara organik dan mendatangkan peneliti yang telah direkomendasikan oleh rekan terpercaya.

Leaderboard dan sistem peringkat dapat memotivasi peneliti untuk lebih aktif dalam program. Leaderboard menampilkan peringkat peneliti berdasarkan jumlah temuan valid, total reward yang diterima, atau poin yang dikumpulkan. Sistem peringkat dapat dibagi menjadi beberapa kategori seperti peringkat bulanan, peringkat tahunan, dan peringkat sepanjang masa. Penghargaan khusus dapat diberikan kepada peneliti yang mencapai peringkat tertinggi, seperti bonus tambahan, merchandise eksklusif, atau undangan ke acara khusus.

Event dan kompetisi khusus dapat diselenggarakan untuk meningkatkan engagement peneliti. Bug bounty competition atau hackathon dengan tema tertentu dapat diselenggarakan secara periodik, misalnya kompetisi mencari kerentanan pada fitur baru atau kompetisi dengan fokus pada jenis kerentanan tertentu. Event ini menciptakan kegembiraan dan persaingan sehat di antara peneliti. Reward tambahan dapat ditawarkan untuk pemenang kompetisi untuk meningkatkan partisipasi.

Program mentorship dapat diimplementasikan untuk mendukung pengembangan peneliti pemula. Peneliti yang berpengalaman dapat ditunjuk sebagai mentor bagi peneliti baru, membantu mereka memahami aturan program, teknik pengujian, dan format pelaporan. Program mentorship tidak hanya membantu peneliti baru, tetapi juga memberikan pengakuan tambahan kepada mentor dan memperkuat ikatan dalam komunitas.

Bug bounty newsletter atau buletin berkala dapat dikirimkan kepada semua peneliti terdaftar untuk menjaga engagement. Buletin ini berisi update tentang program, statistik terbaru, highlight temuan menarik, tips dan trik pengujian, pengumuman tentang perubahan aturan, dan informasi tentang event yang akan datang. Buletin yang informatif dan menarik membantu menjaga peneliti tetap terhubung dengan program meskipun mereka tidak sedang aktif melakukan pengujian.

Bug bounty swag atau merchandise juga merupakan cara yang efektif untuk membangun loyalitas peneliti. Merchandise seperti kaos, stiker, hoodie, atau tumbler dengan logo program Bug Bounty dapat diberikan sebagai penghargaan tambahan atau sebagai hadiah partisipasi. Merchandise yang berkualitas dan didesain dengan baik menjadi simbol kebanggaan bagi peneliti dan mempromosikan program secara tidak langsung.

Bug bounty meetup atau pertemuan tatap muka dapat diselenggarakan secara berkala, terutama jika ada peneliti yang berada di wilayah geografis yang sama. Meetup memberikan kesempatan bagi peneliti untuk bertemu langsung dengan tim program, berbagi pengalaman, dan membangun hubungan personal. Meetup juga dapat menjadi forum untuk diskusi teknis, workshop, atau presentasi tentang keamanan aplikasi web.

Bug bounty conference atau konferensi tahunan dapat diselenggarakan untuk merayakan pencapaian program dan memberikan penghargaan kepada peneliti terbaik. Konferensi ini dapat mencakup sesi presentasi oleh peneliti tentang temuan mereka, workshop teknis, panel diskusi, dan sesi networking. Konferensi yang sukses dapat menjadi agenda tahunan yang dinantikan oleh komunitas dan memperkuat posisi SHYNESv2 sebagai organisasi yang peduli terhadap keamanan siber.
**Additional detail on Automation and Tooling Strategy**

Automasi memainkan peran penting dalam meningkatkan efisiensi dan efektivitas program Bug Bounty. Automasi dapat diterapkan di berbagai aspek program, mulai dari proses triase hingga pelaporan dan remediasi. Strategi automasi yang tepat dapat mengurangi beban kerja manual tim, mempercepat pemrosesan laporan, dan meningkatkan konsistensi dalam penanganan temuan.

Automasi triase merupakan salah satu area yang paling menjanjikan untuk penerapan automasi. Sistem automasi triase dapat melakukan validasi awal terhadap laporan yang masuk dengan memeriksa kelengkapan informasi, memvalidasi format laporan, mendeteksi pola laporan yang mencurigakan, dan memberikan skor awal berdasarkan informasi yang disediakan dalam laporan. Sistem automasi juga dapat melakukan pencarian duplikasi dengan membandingkan laporan baru dengan database laporan yang sudah ada berdasarkan endpoint, parameter, dan jenis kerentanan. Automasi triase tidak dimaksudkan untuk menggantikan Triage Lead, tetapi untuk membantu Triage Lead bekerja lebih efisien dengan menyaring laporan yang jelas-jelas tidak valid atau duplikat.

Automasi verifikasi dapat membantu dalam memvalidasi kerentanan yang dilaporkan secara otomatis. Untuk jenis kerentanan tertentu yang memiliki pola yang jelas, sistem automasi dapat mencoba mereproduksi kerentanan dengan mengirimkan payload yang sama ke endpoint yang sama dan membandingkan respons yang diterima. Automasi verifikasi sangat berguna untuk kerentanan umum seperti reflected XSS, open redirect, dan missing headers yang memiliki pola verifikasi yang relatif sederhana. Untuk kerentanan yang lebih kompleks seperti business logic errors atau IDOR, verifikasi manual oleh Triage Lead tetap diperlukan.

Automasi pelaporan dapat menghasilkan laporan berkala secara otomatis berdasarkan data yang terkumpul dalam sistem. Laporan bulanan, triwulanan, dan tahunan dapat dihasilkan secara otomatis dengan template yang telah ditentukan. Laporan ini mencakup metrik-metrik utama seperti jumlah laporan, jumlah temuan valid, reward yang dibayarkan, waktu pemrosesan rata-rata, dan distribusi severity. Automasi pelaporan menghemat waktu tim dan memastikan konsistensi dalam format dan konten laporan.

Automasi notifikasi dapat memberikan update status secara otomatis kepada peneliti pada setiap tahap pemrosesan laporan. Peneliti akan menerima notifikasi ketika laporan mereka diterima, sedang dalam proses triase, telah diverifikasi, sedang dalam proses remediasi, telah diperbaiki, dan reward telah dibayarkan. Notifikasi otomatis meningkatkan transparansi program dan mengurangi kebutuhan peneliti untuk bertanya tentang status laporan mereka secara manual.

Integrasi dengan alat pengembangan juga merupakan bagian penting dari strategi automasi. Temuan dari program Bug Bounty harus secara otomatis dibuatkan tiket di Jira atau GitHub Issues dengan informasi yang relevan. Ketika perbaikan selesai dilakukan dan kode di-merge ke branch utama, status tiket harus diperbarui secara otomatis. Integrasi ini memastikan bahwa alur kerja dari temuan hingga perbaikan tercatat dan terlacak dengan baik.

Automasi pengujian regresi keamanan dapat diimplementasikan dengan mengintegrasikan alat automated security scanner ke dalam pipeline CI/CD. Setiap kali ada perubahan kode, automated scanner akan menjalankan serangkaian test case yang mencakup kerentanan yang pernah ditemukan sebelumnya. Jika scanner mendeteksi bahwa kerentanan yang telah diperbaiki muncul kembali, proses CI/CD akan gagal dan tim development harus memperbaiki regresi tersebut sebelum kode dapat di-deploy. Pendekatan ini mencegah regresi keamanan dan memastikan bahwa perbaikan bersifat permanen.

Chatbot atau asisten virtual dapat diimplementasikan untuk memberikan dukungan 24/7 kepada peneliti. Chatbot dapat menjawab pertanyaan umum tentang aturan program, membantu peneliti dalam mengisi format laporan, memberikan informasi tentang status laporan, dan mengarahkan peneliti ke sumber daya yang relevan. Chatbot tidak dimaksudkan untuk menggantikan interaksi manusia, tetapi untuk memberikan respons instan terhadap pertanyaan-pertanyaan umum dan pertanyaan yang sering diajukan.
**Additional detail on Bug Bounty Program Policies and Standard Operating Procedures**

Standard Operating Procedures (SOP) merupakan dokumen yang mendefinisikan langkah-langkah operasional yang harus diikuti oleh tim program Bug Bounty dalam menangani setiap aspek program. SOP yang terstruktur dengan baik memastikan konsistensi, efisiensi, dan kualitas dalam operasional program, terutama ketika ada pergantian anggota tim atau ketika volume laporan meningkat secara signifikan.

SOP Penerimaan Laporan mencakup prosedur yang harus diikuti ketika laporan baru diterima. Langkah pertama adalah konfirmasi penerimaan laporan yang dikirimkan secara otomatis kepada peneliti dalam waktu maksimal 1 jam setelah laporan diterima. Langkah kedua adalah pemeriksaan kelengkapan laporan untuk memastikan bahwa laporan menyertakan semua informasi yang diperlukan. Jika ada informasi yang kurang, tim akan mengirimkan permintaan informasi tambahan dalam waktu 24 jam. Langkah ketiga adalah pemberian nomor identifikasi laporan yang unik dan pencatatan laporan dalam sistem pelacakan dengan status "Received".

SOP Triage dan Verifikasi mencakup prosedur yang harus diikuti dalam proses verifikasi laporan. Langkah pertama adalah prioritisasi laporan berdasarkan severity yang dilaporkan oleh peneliti. Laporan dengan severity Critical dan High mendapatkan prioritas verifikasi dalam waktu 24 jam. Langkah kedua adalah reproduksi kerentanan dengan mengikuti langkah-langkah yang disediakan oleh peneliti. Jika tim berhasil mereproduksi, laporan diverifikasi dan status diubah menjadi "Verified". Jika tim tidak dapat mereproduksi, peneliti akan dihubungi untuk klarifikasi dalam waktu 48 jam. Langkah ketiga adalah penentuan severity menggunakan CVSS v3.1 calculator. Skor CVSS final ditentukan oleh tim triase. Langkah keempat adalah pengecekan duplikasi terhadap database laporan yang sudah ada. Langkah kelima adalah pembuatan tiket perbaikan dan penugasan kepada pengembang yang sesuai.

SOP Remediasi mencakup prosedur yang harus diikuti dalam proses perbaikan kerentanan. Langkah pertama adalah penugasan tiket perbaikan kepada pengembang yang sesuai berdasarkan area aplikasi yang terdampak. Langkah kedua adalah perbaikan kerentanan oleh pengembang sesuai dengan rekomendasi yang diberikan. Perbaikan harus di-review oleh pengembang lain (peer review) sebelum di-merge. Langkah ketiga adalah pengujian oleh tim QA untuk memverifikasi bahwa kerentanan telah diperbaiki dan tidak ada efek samping. Langkah keempat adalah deployment perbaikan ke lingkungan staging untuk pengujian lebih lanjut. Langkah kelima adalah notifikasi kepada peneliti bahwa kerentanan telah diperbaiki dan permintaan untuk melakukan verifikasi ulang.

SOP Reward dan Pembayaran mencakup prosedur yang harus diikuti dalam proses pembayaran reward. Langkah pertama adalah konfirmasi kepada peneliti bahwa temuan mereka telah diverifikasi dan reward telah disetujui. Langkah kedua adalah permintaan informasi pembayaran dari peneliti, termasuk preferensi metode pembayaran (transfer bank atau cryptocurrency). Langkah ketiga adalah verifikasi informasi pembayaran untuk memastikan keakuratan data. Langkah keempat adalah pemrosesan pembayaran sesuai dengan metode yang dipilih dalam waktu yang telah ditentukan (14 hari untuk transfer bank, 7 hari untuk cryptocurrency). Langkah kelima adalah konfirmasi pembayaran kepada peneliti dan pencatatan pembayaran dalam sistem keuangan.

SOP Komunikasi dengan Peneliti mencakup prosedur yang harus diikuti dalam berkomunikasi dengan peneliti. Semua komunikasi harus dilakukan secara profesional dan sopan. Respons terhadap pertanyaan peneliti harus diberikan dalam waktu maksimal 24 jam. Bahasa yang digunakan dalam komunikasi harus jelas, tidak ambigu, dan mudah dipahami. Informasi tentang status laporan harus diberikan secara transparan. Jika terjadi perselisihan, tim harus berusaha menyelesaikan secara musyawarah dan mengacu pada aturan yang telah ditetapkan. Semua komunikasi dengan peneliti harus dicatat dalam sistem untuk keperluan audit.

SOP Pelanggaran Aturan mencakup prosedur yang harus diikuti ketika terjadi pelanggaran aturan oleh peneliti. Langkah pertama adalah investigasi untuk mengumpulkan fakta tentang pelanggaran yang terjadi. Langkah kedua adalah klasifikasi pelanggaran ke dalam kategori ringan, sedang, atau berat berdasarkan tingkat keparahannya. Langkah ketiga adalah penentuan sanksi yang sesuai dengan kategori pelanggaran. Langkah keempat adalah komunikasi sanksi kepada peneliti dengan penjelasan yang jelas tentang pelanggaran dan sanksi yang diberikan. Langkah kelima adalah dokumentasi pelanggaran dan sanksi dalam sistem untuk referensi di masa mendatang.
**Additional detail on Technology-Specific Attack Vectors for SHYNESv2**

Aplikasi SHYNESv2 Fashion E-Commerce yang dibangun dengan Laravel dan dihosting di Railway memiliki karakteristik teknologi yang unik yang menciptakan vektor serangan spesifik. Peneliti Bug Bounty yang memahami karakteristik ini dapat mengidentifikasi kerentanan yang mungkin tidak terlihat oleh peneliti yang hanya mengandalkan teknik pengujian umum. Pemahaman tentang vektor serangan spesifik teknologi ini juga penting bagi tim pengembangan untuk menerapkan kontrol keamanan yang tepat.

Laravel Debug Mode merupakan salah satu vektor serangan yang paling umum dalam aplikasi Laravel. Ketika APP_DEBUG diatur ke true, Laravel akan menampilkan halaman error yang sangat informatif yang berisi stack trace, query SQL, variabel lingkungan, dan konfigurasi aplikasi. Informasi ini dapat digunakan oleh penyerang untuk memahami struktur aplikasi, menemukan kerentanan potensial, dan bahkan mengekstrak kredensial database. Peneliti dapat menguji keberadaan debug mode dengan memicu error pada aplikasi, misalnya dengan mengirimkan parameter yang tidak valid, mengakses route yang tidak ada, atau menyebabkan exception melalui input yang dimanipulasi. Jika aplikasi menampilkan halaman error Whoops yang detail, maka debug mode aktif dan ini merupakan kerentanan information disclosure.

Laravel ENV File Exposure merupakan vektor serangan yang memungkinkan penyerang mengakses file .env yang berisi konfigurasi sensitif aplikasi. File .env berisi APP_KEY, DB_PASSWORD, MAIL_PASSWORD, API keys, dan berbagai kredensial lainnya. Meskipun konfigurasi server yang baik seharusnya mencegah akses ke file .env, misconfiguration seperti konfigurasi web server yang salah dapat menyebabkan file .env dapat diakses melalui browser. Peneliti dapat menguji keberadaan kerentanan ini dengan mencoba mengakses file .env melalui berbagai URL seperti /.env, /public/.env, atau /storage/.env. Jika file .env dapat diakses, ini merupakan kerentanan kritis yang memungkinkan penyerang mendapatkan akses ke seluruh kredensial aplikasi.

Laravel Serialized Data Injection merupakan vektor serangan yang menargetkan mekanisme serialisasi dan deserialisasi data dalam Laravel. Laravel menggunakan serialisasi untuk berbagai keperluan seperti session data, cache data, dan queue jobs. Jika penyerang dapat memanipulasi data serialized, mereka dapat menyebabkan deserialisasi tidak aman yang dapat mengakibatkan remote code execution. Kerentanan ini terutama relevan pada aplikasi yang menggunakan unserialize() secara langsung atau yang memiliki custom serialization logic. Peneliti dapat menguji kerentanan ini dengan memanipulasi session cookie, cache key, atau queue payload yang mengandung data serialized.

Laravel Route and Controller Security merupakan aspek penting yang perlu diuji. Laravel menggunakan route definition untuk memetakan URL ke controller. Peneliti harus menguji apakah route yang seharusnya dilindungi oleh middleware autentikasi benar-benar dilindungi. Pengecekan dilakukan dengan mengakses route yang memerlukan autentikasi tanpa login. Peneliti juga harus menguji apakah route dengan parameter yang mengandung ID dapat diakses oleh pengguna yang tidak berhak (IDOR). Laravel route model binding, meskipun nyaman, dapat menyebabkan IDOR jika tidak dikombinasikan dengan policy atau gate yang tepat. Selain itu, peneliti harus memeriksa implicit route binding yang dapat menyebabkan mass assignment jika tidak dikonfigurasi dengan benar.

Laravel Artisan Console Exposure merupakan vektor serangan yang memungkinkan penyerang menjalankan perintah Artisan melalui web. Dalam lingkungan produksi yang dikonfigurasi dengan benar, akses ke Artisan console melalui web harus dinonaktifkan. Namun, misconfiguration atau penggunaan paket pihak ketiga yang memungkinkan akses Artisan melalui web dapat menyebabkan kerentanan kritis. Peneliti dapat menguji keberadaan kerentanan ini dengan mencoba mengakses route yang memungkinkan eksekusi perintah Artisan.

Railway Platform-Specific Vulnerabilities juga perlu dipertimbangkan. Railway sebagai platform hosting memiliki karakteristik keamanan tertentu yang perlu dipahami. Peneliti harus menguji apakah konfigurasi environment variables di Railway sudah benar dan tidak mengekspos informasi sensitif. Railway menyediakan fitur untuk mengatur environment variables melalui dashboard, dan konfigurasi yang salah dapat menyebabkan informasi sensitif terekspos. Peneliti juga harus menguji apakah konfigurasi network isolation sudah benar, misalnya apakah aplikasi di Railway dapat mengakses resource internal yang seharusnya tidak dapat diakses.

PostgreSQL Database-Specific Vulnerabilities juga relevan. PostgreSQL memiliki fitur-fitur tertentu yang dapat menjadi vektor serangan jika tidak dikonfigurasi dengan benar. Fitur pgcrypto untuk enkripsi data, row-level security untuk pembatasan akses baris data, dan foreign data wrappers untuk akses data eksternal perlu dikonfigurasi dengan benar. Peneliti harus menguji apakah prinsip least privilege diterapkan pada koneksi database aplikasi, apakah fungsi berbahaya di PostgreSQL dinonaktifkan, dan apakah konfigurasi SSL untuk koneksi database sudah benar.
**Additional detail on Appendix: Sample Bug Bounty Report**

Berikut adalah contoh laporan Bug Bounty yang baik dan lengkap untuk memberikan gambaran kepada peneliti tentang bagaimana menyusun laporan yang efektif. Contoh ini menggunakan kerentanan SQL Injection fiktif pada aplikasi SHYNESv2 yang ditemukan di lingkungan staging.

`markdown
## [Critical] SQL Injection pada Endpoint /api/products/search

**Endpoint**: https://staging.shynesv2.up.railway.app/api/products/search
**Metode**: GET
**Parameter**: q (search query)

### Deskripsi
Ditemukan kerentanan SQL Injection pada endpoint pencarian produk. Parameter 'q' tidak divalidasi dengan baik dan digunakan langsung dalam query SQL tanpa menggunakan parameter binding. Kerentanan ini memungkinkan penyerang untuk mengekstrak data dari database, termasuk data pengguna, data transaksi, dan data sensitif lainnya.

### Steps to Reproduce
1. Buka Burp Suite dan konfigurasi proxy
2. Kirim request GET ke endpoint /api/products/search dengan parameter q
3. Modifikasi parameter q dengan payload SQL Injection: q=test' OR '1'='1
4. Amati respons yang mengembalikan seluruh data produk tanpa filter
5. Gunakan payload UNION untuk mengekstrak data dari tabel lain

### Proof of Concept
Request:
`
GET /api/products/search?q=test' UNION SELECT table_name,NULL FROM information_schema.tables-- HTTP/1.1
Host: staging.shynesv2.up.railway.app
`

Response:
`json
{
  "products": [
    {"table_name": "users"},
    {"table_name": "products"},
    {"table_name": "orders"},
    {"table_name": "payments"},
    {"table_name": "contracts"}
  ]
}
`

### Impact
Penyerang dapat mengekstrak seluruh data dalam database, termasuk data pengguna (nama, email, password hash, alamat), data transaksi (detail pesanan, informasi pembayaran), dan data kontrak (dokumen kontrak, data mitra). Dampak pada kerahasiaan data sangat tinggi.

### CVSS v3.1 Score
Base Score: 9.8 (Critical)
Vector: CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:H

### Suggested Fix
Gunakan parameter binding Eloquent ORM atau Query Builder Laravel sebagai pengganti raw SQL query. Hindari penggunaan DB::raw() atau DB::statement() dengan concatenation string.
Contoh perbaikan:
`php
// Sebelum (rentan SQLi)
 = DB::select("SELECT * FROM products WHERE name LIKE '%" . ->q . "%'");

// Sesudah (aman)
 = Product::where('name', 'like', '%' . ->q . '%')->get();
`

### Attachment
- burp_request_sqli.txt
- screenshot_sqli_response.png
- database_extraction_poc.txt
`

Contoh laporan ini menunjukkan bagaimana laporan yang baik disusun dengan informasi yang lengkap, langkah reproduksi yang jelas, proof of concept yang meyakinkan, dampak yang terukur, dan rekomendasi perbaikan yang konkret. Peneliti yang mengikuti format ini akan mempercepat proses triase dan meningkatkan kredibilitas laporan mereka.
**Additional detail on Appendix: Glossary of Terms**

Glosarium ini menyediakan definisi istilah-istilah teknis yang digunakan dalam laporan ini untuk memudahkan pemahaman pembaca yang mungkin tidak familiar dengan terminologi keamanan siber. Istilah-istilah ini sering digunakan dalam konteks program Bug Bounty dan pengujian keamanan aplikasi web secara umum.

Bug Bounty: Program insentif di mana organisasi menawarkan reward finansial kepada peneliti keamanan yang berhasil menemukan dan melaporkan kerentanan dalam sistem atau aplikasi mereka secara bertanggung jawab.

Crowdsourced Security: Pendekatan keamanan yang memanfaatkan kecerdasan kolektif dari sekelompok besar individu (crowd) untuk mengidentifikasi dan menyelesaikan masalah keamanan.

CVSS (Common Vulnerability Scoring System): Kerangka kerja terbuka yang digunakan untuk mengomunikasikan karakteristik dan tingkat keparahan kerentanan perangkat lunak, dikelola oleh FIRST.

CVE (Common Vulnerabilities and Exposures): Sistem identifikasi standar untuk kerentanan keamanan yang dikelola oleh MITRE Corporation, memberikan nomor unik untuk setiap kerentanan.

Disclosure: Proses pengungkapan informasi tentang kerentanan keamanan kepada publik atau pihak yang berkepentingan.

DoS/DDoS (Denial of Service/Distributed Denial of Service): Serangan yang bertujuan untuk membuat sistem atau layanan tidak tersedia bagi pengguna yang sah dengan membanjiri sistem dengan lalu lintas atau permintaan yang berlebihan.

Endpoint: URL atau titik akhir dalam API yang menerima permintaan dari klien dan mengembalikan respons.

Exploit: Kode atau teknik yang digunakan untuk mengeksploitasi kerentanan dalam sistem atau aplikasi.

False Positive: Laporan kerentanan yang ternyata bukan kerentanan setelah diverifikasi, sering kali disebabkan oleh kesalahan interpretasi dari respons sistem.

Fuzzing: Teknik pengujian yang melibatkan pengiriman data acak atau tidak valid ke dalam input aplikasi untuk menemukan kerentanan atau perilaku yang tidak diinginkan.

Hall of Fame: Halaman penghargaan yang menampilkan nama peneliti yang telah berkontribusi dalam program Bug Bounty.

HMAC (Hash-based Message Authentication Code): Kode autentikasi pesan yang menggunakan fungsi hash kriptografi dan kunci rahasia untuk memverifikasi integritas dan autentisitas pesan.

IDOR (Insecure Direct Object References): Kerentanan yang terjadi ketika aplikasi menggunakan identifier langsung untuk merujuk ke objek data tanpa memverifikasi otorisasi pengguna.

MITRE: Organisasi nirlaba yang mengelola database CVE dan berbagai standar keamanan siber lainnya.

NDA (Non-Disclosure Agreement): Perjanjian hukum yang mengatur kerahasiaan informasi yang diungkapkan antara dua pihak atau lebih.

OWASP (Open Web Application Security Project): Organisasi nirlaba internasional yang berfokus pada peningkatan keamanan perangkat lunak, terkenal dengan OWASP Top Ten dan Testing Guide.

Payload: Data yang dikirimkan sebagai bagian dari serangan siber, biasanya berisi kode atau perintah yang dirancang untuk mengeksploitasi kerentanan.

Penetration Testing: Pengujian keamanan yang dilakukan secara manual atau otomatis untuk mengidentifikasi kerentanan dalam sistem atau aplikasi.

PoC (Proof of Concept): Bukti yang menunjukkan bahwa kerentanan benar-benar dapat dieksploitasi, biasanya berupa request dan response HTTP, screenshot, atau kode eksploitasi.

Reconnaissance: Tahap pengumpulan informasi tentang target sebagai bagian dari proses pengujian keamanan.

Remediation: Proses perbaikan kerentanan yang telah ditemukan dan diverifikasi.

Responsible Disclosure: Praktik etis di mana peneliti melaporkan kerentanan kepada organisasi terlebih dahulu sebelum mempublikasikannya ke publik.

ROE (Rules of Engagement): Dokumen yang mengatur aturan, batasan, dan prosedur yang harus diikuti oleh peneliti dalam program Bug Bounty.

Severity: Tingkat keparahan kerentanan yang diukur menggunakan standar CVSS, dikategorikan menjadi Critical, High, Medium, Low, dan Informational.

SLA (Service Level Agreement): Perjanjian tentang tingkat layanan yang harus dipenuhi, dalam konteks Bug Bounty mencakup batas waktu untuk merespons, memverifikasi, dan memperbaiki kerentanan.

SQL Injection: Kerentanan yang memungkinkan penyerang untuk menyisipkan perintah SQL ke dalam query database melalui input pengguna.

SSRF (Server-Side Request Forgery): Kerentanan yang memungkinkan penyerang untuk memaksa server melakukan permintaan ke target internal atau eksternal.

Staging: Lingkungan pengujian yang merupakan salinan dari lingkungan produksi dengan data dummy, digunakan untuk pengujian keamanan.

Triase: Proses evaluasi dan prioritisasi laporan kerentanan untuk menentukan validitas, severity, dan urgensi penanganan.

VDP (Vulnerability Disclosure Program): Program yang menyediakan saluran pelaporan kerentanan tanpa menawarkan imbalan finansial, berbeda dengan Bug Bounty yang menawarkan reward.

WAF (Web Application Firewall): Sistem keamanan yang memonitor, memfilter, dan memblokir lalu lintas HTTP yang mencurigakan atau berbahaya.

XSS (Cross-Site Scripting): Kerentanan yang memungkinkan penyerang untuk menyisipkan skrip jahat ke dalam halaman web yang dilihat oleh pengguna lain.
**Additional detail on Appendix: Sample Rules of Engagement Summary**

Berikut adalah ringkasan Rules of Engagement (RoE) yang akan diberikan kepada setiap peneliti yang berpartisipasi dalam program Bug Bounty SHYNESv2. Dokumen lengkap akan disediakan dalam format PDF yang ditandatangani secara elektronik.

**1. TUJUAN PROGRAM**
Program Bug Bounty SHYNESv2 bertujuan untuk mengidentifikasi dan memperbaiki kerentanan keamanan dalam aplikasi SHYNESv2 Fashion E-Commerce dengan memanfaatkan keahlian komunitas peneliti keamanan global. Program ini dijalankan dengan prinsip responsible disclosure dan crowdsourced security.

**2. SCOPE PENGUJIAN**
In Scope: Semua subdomain dan endpoint di bawah *.staging.shynesv2.up.railway.app, termasuk API endpoint, halaman web, dan seluruh fitur yang tersedia di lingkungan staging.
Out of Scope: Domain produksi shynesv2.up.railway.app, server infrastructure Railway, database server, caching server, layanan pihak ketiga (payment gateway, email service, cloud storage), serangan DoS/DDoS, social engineering, physical security testing.

**3. METODE PENGUJIAN YANG DIIZINKAN**
Metode yang diizinkan mencakup manual testing menggunakan browser dan tools keamanan, automated scanning menggunakan tools yang disediakan, parameter fuzzing, teknik injeksi (SQLi, XSS, dll), authentication testing, authorization testing, business logic testing.
Metode yang dilarang mencakup serangan DoS/DDoS, brute force dengan volume sangat tinggi, social engineering, physical security testing, akses ke data pengguna riil, modifikasi atau penghapusan data, eksfiltrasi data melebihi 10 record.

**4. PROSEDUR PELAPORAN**
Peneliti wajib melaporkan kerentanan melalui platform Bug Bounty yang telah ditentukan dengan mengikuti format template pelaporan yang telah disediakan. Laporan harus mencakup endpoint, metode, parameter, deskripsi, langkah reproduksi, proof of concept, dampak, skor CVSS, dan rekomendasi perbaikan.

**5. VERIFIKASI DAN REWARD**
Setiap laporan akan diverifikasi oleh tim keamanan SHYNESv2 dalam waktu maksimal 5 hari kerja. Reward akan dibayarkan setelah kerentanan diverifikasi dan diperbaiki, dengan besaran sesuai skema yang telah ditetapkan berdasarkan severity CVSS v3.1.

**6. KERAHASIAAN**
Peneliti wajib menjaga kerahasiaan semua informasi yang diperoleh selama program, termasuk detail kerentanan, arsitektur aplikasi, data dummy, dan informasi internal lainnya. Pelanggaran kerahasiaan akan mengakibatkan diskualifikasi dan potensi tindakan hukum.

**7. SANKSI PELANGGARAN**
Pelanggaran ringan (peringatan): Pelaporan tidak lengkap, keterlambatan respons.
Pelanggaran sedang (diskualifikasi temuan): Pengujian di luar scope tanpa sengaja.
Pelanggaran berat (diskualifikasi permanen): DoS/DDoS, social engineering, pencurian data, publikasi tanpa izin.

**8. HAK KEKAYAAN INTELEKTUAL**
Temuan dan laporan yang diserahkan oleh peneliti menjadi milik SHYNESv2 untuk keperluan perbaikan dan pengamanan sistem. Peneliti mendapatkan hak untuk mencantumkan temuan dalam portofolio pribadi setelah periode embargo berakhir.

**9. PELEPASAN TUNTUTAN (SAFE HARBOR)**
SHYNESv2 menyatakan tidak akan menuntut peneliti secara pidana atau perdata atas aktivitas pengujian yang dilakukan sesuai dengan aturan program. Safe harbor ini berlaku selama peneliti mematuhi seluruh ketentuan dalam Rules of Engagement.

**10. HUKUM YANG BERLAKU**
Program Bug Bounty ini tunduk pada hukum Republik Indonesia, termasuk UU ITE dan UU Perlindungan Data Pribadi. Setiap sengketa yang timbul akan diselesaikan melalui musyawarah terlebih dahulu, dan jika tidak mencapai kesepakatan, melalui pengadilan negeri yang berwenang.

Ringkasan Rules of Engagement di atas merupakan panduan singkat untuk peneliti. Dokumen lengkap yang mengikat secara hukum akan disediakan dan harus ditandatangani sebelum peneliti diberikan akses ke lingkungan staging.
**Additional detail on Appendix: Frequently Asked Questions (FAQ)**

FAQ ini disusun untuk membantu peneliti yang baru bergabung dalam program Bug Bounty SHYNESv2. Pertanyaan-pertanyaan yang sering diajukan tentang program ini dijawab secara singkat dan jelas.

**Q: Bagaimana cara mendaftar untuk program Bug Bounty SHYNESv2?**
A: Calon peneliti dapat mendaftar melalui platform Bug Bounty yang telah ditunjuk. Proses registrasi mencakup pengisian data diri, verifikasi identitas, dan penandatanganan NDA secara elektronik. Setelah registrasi selesai, peneliti akan mendapatkan akses ke lingkungan staging dan dokumentasi program.

**Q: Apakah saya perlu membayar untuk berpartisipasi?**
A: Tidak. Partisipasi dalam program Bug Bounty SHYNESv2 sepenuhnya gratis. Organisasi menyediakan akses ke lingkungan staging dan tools yang diperlukan tanpa biaya apapun kepada peneliti.

**Q: Berapa besar reward yang bisa saya dapatkan?**
A: Reward bervariasi berdasarkan tingkat severity kerentanan yang ditemukan. Kerentanan Critical mendapatkan .500, High , Medium , Low , dan Informational mendapatkan pengakuan di Hall of Fame. Besaran reward ini dapat berubah sewaktu-waktu dengan pemberitahuan sebelumnya.

**Q: Bagaimana cara saya menerima pembayaran?**
A: Pembayaran dapat diterima melalui transfer bank atau cryptocurrency (Bitcoin, Ethereum, USDT). Informasi pembayaran dapat diatur melalui profil peneliti di platform Bug Bounty. Pembayaran diproses dalam waktu 14 hari kerja untuk transfer bank dan 7 hari kerja untuk cryptocurrency.

**Q: Berapa lama waktu yang dibutuhkan untuk verifikasi laporan saya?**
A: Tim keamanan SHYNESv2 berusaha untuk memverifikasi laporan dalam waktu maksimal 5 hari kerja. Laporan dengan severity Critical dan High mendapatkan prioritas verifikasi dalam waktu 24 jam. Waktu verifikasi dapat bervariasi tergantung pada kompleksitas kerentanan dan volume laporan yang masuk.

**Q: Apa yang terjadi jika laporan saya adalah duplikat?**
A: Program Bug Bounty SHYNESv2 menerapkan kebijakan first-come-first-served. Hanya peneliti pertama yang melaporkan kerentanan yang akan mendapatkan reward. Jika laporan Anda terdeteksi sebagai duplikat, Anda akan diberitahu dan laporan akan ditutup tanpa reward, namun Anda tetap diakui dalam sistem.

**Q: Dapatkah saya mempublikasikan temuan saya?**
A: Ya, setelah periode embargo berakhir. Untuk kerentanan Critical dan High, periode embargo adalah 90 hari. Untuk kerentanan Medium dan Low, periode embargo adalah 60 hari. Publikasi harus menyebutkan bahwa kerentanan telah diperbaiki oleh SHYNESv2 dan tidak mengungkapkan data sensitif.

**Q: Apakah saya bisa mendapatkan CVE untuk temuan saya?**
A: CVE assignment dilakukan untuk kerentanan dengan severity Critical dan High. Tim keamanan SHYNESv2 akan mengajukan permintaan CVE ke MITRE dan Anda akan dicantumkan sebagai penemu dalam entri CVE.

**Q: Apa yang harus saya lakukan jika saya menemukan kerentanan kritis yang memerlukan respons segera?**
A: Gunakan saluran komunikasi darurat yang terpisah dari saluran pelaporan reguler. Informasi kontak darurat tersedia di dokumentasi program. Tim incident response akan menangani laporan darurat dengan prioritas tertinggi.

**Q: Apakah saya akan mendapatkan akses ke kode sumber aplikasi?**
A: Tidak. Program Bug Bounty SHYNESv2 hanya menyediakan akses ke lingkungan staging untuk pengujian black-box. Kode sumber aplikasi tidak disediakan untuk peneliti.

**Q: Bisakah saya berpartisipasi secara anonim?**
A: Identitas Anda akan diketahui oleh tim keamanan SHYNESv2 untuk keperluan verifikasi dan pembayaran. Namun, Anda dapat memilih untuk tetap anonim di Hall of Fame publik dan publikasi disclosure.

**Q: Apa sanksi jika saya melanggar aturan?**
A: Sanksi bervariasi tergantung pada tingkat pelanggaran, mulai dari peringatan, diskualifikasi temuan, hingga diskualifikasi permanen dari program. Pelanggaran serius seperti DoS, social engineering, atau pencurian data dapat mengakibatkan tindakan hukum.
**Additional detail on Appendix: Sample Vulnerability Severity Classification Guide**

Panduan klasifikasi severity ini membantu peneliti dan tim triase dalam menentukan tingkat keparahan kerentanan secara konsisten menggunakan standar CVSS v3.1. Setiap kategori severity disertai dengan contoh kerentanan yang relevan untuk aplikasi e-commerce berbasis Laravel.

**CRITICAL (CVSS 9.0 - 10.0)**

Kerentanan Critical memiliki dampak keamanan yang sangat serius dan memerlukan respons segera. Karakteristik kerentanan Critical meliputi: dapat dieksploitasi dari jarak jauh tanpa autentikasi, memungkinkan akses penuh ke sistem atau data, menyebabkan kebocoran data massal, memungkinkan eksekusi kode di server, atau memungkinkan kontrol penuh atas aplikasi.

Contoh kerentanan Critical pada aplikasi SHYNESv2:
1. Remote Code Execution (RCE) melalui deserialisasi tidak aman pada Laravel session data
2. SQL Injection pada endpoint login yang memungkinkan authentication bypass dan akses database penuh
3. Authentication bypass pada API admin yang memungkinkan akses tanpa kredensial
4. Pre-auth RCE melalui file upload yang tidak divalidasi
5. SSRF yang memungkinkan akses ke metadata server Railway dan ekstraksi kredensial

**HIGH (CVSS 7.0 - 8.9)**

Kerentanan High memiliki dampak keamanan yang signifikan namun memerlukan kondisi tertentu untuk dieksploitasi atau memiliki dampak yang lebih terbatas dibandingkan Critical. Karakteristik kerentanan High meliputi: memungkinkan akses ke data pengguna lain, memungkinkan peningkatan hak akses, menyebabkan kebocoran data terbatas, atau memerlukan autentikasi untuk eksploitasi.

Contoh kerentanan High pada aplikasi SHYNESv2:
1. IDOR yang memungkinkan akses ke pesanan dan data pribadi pengguna lain
2. Stored XSS pada halaman produk yang memungkinkan pencurian cookie session admin
3. Privilege escalation dari pengguna biasa menjadi admin
4. SQL Injection pada endpoint yang memerlukan autentikasi
5. Webhook signature bypass yang memungkinkan pemalsuan notifikasi pembayaran

**MEDIUM (CVSS 4.0 - 6.9)**

Kerentanan Medium memiliki dampak keamanan yang terbatas atau memerlukan kondisi khusus untuk dieksploitasi. Kerentanan ini biasanya tidak menyebabkan kebocoran data sensitif secara langsung tetapi dapat digunakan sebagai bagian dari serangan yang lebih kompleks.

Contoh kerentanan Medium pada aplikasi SHYNESv2:
1. Reflected XSS yang memerlukan interaksi pengguna untuk mengeksekusi
2. CSRF pada fungsi update profil pengguna
3. Information disclosure melalui stack trace atau debug mode
4. Path traversal yang terbatas pada direktori non-kritis
5. Open redirect yang dapat digunakan untuk phishing

**LOW (CVSS 0.1 - 3.9)**

Kerentanan Low memiliki dampak keamanan yang minimal dan biasanya tidak dapat dieksploitasi secara langsung. Kerentanan ini lebih merupakan pelanggaran terhadap praktik terbaik keamanan daripada kerentanan yang dapat dieksploitasi.

Contoh kerentanan Low pada aplikasi SHYNESv2:
1. Missing security headers (HSTS, X-Frame-Options, X-Content-Type-Options)
2. Cookie tanpa atribut Secure atau HttpOnly
3. Informasi versi server yang terekspos dalam header respons
4. Directory listing yang aktif pada direktori non-kritis
5. Password policy yang lemah namun tidak dapat dieksploitasi secara langsung

**INFORMATIONAL (CVSS 0.0)**

Temuan Informational tidak memiliki dampak keamanan langsung tetapi memberikan informasi yang berguna untuk peningkatan keamanan secara umum. Temuan ini tidak mendapatkan reward finansial tetapi diakui dalam Hall of Fame.

Contoh temuan Informational pada aplikasi SHYNESv2:
1. Saran untuk mengimplementasikan Content Security Policy (CSP)
2. Rekomendasi untuk memperbarui library yang sudah usang
3. Observasi tentang potensi kerentanan di masa mendatang
4. Saran untuk meningkatkan logging dan monitoring
5. Rekomendasi arsitektur keamanan untuk fitur baru

Panduan klasifikasi ini bersifat indikatif dan keputusan final tentang severity kerentanan tetap berada pada tim triase SHYNESv2 berdasarkan perhitungan CVSS v3.1 yang objektif. Peneliti didorong untuk menyertakan perhitungan CVSS dalam laporan mereka sebagai referensi, namun skor final dapat berbeda setelah verifikasi oleh tim triase.
**Additional detail on Appendix: Reporting Template and Guidelines**

Panduan pengisian template laporan ini disusun untuk membantu peneliti dalam menyusun laporan yang efektif dan sesuai dengan standar program Bug Bounty SHYNESv2. Setiap bagian dari template laporan dijelaskan dengan panduan pengisian yang jelas.

**BAGIAN JUDUL**

Judul laporan harus mencerminkan jenis kerentanan dan lokasinya secara jelas dan ringkas. Format judul yang direkomendasikan adalah: "[Jenis Kerentanan] pada [Endpoint/Fitur] yang Memungkinkan [Dampak]". Contoh: "SQL Injection pada Endpoint /api/products/search yang Memungkinkan Ekstraksi Database". Hindari judul yang terlalu umum seperti "Bug pada Website" atau "Ada Vulnerability". Judul yang baik membantu tim triase untuk memahami temuan dengan cepat dan memprioritaskan penanganan.

**BAGIAN ENDPOINT, METODE, DAN PARAMETER**

Bagian ini harus mencantumkan informasi teknis yang tepat tentang lokasi kerentanan. Endpoint harus berupa URL lengkap termasuk domain dan path. Metode HTTP harus disebutkan dengan jelas (GET, POST, PUT, DELETE, PATCH). Parameter yang dieksploitasi harus disebutkan beserta lokasinya dalam request (query parameter, body parameter, header, atau cookie). Informasi yang tepat dan lengkap pada bagian ini memudahkan tim triase untuk langsung menuju ke lokasi kerentanan tanpa perlu mencari-cari.

**BAGIAN DESKRIPSI**

Deskripsi harus menjelaskan kerentanan secara singkat namun lengkap. Jelaskan apa kerentanan yang ditemukan, bagaimana kerentanan tersebut muncul, dan mengapa hal tersebut merupakan masalah keamanan. Deskripsi harus ditulis dalam bahasa yang jelas dan mudah dipahami, bahkan oleh pengembang yang mungkin tidak memiliki latar belakang keamanan yang mendalam. Hindari jargon yang berlebihan dan jelaskan istilah teknis jika diperlukan. Panjang deskripsi yang ideal adalah 3-5 kalimat.

**BAGIAN STEPS TO REPRODUCE**

Langkah-langkah reproduksi harus ditulis secara detail dan terstruktur sehingga tim keamanan dapat mengikuti langkah-langkah tersebut untuk memverifikasi temuan. Setiap langkah harus ditulis dalam urutan yang benar dan menggunakan bahasa yang jelas. Gunakan format penomoran untuk setiap langkah. Sertakan alat atau tools yang digunakan jika relevan. Contoh langkah yang baik: "1. Login ke aplikasi menggunakan akun buyer_staging dengan password yang disediakan. 2. Buka halaman produk di /products. 3. Klik tombol 'Beli' pada produk dengan ID 123. 4. Gunakan Burp Suite untuk mengintersep request POST ke /api/checkout. 5. Modifikasi parameter price menjadi 0. 6. Forward request dan amati bahwa pesanan berhasil diproses dengan harga Rp0."

**BAGIAN PROOF OF CONCEPT**

Proof of Concept (PoC) harus menyertakan bukti yang cukup untuk membuktikan bahwa kerentanan benar-benar dapat dieksploitasi. PoC dapat berupa potongan request dan response HTTP yang relevan, payload yang digunakan, screenshot yang menunjukkan dampak kerentanan, atau kombinasi dari semuanya. Data sensitif dalam PoC harus dianonimkan jika perlu. Pastikan PoC jelas dan mudah dipahami. Untuk kerentanan yang kompleks, video PoC dapat dilampirkan sebagai tambahan.

**BAGIAN IMPACT**

Bagian impact harus menjelaskan konsekuensi jika kerentanan dieksploitasi oleh pihak jahat. Dampak harus dijelaskan secara spesifik dan terkait dengan konteks aplikasi. Jelaskan data apa yang dapat diakses atau dimodifikasi, tindakan apa yang dapat dilakukan penyerang, dan dampak bisnis yang mungkin timbul. Contoh dampak yang baik: "Penyerang dapat mengekstrak seluruh data pengguna dari database, termasuk nama, alamat email, password hash, alamat pengiriman, dan riwayat transaksi. Data ini dapat digunakan untuk serangan phishing, pencurian identitas, atau dijual di pasar gelap."

**BAGIAN CVSS SCORE**

Skor CVSS harus dihitung menggunakan CVSS v3.1 calculator resmi yang tersedia di https://www.first.org/cvss/calculator/3.1. Cantumkan base score dan vektor CVSS yang lengkap. Jelaskan secara singkat alasan pemilihan setiap metrik dalam vektor CVSS. Contoh: "Attack Vector: Network (kerentanan dapat dieksploitasi dari jarak jauh melalui jaringan), Attack Complexity: Low (tidak diperlukan kondisi khusus), Privileges Required: None (tidak memerlukan autentikasi), User Interaction: None (tidak memerlukan interaksi pengguna), Scope: Unchanged, Confidentiality: High, Integrity: High, Availability: High."

**BAGIAN SUGGESTED FIX**

Rekomendasi perbaikan harus konkret dan dapat ditindaklanjuti oleh tim pengembang. Sertakan contoh kode atau konfigurasi jika memungkinkan. Rekomendasi harus disesuaikan dengan teknologi yang digunakan oleh aplikasi (Laravel, PostgreSQL, dll). Jelaskan mengapa rekomendasi tersebut efektif dalam memperbaiki kerentanan. Contoh rekomendasi yang baik: "Gunakan Eloquent ORM parameter binding sebagai pengganti raw SQL query. Jangan menggunakan DB::raw() atau DB::statement() dengan concatenation string. Berikut adalah contoh kode yang aman: Product::where('name', 'like', '%'.->q.'%')->get()."

**BAGIAN LAMPIRAN**

Lampiran harus disertakan untuk mendukung laporan. Lampiran dapat berupa file screenshot (format PNG atau JPEG), file request-response Burp Suite (format txt), file log, atau file pendukung lainnya. Beri nama file lampiran yang deskriptif untuk memudahkan identifikasi. Kompres file jika ukurannya terlalu besar. Pastikan lampiran tidak mengandung data sensitif yang tidak perlu.
**Additional detail on Appendix: Program Evaluation Checklist**

Checklist evaluasi program ini digunakan oleh Program Manager untuk melakukan evaluasi berkala terhadap program Bug Bounty SHYNESv2. Checklist ini mencakup aspek-aspek penting yang perlu diperiksa secara teratur untuk memastikan program berjalan dengan optimal.

**EVALUASI BULANAN**

Kelengkapan dan keakuratan dokumentasi program. Periksa apakah dokumentasi aturan main, format pelaporan, dan panduan teknis masih akurat dan up-to-date. Periksa apakah ada perubahan dalam aplikasi atau infrastruktur yang perlu dicerminkan dalam dokumentasi. Periksa apakah dokumentasi versi bahasa Indonesia dan Inggris tersedia dan konsisten.

Kinerja tim triase. Periksa jumlah laporan yang diterima dan diproses dalam sebulan terakhir. Periksa waktu respons rata-rata untuk laporan baru. Periksa waktu verifikasi rata-rata per severity. Periksa akurasi severity scoring dengan membandingkan skor peneliti dan skor tim. Periksa backlog laporan yang belum diproses.

Kinerja tim remediasi. Periksa jumlah kerentanan yang diperbaiki dalam sebulan terakhir. Periksa kepatuhan terhadap SLA perbaikan per severity. Periksa jumlah kerentanan yang melebihi SLA. Periksa efektivitas perbaikan dengan memverifikasi sampel perbaikan.

Kinerja keuangan. Periksa total reward yang dibayarkan dalam sebulan terakhir. Periksa realisasi anggaran terhadap rencana. Periksa proyeksi pengeluaran untuk sisa bulan. Periksa status pembayaran yang tertunda.

**EVALUASI TRIWULANAN**

Kepuasan peneliti. Analisis hasil survey kepuasan peneliti yang dilakukan setiap triwulan. Identifikasi area yang mendapatkan skor rendah dan rencanakan perbaikan. Periksa tren kepuasan peneliti dari waktu ke waktu. Identifikasi peneliti dengan kontribusi tinggi yang perlu diberikan penghargaan khusus.

Kualitas temuan. Analisis distribusi severity temuan dalam triwulan terakhir. Identifikasi jenis kerentanan yang paling sering ditemukan. Identifikasi area aplikasi yang paling banyak memiliki kerentanan. Evaluasi efektivitas program dalam menemukan kerentanan kritis. Bandingkan dengan data triwulan sebelumnya untuk mengidentifikasi tren.

Efektivitas biaya. Hitung biaya per kerentanan yang ditemukan. Bandingkan dengan biaya pengujian penetrasi tradisional. Hitung Return on Investment (ROI) program. Evaluasi apakah anggaran program mencukupi atau perlu disesuaikan.

Kepatuhan aturan. Periksa jumlah pelanggaran aturan yang terjadi dalam triwulan terakhir. Evaluasi efektivitas sanksi yang diberikan. Identifikasi pola pelanggaran yang perlu diantisipasi. Periksa apakah aturan program masih relevan atau perlu diperbarui.

**EVALUASI TAHUNAN**

Pencapaian tujuan program. Evaluasi apakah program mencapai tujuan yang telah ditetapkan di awal tahun. Identifikasi keberhasilan dan kegagalan program. Analisis faktor-faktor yang mempengaruhi pencapaian tujuan. Dokumentasikan pelajaran yang dipetik.

Perbandingan dengan industri. Bandingkan metrik program dengan data industri dari laporan HackerOne, Bugcrowd, atau sumber lainnya. Identifikasi area di mana program unggul atau tertinggal. Tetapkan target perbaikan untuk tahun berikutnya berdasarkan benchmarking.

Perencanaan tahun depan. Tentukan tujuan program untuk tahun berikutnya. Sesuaikan anggaran berdasarkan pengalaman tahun ini. Rencanakan perubahan aturan atau skema reward jika diperlukan. Rencanakan perluasan program (misalnya dari private ke public). Identifikasi kebutuhan sumber daya tambahan.

Pembaruan strategi keamanan. Evaluasi kontribusi program terhadap strategi keamanan organisasi secara keseluruhan. Identifikasi kerentanan sistemik yang perlu ditangani di tingkat arsitektur. Rencanakan integrasi dengan inisiatif keamanan lainnya. Tetapkan prioritas keamanan untuk tahun berikutnya berdasarkan temuan program.
**Additional detail on Appendix: Technical Setup Guide for Researchers**

Panduan ini membantu peneliti dalam menyiapkan lingkungan pengujian mereka untuk program Bug Bounty SHYNESv2. Konfigurasi yang tepat memastikan pengujian dapat dilakukan secara efektif dan hasil yang diperoleh akurat.

**KONFIGURASI BURP SUITE**

Unduh dan instal Burp Suite Professional atau Community Edition. Konfigurasi proxy Burp Suite pada port 8080 atau port lain yang tersedia. Impor sertifikat CA Burp Suite ke browser untuk memungkinkan inspeksi traffic HTTPS. Konfigurasi scope Burp Suite untuk hanya menyertakan domain staging.shynesv2.up.railway.app dan subdomainnya. Aktifkan logging request dan response untuk keperluan dokumentasi. Konfigurasi session handling rules untuk menangani autentikasi secara otomatis. Siapkan project file untuk menyimpan progress pengujian.

**KONFIGURASI OWASP ZAP**

Unduh dan instal OWASP ZAP versi terbaru. Impor konfigurasi awal yang disediakan oleh program. Konfigurasi context ZAP untuk aplikasi SHYNESv2 dengan menyertakan domain staging dan informasi autentikasi. Konfigurasi scope untuk membatasi pengujian pada target yang diizinkan. Siapkan automated scan policy dengan fokus pada kerentanan yang relevan untuk aplikasi Laravel. Konfigurasi reporting template untuk menghasilkan laporan dalam format yang sesuai.

**KONFIGURASI ALAT TAMBAHAN**

Instal alat berikut untuk mendukung pengujian: Sublist3r atau Amass untuk subdomain enumeration, ffuf atau gobuster untuk directory dan file fuzzing, Nuclei untuk template-based vulnerability scanning, curl atau httpie untuk manual request testing, jq untuk JSON parsing dan formatting, git untuk version control dan akses ke resource publik.

Siapkan wordlist yang relevan untuk pengujian aplikasi Laravel, termasuk direktori umum Laravel (/app, /storage, /bootstrap, /config, /database, /resources, /routes, /vendor), file konfigurasi (.env, .env.example, artisan, composer.json, composer.lock, package.json), endpoint API umum Laravel (/api/login, /api/register, /api/logout, /api/user, /api/products, /api/orders, /api/payments, /api/contracts), dan parameter umum Laravel (_token, _method, page, limit, sort, filter, search, id, q).

**KONFIGURASI BROWSER**

Gunakan browser Chromium atau Firefox dengan ekstensi keamanan yang relevan: Wappalyzer untuk identifikasi teknologi, EditThisCookie atau Cookie-Editor untuk melihat cookie, FoxyProxy untuk manajemen proxy yang mudah, User-Agent Switcher untuk mengubah identitas browser. Nonaktifkan caching browser untuk memastikan setiap request segar. Aktifkan developer tools untuk inspeksi network, console, dan elemen.

**AKSES KE LINGKUNGAN STAGING**

Akses lingkungan staging melalui URL https://staging.shynesv2.up.railway.app. Gunakan akun test yang disediakan: admin_staging dengan password shynes_staging_admin_2026 untuk akses admin, supplier_staging dengan password shynes_staging_supplier_2026 untuk akses supplier, buyer_staging dengan password shynes_staging_buyer_2026 untuk akses pembeli. Verifikasi akses dengan login menggunakan kredensial yang disediakan dan jelajahi fitur-fitur yang tersedia.

**DOKUMENTASI PENGUJIAN**

Buat direktori project untuk menyimpan dokumentasi pengujian. Simpan setiap request dan response yang relevan dalam file teks. Ambil screenshot untuk setiap temuan yang signifikan. Catat langkah-langkah pengujian secara detail untuk memudahkan reproduksi. Gunakan format penamaan file yang konsisten. Backup dokumentasi secara berkala untuk menghindari kehilangan data.

**ETIKA PENGUJIAN**

Ingatlah selalu untuk mematuhi aturan program. Jangan melakukan pengujian di luar scope yang ditentukan. Jangan mengekstrak data melebihi batas yang diizinkan. Jangan memodifikasi atau menghapus data. Jangan melakukan tindakan yang dapat menurunkan performa sistem. Laporkan setiap temuan melalui saluran yang telah ditentukan. Hormati privasi pengguna lain dan jaga kerahasiaan informasi yang diperoleh.
**Additional detail on Appendix: Contact Information and Emergency Procedures**

Informasi kontak dan prosedur darurat ini penting untuk diketahui oleh semua peneliti yang berpartisipasi dalam program Bug Bounty SHYNESv2. Saluran komunikasi yang tepat memastikan bahwa laporan dan pertanyaan ditangani dengan efisien.

**SALURAN KOMUNIKASI REGULER**

Platform Bug Bounty utama: Semua pelaporan dan komunikasi reguler dilakukan melalui platform Bug Bounty yang telah ditunjuk. Platform ini menyediakan sistem ticketing, sistem pesan, dan dashboard untuk melacak status laporan. Alamat email program: security@shynesv2.up.railway.app untuk pertanyaan umum yang tidak terkait dengan laporan spesifik. Jam operasional: Senin hingga Jumat, pukul 09.00 - 17.00 WIB. Respons di luar jam operasional akan diberikan pada hari kerja berikutnya.

**SALURAN KOMUNIKASI DARURAT**

Untuk kerentanan kritis yang memerlukan respons segera, gunakan saluran komunikasi darurat berikut: Nomor telepon darurat: [Nomor Telepon Darurat], Email darurat: emergency@shynesv2.up.railway.app, dan Saluran Signal/WhatsApp: [Nomor Signal/WhatsApp]. Saluran darurat hanya boleh digunakan untuk kerentanan yang memenuhi kriteria darurat: remote code execution yang sedang dieksploitasi secara aktif, kebocoran data pengguna yang sedang berlangsung, akses administratif tanpa otorisasi, atau kerentanan payment gateway yang menyebabkan kerugian finansial. Penyalahgunaan saluran darurat untuk laporan non-darurat akan dikenakan sanksi.

**PROSEDUR ESKALASI**

Jika peneliti tidak mendapatkan respons dalam waktu yang ditentukan, prosedur eskalasi berikut dapat diikuti: Eskalasi Level 1: Hubungi tim program melalui platform Bug Bounty atau email jika tidak ada respons dalam 24 jam. Eskalasi Level 2: Hubungi Program Manager melalui email jika tidak ada respons dalam 48 jam setelah eskalasi Level 1. Eskalasi Level 3: Hubungi manajemen melalui email resmi perusahaan jika tidak ada respons dalam 72 jam setelah eskalasi Level 2. Prosedur eskalasi hanya digunakan untuk masalah yang memerlukan perhatian manajemen, bukan untuk mempercepat verifikasi laporan biasa.

**PELAPORAN INSIDEN KEAMANAN**

Jika peneliti menemukan bukti bahwa kerentanan telah dieksploitasi oleh pihak ketiga, laporkan segera melalui saluran darurat. Jangan mencoba untuk menyelidiki sendiri eksploitasi yang sedang berlangsung. Berikan informasi sebanyak mungkin tentang indikator kompromi yang ditemukan. Tim incident response SHYNESv2 akan menangani situasi dan memberikan update kepada peneliti.

**HUBUNGI PERS**

Semua pertanyaan dari media atau pers tentang program Bug Bounty harus diarahkan ke tim komunikasi perusahaan. Peneliti tidak diizinkan untuk berbicara dengan media atas nama SHYNESv2 tanpa izin tertulis. Pelanggaran terhadap kebijakan ini dapat mengakibatkan diskualifikasi dari program dan tindakan hukum.

---

*Dokumen ini merupakan bagian dari Laporan UAS Keamanan Sistem Informasi yang disusun oleh [Nama Mahasiswa 4] - [NIM.4].*

*Seluruh informasi dalam laporan ini bersifat akademis dan ditujukan untuk tujuan pembelajaran. Implementasi program Bug Bounty pada aplikasi SHYNESv2 Fashion E-Commerce memerlukan penyesuaian lebih lanjut berdasarkan kebutuhan dan konteks organisasi yang sebenarnya.*
