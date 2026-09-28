<?php
require_once __DIR__ . '/../config.php';

$flashes = get_flash();
if (!empty($flashes)):
?>
<div class="container" style="margin-top: 20px;">
    <?php foreach ($flashes as $type => $messages): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="alert alert-<?php echo $type === 'error' ? 'error' : ($type === 'info' ? 'info' : 'success'); ?>">
                <i class="fas <?php echo $type === 'error' ? 'fa-exclamation-circle' : ($type === 'info' ? 'fa-info-circle' : 'fa-check-circle'); ?>"></i>
                <span><?php echo sanitize($msg); ?></span>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
