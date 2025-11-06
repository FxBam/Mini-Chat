<?php
session_start();
require __DIR__ . '/../src/MessageStorage.php';

// Simple config: read admin password from environment variable ADMIN_PASS or default 'changeme'
$ADMIN_PASS = getenv('ADMIN_PASS') ?: 'changeme';

// CSRF token helper
function ensure_csrf()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
}

function check_csrf($t)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $t);
}

ensure_csrf();

$action = $_GET['action'] ?? '';

// Logout
if ($action === 'logout') {
    unset($_SESSION['admin']);
    header('Location: /admin.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $password = $_POST['password'] ?? '';
    if (hash_equals($ADMIN_PASS, $password)) {
        $_SESSION['admin'] = true;
        header('Location: /admin.php');
        exit;
    } else {
        $login_error = 'Mot de passe incorrect.';
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && !empty($_SESSION['admin'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!check_csrf($token)) {
        $delete_error = 'Token CSRF invalide.';
    } else {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $messages = \MessageStorage::read();
        if ($id !== null && isset($messages[$id])) {
            array_splice($messages, $id, 1);
            \MessageStorage::writeAll($messages);
            $delete_success = 'Message supprimé.';
        } else {
            $delete_error = 'Message introuvable.';
        }
    }
}

include __DIR__ . '/templates/header.php';

if (empty($_SESSION['admin'])):
    // show login form
    ?>
    <main class="container">
        <div class="card">
            <h2>Administration — Connexion</h2>
            <?php if (!empty($login_error)): ?>
                <div style="color:#c0392b"><?=htmlspecialchars($login_error)?></div>
            <?php endif; ?>
            <form method="post" action="/admin.php">
                <label>Mot de passe</label>
                <input type="password" name="password">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                <div style="margin-top:.6rem"><button type="submit" name="login">Se connecter</button></div>
            </form>
            <p class="muted">Le mot de passe administrateur peut être défini via la variable d'environnement <code>ADMIN_PASS</code>.</p>
        </div>
    </main>
    <?php
else:
    $messages = \MessageStorage::read();
    ?>
    <main class="container">
        <div class="card">
            <h2>Administration — Messages</h2>
            <?php if (!empty($delete_error)): ?>
                <div style="color:#c0392b"><?=htmlspecialchars($delete_error)?></div>
            <?php endif; ?>
            <?php if (!empty($delete_success)): ?>
                <div style="color:green"><?=htmlspecialchars($delete_success)?></div>
            <?php endif; ?>

            <?php if (empty($messages)): ?>
                <p class="muted">Aucun message.</p>
            <?php else: ?>
                <table style="width:100%; border-collapse:collapse">
                    <thead>
                        <tr><th style="text-align:left">#</th><th>Nom</th><th>Email</th><th>Message</th><th>Horodatage</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($messages as $i => $m): ?>
                        <tr style="border-top:1px solid #eee">
                            <td><?= $i ?></td>
                            <td><?=htmlspecialchars($m['name'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8')?></td>
                            <td><?=htmlspecialchars($m['email'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8')?></td>
                            <td><?=nl2br(htmlspecialchars($m['message'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'))?></td>
                            <td><?=isset($m['timestamp']) ? date('Y-m-d H:i:s', $m['timestamp']) : ''?></td>
                            <td>
                                <form method="post" action="/admin.php" onsubmit="return confirm('Supprimer ce message ?');">
                                    <input type="hidden" name="id" value="<?= $i ?>">
                                    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                                    <button type="submit" name="delete">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <p style="margin-top:1rem"><a href="/admin.php?action=logout">Se déconnecter</a></p>
        </div>
    </main>
    <?php
endif;

include __DIR__ . '/templates/footer.php';
