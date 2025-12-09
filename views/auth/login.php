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
        .font-serif-modern { font-family: 'Playfair Display', serif; }
        
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex items-center justify-center min-h-screen selection:bg-slate-900 selection:text-white relative overflow-hidden">

    <div class="fixed inset-0 bg-gradient-to-br from-white via-slate-50 to-blue-50/40 -z-10"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-indigo-100/30 rounded-full blur-[100px] -z-10"></div>

    <div class="w-full max-w-[420px] px-6 fade-in relative z-10">
        
        <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-black/5">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-900 text-white shadow-lg shadow-slate-200 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 font-serif-modern tracking-tight">Pasundan Hotel</h2>
                <p class="text-slate-500 text-sm mt-2">Selamat datang, silakan login.</p>
            </div>

            <?php if(isset($_SESSION['flash_type']) && $_SESSION['flash_type'] == 'error'): ?>
                <div class="mb-6 p-3 rounded-lg bg-red-50 border border-red-100 text-red-600 text-xs flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= $_SESSION['flash_message'] ?></span>
                </div>
                <?php unset($_SESSION['flash_type'], $_SESSION['flash_message']); ?>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Username</label>
                    <div class="relative group">
                        <input type="text" name="username" required 
                            class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3.5 outline-none transition-all duration-300 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 placeholder:text-slate-400 shadow-sm"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Password</label>
                    <div class="relative group">
                        <input type="password" name="password" required 
                            class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3.5 outline-none transition-all duration-300 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 placeholder:text-slate-400 shadow-sm"
                            placeholder="Masukkan password">
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white font-bold rounded-xl py-3.5 text-sm hover:bg-black active:scale-[0.98] transition-all duration-200 shadow-lg shadow-slate-900/20">
                    Masuk Sekarang
                </button>

            </form>

            <div class="mt-8 text-center">
                <p class="text-xs text-slate-400">
                    &copy; <?= date('Y') ?> Hotel System. All rights reserved.
                </p>
            </div>

        </div>
    </div>

</body>
</html>
