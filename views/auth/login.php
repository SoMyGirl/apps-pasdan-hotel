<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-zinc-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden">
        <div class="p-8 text-center border-b border-zinc-100 bg-zinc-50/50">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-zinc-900 text-white mb-4">
                <i data-lucide="building-2" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-bold text-zinc-900">Selamat Datang</h2>
            <p class="text-sm text-zinc-500 mt-1">Masuk untuk mengakses sistem manajemen hotel.</p>
        </div>

        <div class="p-8">
            <?php if(isset($_GET['msg'])): ?>
                <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-600 text-sm flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> Login Gagal! Cek username/password.
                </div>
            <?php endif; ?>

            <form action="index.php?aksi=login_proses" method="POST" class="space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-zinc-700">Username</label>
                    <input type="text" name="username" required 
                        class="flex h-10 w-full rounded-md border border-zinc-300 bg-transparent px-3 py-2 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-zinc-700">Password</label>
                    <input type="password" name="password" required 
                        class="flex h-10 w-full rounded-md border border-zinc-300 bg-transparent px-3 py-2 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                </div>

                <button type="submit" class="w-full h-10 bg-zinc-900 text-white hover:bg-zinc-800 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
        
        <div class="p-4 border-t border-zinc-100 text-center text-xs text-zinc-400">
            &copy; 2024 Project Hotel Sekolah
        </div>
    </div>

    <script> lucide.createIcons(); </script>
</body>
</html>