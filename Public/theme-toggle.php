<?php

function currentTheme(): string
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_tema'])) {
        $currentTheme = ($_COOKIE['tema'] ?? 'light') === 'dark' ? 'dark' : 'light';
        $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';

        setcookie('tema', $newTheme, [
            'expires' => time() + 60 * 60 * 24 * 365,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        $requestUri = str_replace(["\r", "\n"], '', (string) ($_SERVER['REQUEST_URI'] ?? '/'));
        header('Location: ' . $requestUri);
        exit;
    }

    return ($_COOKIE['tema'] ?? 'light') === 'dark' ? 'dark' : 'light';
}

function renderThemeToggle(string $theme): void
{
    $action = htmlspecialchars((string) ($_SERVER['REQUEST_URI'] ?? ''), ENT_QUOTES, 'UTF-8');
    $isDark = $theme === 'dark';
    ?>
    <form method="post" action="<?= $action ?>" class="theme-toggle-form">
        <button type="submit" name="toggle_tema" class="theme-toggle"
                aria-label="<?= $isDark ? 'Ativar modo claro' : 'Ativar modo escuro' ?>"
                title="<?= $isDark ? 'Modo claro' : 'Modo escuro' ?>">
            <?= $isDark ? '🌙' : '☀️' ?>
        </button>
    </form>
    <?php
}
