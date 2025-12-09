</main> </div> <script>
    // Jalankan Icon
    lucide.createIcons();

    // SweetAlert Notifikasi (Toast Center Dark Theme)
    <?php if (isset($_SESSION['flash_type'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['flash_type'] ?>',
            title: '<?= ucfirst($_SESSION['flash_type']) ?>',
            text: '<?= $_SESSION['flash_message'] ?>',
            toast: true,
            position: 'top', // POSISI TENGAH
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#18181b', // Zinc-900 (Hitam)
            color: '#fff',         // Teks Putih
            iconColor: '<?= $_SESSION['flash_type'] == "success" ? "#10b981" : "#f43f5e" ?>', // Hijau/Merah custom
            customClass: {
                popup: 'rounded-xl border border-zinc-700 shadow-2xl'
            }
        });
        <?php unset($_SESSION['flash_type'], $_SESSION['flash_message']); ?>
    <?php endif; ?>
</script>
</body>
</html>