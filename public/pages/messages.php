<?php
$messages = \MessageStorage::read();
?>
<main class="container">
    <div class="card">
        <h2>Messages envoyés</h2>
        <?php if (empty($messages)): ?>
            <p class="muted">Aucun message pour le moment.</p>
        <?php else: ?>
            <div class="messages">
            <?php foreach (array_reverse($messages) as $m): ?>
                <div class="message">
                    <strong><?=htmlspecialchars($m['name'] ?? 'Anonyme', ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8')?></strong>
                    <div class="muted"><?=isset($m['timestamp']) ? date('Y-m-d H:i:s', $m['timestamp']) : ''?> — <?=htmlspecialchars($m['email'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8')?></div>
                    <p><?=nl2br(htmlspecialchars($m['message'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'))?></p>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
