</div> </main>
<script>
    lucide.createIcons();
    // Flash Message Logic
    <?php if (isset($_SESSION['flash_type'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['flash_type'] ?>',
            title: '<?= $_SESSION['flash_title'] ?? "Info" ?>',
            text: '<?= $_SESSION['flash_message'] ?>',
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
        });
        <?php unset($_SESSION['flash_type'], $_SESSION['flash_message']); ?>
    <?php endif; ?>
</script>
</body>
</html>