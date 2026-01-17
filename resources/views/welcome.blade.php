<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Shyness</title>

    <!-- Font: Inter (Google Fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Animasi sederhana untuk fade-in */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased h-screen flex flex-col justify-between overflow-hidden relative">

    <!-- Dekorasi Background (Abstrak Monokrom) -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-gray-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-gray-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 translate-x-1/3 translate-y-1/3"></div>

    <!-- Empty Header for spacing -->
    <div></div>

    <!-- Main Content -->
    <div class="relative z-10 flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 text-center animate-fade-in-up">

        <!-- Logo Besar -->
        <div class="mb-8 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 inline-block">
            <img src="{{ asset('storage/images/shyness.png') }}"
                 alt="Logo Shyness"
                 class="h-24 w-auto object-contain"
                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000000&color=fff&size=128&font-size=0.5';">
        </div>

        <!-- Judul -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900 mb-4">
            Selamat Datang di <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-700 to-black">SHYNESS</span>
        </h1>

        <!-- Subjudul -->
        <p class="mt-2 max-w-md mx-auto text-lg sm:text-xl text-gray-500 md:mt-4 md:max-w-3xl">
            Platform manajemen konten modern, minimalis, dan elegan. Kelola data Anda dengan fokus dan kesederhanaan.
        </p>

        <div class="mt-10 flex justify-center gap-4">
            <a href="{{ route('login') }}"
               class="group relative inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-gray-900 rounded-full shadow-lg hover:bg-black hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                <span>Masuk ke Dashboard</span>
                <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>

                <!-- Efek Glow -->
                <div class="absolute inset-0 rounded-full ring-2 ring-white/20 group-hover:ring-white/40 transition-all"></div>
            </a>
        </div>

    </div>

    <!-- Footer -->
    <div class="relative z-10 py-6 text-center">
        <p class="text-sm text-gray-400">
            &copy; {{ date('Y') }} Shyness App. All rights reserved.
        </p>
    </div>

</body>
</html>
