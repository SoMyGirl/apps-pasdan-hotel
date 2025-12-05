<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found - Hotel System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { zinc: { 900: '#18181b', 50: '#fafafa' } } } } }
    </script>
</head>
<body class="bg-zinc-50 h-screen w-full flex items-center justify-center p-4">

    <div class="max-w-md w-full text-center">
        <div class="mb-6 relative inline-block">
            <div class="absolute inset-0 bg-zinc-200 rounded-full blur-xl opacity-50"></div>
            <div class="relative bg-white p-4 rounded-2xl border border-zinc-200 shadow-sm">
                <i data-lucide="file-question" class="w-12 h-12 text-zinc-900"></i>
            </div>
        </div>

        <h1 class="text-8xl font-black text-zinc-900 tracking-tighter mb-2">404</h1>
        <h2 class="text-xl font-bold text-zinc-800 mb-2">Halaman Tidak Ditemukan</h2>
        <p class="text-zinc-500 mb-8 leading-relaxed">
            Maaf, halaman yang Anda cari tidak tersedia, telah dipindahkan, atau nama jalurnya salah.
        </p>

        <div class="flex flex-col gap-3">
            <a href="index.php?modul=Dashboard&aksi=index" 
               class="w-full py-3 px-4 bg-zinc-900 text-white rounded-xl font-bold hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-200 flex items-center justify-center gap-2 group">
                <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Dashboard
            </a>
            
            <button onclick="history.back()" class="w-full py-3 px-4 bg-white text-zinc-700 border border-zinc-200 rounded-xl font-bold hover:bg-zinc-50 transition-all">
                Halaman Sebelumnya
            </button>
        </div>

        <div class="mt-12 text-xs text-zinc-400">
            &copy; Hotel Management System
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>