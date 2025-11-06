<?php
require __DIR__ . '/../src/MessageStorage.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple router
if ($uri === '/' || $uri === '') {
    include __DIR__ . '/templates/header.php';
    include __DIR__ . '/pages/home.php';
    include __DIR__ . '/templates/footer.php';
    exit;
}

if ($uri === '/about') {
    include __DIR__ . '/templates/header.php';
    include __DIR__ . '/pages/about.php';
    include __DIR__ . '/templates/footer.php';
    exit;
}

if ($uri === '/contact') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Basic validation and sanitization
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';

        $errors = [];
        if ($name === '') $errors[] = 'Le nom est requis.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Un email valide est requis.';
        if ($message === '') $errors[] = 'Le message est requis.';

        if (empty($errors)) {
            \MessageStorage::save([
                'name' => $name,
                'email' => $email,
                'message' => $message,
            ]);
            // Redirect to avoid re-post on refresh
            header('Location: /?sent=1');
            exit;
        }

        // If errors, show the form with errors
        include __DIR__ . '/templates/header.php';
        include __DIR__ . '/pages/contact.php';
        include __DIR__ . '/templates/footer.php';
        exit;
    }

    // GET: show form
    include __DIR__ . '/templates/header.php';
    include __DIR__ . '/pages/contact.php';
    include __DIR__ . '/templates/footer.php';
    exit;
}

if ($uri === '/messages') {
    include __DIR__ . '/templates/header.php';
    include __DIR__ . '/pages/messages.php';
    include __DIR__ . '/templates/footer.php';
    exit;
}

// 404
http_response_code(404);
include __DIR__ . '/templates/header.php';
echo '<main class="container"><h2>404 — Page non trouvée</h2><p>La page demandée est introuvable.</p></main>';
include __DIR__ . '/templates/footer.php';
