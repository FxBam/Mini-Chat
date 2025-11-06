<main class="container">
    <div class="card">
        <h2>Bienvenue sur Mini Chat</h2>
        <p>Ceci est un petit site d'exemple en PHP. Utilisez le formulaire de contact pour envoyer un message.</p>

        <?php if (isset($_GET['sent']) && $_GET['sent'] == '1'): ?>
            <p style="color:green">Merci — votre message a été envoyé.</p>
        <?php endif; ?>

        <p class="muted">Voir les messages envoyés : <a href="/messages">/messages</a></p>
    </div>
</main>
