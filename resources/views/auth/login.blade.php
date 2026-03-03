<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - SHYNESS</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    
    <!-- Icon Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        primary: '#111111',
                    }
                }
            }
        }
    </script>
    <style>
        .border-thin { border: 1px solid #E5E5E5; }
        .input-underlined {
            border: none;
            border-bottom: 1px solid #E5E5E5;
            border-radius: 0;
            background: transparent;
            padding-left: 0;
            padding-right: 0;
            transition: border-color 0.3s ease;
        }
        .input-underlined:focus {
            outline: none;
            box-shadow: none;
            border-bottom-color: #111;
        }
    </style>
</head>
<body class="bg-white text-primary antialiased min-h-screen selection:bg-black selection:text-white flex flex-col md:flex-row">

    <!-- Navbar Minimalis -->
    <nav class="absolute top-0 left-0 w-full py-6 px-8 flex justify-between items-center z-50">
        <a href="{{ route('welcome') }}" class="text-xl font-serif font-semibold tracking-widest uppercase text-primary hover:opacity-70 transition-opacity">Shyness</a>
    </nav>

    <!-- Image Section (Hide on mobile) -->
    <div class="hidden md:block md:w-1/2 min-h-screen bg-[#f5f5f5] relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10 z-10"></div>
        <img src="{{ asset('images/campaign/details.png') }}" class="absolute inset-0 w-full h-full object-cover scale-105" alt="Editorial Fashion">
        <div class="absolute bottom-12 left-12 z-20">
            <h2 class="font-serif italic text-4xl text-white drop-shadow-md">Curated Essence.</h2>
            <p class="text-xs tracking-widest uppercase text-white/80 mt-2">Welcome Back</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="w-full md:w-1/2 min-h-screen flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white relative">
        <div class="w-full max-w-md mt-16 md:mt-0">
            <div class="mb-12">
                <h1 class="text-4xl font-serif font-medium text-primary mb-3">Log In.</h1>
                <p class="text-sm font-light tracking-wide text-gray-500">Access your Shyness account to manage your curation.</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="relative group">
                    <label for="email" class="block text-[10px] uppercase tracking-widest font-medium text-gray-400 mb-1 transition-colors group-focus-within:text-black">Email Address</label>
                    <input type="email" name="email" id="email" required 
                        class="block w-full py-3 input-underlined text-sm"
                        placeholder="Enter your email">
                    @error('email')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative group">
                    <label for="password" class="block text-[10px] uppercase tracking-widest font-medium text-gray-400 mb-1 transition-colors group-focus-within:text-black">Password</label>
                    <input type="password" name="password" id="password" required 
                        class="block w-full py-3 input-underlined text-sm"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded-sm">
                        <label for="remember" class="ml-2 block text-xs tracking-wider text-gray-500">
                            Remember me
                        </label>
                    </div>
                    <div class="text-xs">
                        <a href="#" class="font-medium tracking-wider text-primary hover:text-gray-500 transition-colors">Forgot your password?</a>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-black text-xs tracking-widest uppercase font-medium text-white bg-primary hover:bg-white hover:text-primary transition-all duration-300">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-thin text-center">
                <p class="text-xs tracking-wider text-gray-500">
                    Don't have an account? <br/>
                    <a href="{{ route('register') }}" class="font-medium text-primary hover:text-gray-500 transition-colors uppercase mt-3 inline-block border-b border-primary pb-1">Create Account</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
