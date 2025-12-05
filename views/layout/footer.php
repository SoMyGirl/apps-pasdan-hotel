</main> </div> <script>
    // Jalankan Icon
    lucide.createIcons();

    // SweetAlert Notifikasi
    <?php if (isset($_SESSION['flash_type'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['flash_type'] ?>',
            text: '<?= $_SESSION['flash_message'] ?>',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        <?php unset($_SESSION['flash_type'], $_SESSION['flash_message']); ?>
    <?php endif; ?>
</script>
</body>
</html>