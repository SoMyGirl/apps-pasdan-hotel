<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { zinc: { 900: '#18181b', 800: '#27272a', 50: '#fafafa' } },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                } 
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print { aside, header, .no-print { display: none !important; } }
        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d4d4d8; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #a1a1aa; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>

<body class="flex h-screen overflow-hidden bg-zinc-50" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarMinimized: localStorage.getItem('sidebarMinimized') === 'true',
          isDesktop: window.innerWidth >= 1024,
          toggleSidebar() {
              if (this.isDesktop) {
                  this.sidebarMinimized = !this.sidebarMinimized;
                  localStorage.setItem('sidebarMinimized', this.sidebarMinimized);
              } else {
                  this.sidebarOpen = false;
              }
          }
      }"
      @resize.window="isDesktop = window.innerWidth >= 1024">