
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['alert'])):

    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);

    $allowedTypes = ['success', 'error', 'warning', 'info'];
    $type = in_array($alert['type'] ?? '', $allowedTypes, true)
        ? $alert['type']
        : 'info';

    $title = $alert['title'] ?? '';
    $message = $alert['message'] ?? '';

?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    AppAlert.<?= $type ?>(
        <?= json_encode($title, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        <?= json_encode($message, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
    );
});
</script>

<?php endif; ?>
