<main class="container">
    <div class="card">
        <h2>Contact</h2>

        <?php if (!empty($errors)): ?>
            <div style="color:#c0392b">
                <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?=htmlspecialchars($e, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8')?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/contact">
            <label>Nom</label>
            <input type="text" name="name" value="<?=isset($name) ? htmlspecialchars($name, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') : ''?>">

            <label>Email</label>
            <input type="email" name="email" value="<?=isset($email) ? htmlspecialchars($email, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') : ''?>">

            <label>Message</label>
            <textarea name="message" rows="6"><?=isset($message) ? htmlspecialchars($message, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') : ''?></textarea>

            <button type="submit">Envoyer</button>
        </form>
    </div>
</main>
