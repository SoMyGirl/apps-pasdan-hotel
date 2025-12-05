<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { zinc: { 900: '#18181b', 50: '#fafafa' } } } }
        }
    </script>
    <style>
        body { font-family: sans-serif; }
        @media print { aside, header, .no-print { display: none !important; } }
        /* Scrollbar Kustom agar lebih tipis dan modern */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #d4d4d8; /* Zinc-300 */
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #a1a1aa; /* Zinc-400 */
        }

        /* Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #d4d4d8 transparent;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-zinc-50">