<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Management System</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: sans-serif; }
    </style>
</head>
<body class="bg-zinc-50 h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-xl border border-zinc-200 shadow-lg w-full max-w-sm">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-zinc-900 text-white mb-4 shadow-md shadow-zinc-300">
                <i data-lucide="building-2" class="w-7 h-7"></i>
            </div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Hotel System</h1>
            <p class="text-zinc-500 text-sm mt-1">Silakan login untuk melanjutkan.</p>
        </div>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="user" class="h-4 w-4 text-zinc-400"></i>
                    </div>
                    <input type="text" name="username" required 
                        class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all text-sm placeholder-zinc-400" 
                        placeholder="Masukkan username">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-4 w-4 text-zinc-400"></i>
                    </div>
                    <input type="password" name="password" required 
                        class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all text-sm placeholder-zinc-400" 
                        placeholder="Masukkan password">
                </div>
            </div>

            <button type="submit" class="w-full bg-zinc-900 text-white py-2.5 rounded-lg font-bold text-sm hover:bg-zinc-800 transition-all shadow-md hover:shadow-lg flex justify-center items-center gap-2">
                Masuk Sistem <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>
        
        <p class="text-center text-[10px] text-zinc-400 mt-8">
            &copy; <?= date('Y') ?> Hotel Pasundan. All rights reserved.
        </p>
    </div>

    <script>
        lucide.createIcons();

        // Cek apakah ada pesan error dari PHP Session?
        <?php if (isset($_SESSION['flash_type'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['flash_type'] ?>', // success atau error
                title: '<?= $_SESSION['flash_type'] == 'success' ? 'Berhasil!' : 'Gagal!' ?>',
                text: '<?= $_SESSION['flash_message'] ?>',
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
            // Hapus session flash agar tidak muncul lagi saat refresh
            <?php unset($_SESSION['flash_type'], $_SESSION['flash_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>