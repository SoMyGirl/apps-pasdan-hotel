<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pasundan Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-sunda { font-family: 'Playfair Display', serif; }
        
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="relative min-h-screen flex items-center justify-center bg-cover bg-center py-12 lg:py-0" 
         style="background-image: url('https://images.unsplash.com/photo-1596401490237-77b3b3310023?q=80&w=2070&auto=format&fit=crop');">
        
        <div class="absolute inset-0 bg-gradient-to-br from-neutral-900/95 via-zinc-900/90 to-black/80"></div>

        <div class="relative z-10 w-full max-w-7xl px-6 flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-24">

            <div class="w-full lg:w-1/2 text-white space-y-6 lg:space-y-8 fade-in-up text-center lg:text-left">
                <div>
                    <h5 class="text-gray-400 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-2">Wilujeng Sumping di</h5>
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold leading-none tracking-tight font-sunda">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-200 to-gray-500">PASUNDAN</span> <br> 
                        <span class="text-white">HOTEL</span>
                    </h1>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start space-y-2 sm:space-y-0 sm:space-x-3 text-white">
                    <div class="flex space-x-1 text-gray-200">
                        <svg class="w-6 h-6 fill-current drop-shadow-lg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-6 h-6 fill-current drop-shadow-lg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-6 h-6 fill-current drop-shadow-lg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-6 h-6 fill-current drop-shadow-lg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-6 h-6 fill-current drop-shadow-lg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-400 ml-2 tracking-wide">The Heart of Parahyangan Luxury</span>
                </div>

                <p class="text-gray-300 text-base sm:text-lg leading-relaxed max-w-lg mx-auto lg:mx-0 border-l-0 lg:border-l-4 border-white lg:pl-6 italic opacity-90">
                    "Rasakan kesejukan alam Parahyangan berpadu dengan kemewahan modern. Tempat di mana tradisi 'Someah' menyambut istirahat Anda."
                </p>
            </div>

            <div class="w-full lg:w-[480px] fade-in-up" style="animation-delay: 0.2s;">
                <div class="relative bg-black/40 backdrop-blur-xl border border-white/10 p-6 sm:p-8 md:p-10 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.8)]">
                    
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10 animate-pulse"></div>

                    <div class="relative">
                        <h2 class="text-3xl font-bold text-white mb-2 font-sunda text-center lg:text-left">Login</h2>
                        <p class="text-gray-400 text-sm mb-6 text-center lg:text-left">Access your reservation at Pasundan Resort.</p>

                        <?php if(isset($_GET['msg'])): ?>
                            <div class="mb-6 p-4 rounded-xl bg-red-900/20 border border-red-500/30 text-red-200 text-sm flex items-center gap-3 animate-pulse">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>Login Gagal! Cek username/password.</span>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?aksi=login_proses" method="POST" class="space-y-6">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1 ml-1">Username</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                            </svg>
                                        </div>
                                        <input type="text" name="username" required
                                            class="w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:outline-none focus:border-white focus:bg-white/10 focus:ring-1 focus:ring-white transition-all duration-300"
                                            placeholder="Masukkan username">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1 ml-1">Password</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <input type="password" name="password" required
                                            class="w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:outline-none focus:border-white focus:bg-white/10 focus:ring-1 focus:ring-white transition-all duration-300"
                                            placeholder="Masukkan password">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-10 h-6 bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-black after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-gray-400 after:border-gray-500 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-white peer-checked:after:bg-black"></div>
                                    </div>
                                    <span class="ml-3 text-gray-400 group-hover:text-white transition">Ingat Saya</span>
                                </label>
                                <a href="#" class="text-gray-300 hover:text-white font-medium transition underline decoration-gray-500 hover:decoration-white">Lupa Password?</a>
                            </div>

                            <button type="submit" 
                                class="w-full py-4 bg-white rounded-2xl text-black font-bold text-lg shadow-lg shadow-white/10 hover:shadow-white/20 hover:bg-gray-200 hover:-translate-y-1 transition-all duration-300 transform">
                                Masuk Sekarang
                            </button>
                        </form>

                         <div class="relative my-6">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-white/10"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 text-gray-500">Atau masuk dengan</span>
                                </div>
                            </div>

                            <div class="flex justify-center space-x-4">
                                <a href="#" class="p-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:border-white/30 transition-all group">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="#" class="p-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:border-white/30 transition-all group">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.813 1.053 6.427 2.56l2.32-2.32c-2.32-2.16-5.413-3.467-8.747-3.467-7.227 0-13.067 5.867-13.067 13.093s5.84 13.093 13.067 13.093c3.787 0 6.64-1.227 8.56-3.147 2.053-2.027 2.613-5.067 2.613-6.933 0-.48-.053-.96-.133-1.413h-10.773z" /></svg>
                                </a>
                            </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>