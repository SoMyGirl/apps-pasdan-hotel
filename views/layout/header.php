<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Hotel</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        zinc: {
                            950: '#09090b', // Warna teks utama
                            900: '#18181b', // Warna tombol primary
                            500: '#71717a', // Warna teks secondary
                            200: '#e4e4e7', // Warna border
                            100: '#f4f4f5', // Warna hover
                            50: '#fafafa',  // Warna background
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #09090b; }
        
        /* Logic khusus saat nge-Print: Sembunyikan Sidebar, Header, dan elemen 'no-print' */
        @media print {
            aside, header, .no-print, form { display: none !important; }
            body, main { background-color: white !important; overflow: visible !important; height: auto !important; }
            .print-full-width { width: 100% !important; grid-column: span 12 !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-white">