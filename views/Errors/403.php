<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Hotel System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { zinc: { 900: '#18181b', 50: '#fafafa' } } } } }
    </script>
</head>
<body class="bg-zinc-50 h-screen w-full flex items-center justify-center p-4">

    <div class="max-w-md w-full text-center">
        <div class="mb-6 relative inline-block">
            <div class="absolute inset-0 bg-rose-200 rounded-full blur-xl opacity-40"></div>
            <div class="relative bg-white p-4 rounded-2xl border border-zinc-200 shadow-sm">
                <i data-lucide="shield-alert" class="w-12 h-12 text-rose-600"></i>
            </div>
        </div>

        <h1 class="text-2xl font-black text-zinc-900 tracking-tight mb-2">Akses Ditolak</h1>
        <p class="text-zinc-500 mb-8 leading-relaxed">
            Anda tidak memiliki izin (Permission) untuk mengakses halaman ini. Halaman ini terbatas hanya untuk Administrator.
        </p>

        <div class="bg-white border border-zinc-200 rounded-xl p-4 mb-8 text-left flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-600">
                <?= substr($_SESSION['nama'] ?? 'U', 0, 1) ?>
            </div>
            <div class="flex-1">
                <p class="text-xs text-zinc-400 font-bold uppercase">Logged in as</p>
                <p class="text-sm font-bold text-zinc-900"><?= $_SESSION['nama'] ?? 'Guest' ?></p>
            </div>
            <div class="px-2 py-1 bg-zinc-100 rounded text-xs font-bold text-zinc-500 uppercase border border-zinc-200">
                <?= $_SESSION['role'] ?? 'Unknown' ?>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <a href="index.php?modul=Dashboard&aksi=index" 
               class="w-full py-3 px-4 bg-zinc-900 text-white rounded-xl font-bold hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-200 flex items-center justify-center gap-2">
                Kembali ke Dashboard
            </a>
            
            <a href="index.php?modul=Auth&aksi=logout" class="text-sm text-zinc-400 hover:text-rose-600 mt-2 transition-colors">
                Login dengan akun berbeda?
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>