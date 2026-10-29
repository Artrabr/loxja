<?php

function renderAdminLayoutStart(string $theme, string $activePage): void
{
    $navigation = [
        'dashboard' => ['index.php', 'Painel'],
        'add-product' => ['add-product.php', 'Adicionar produto'],
        'edit-products' => ['edit-product.php', 'Editar produtos'],
    ];
    ?>
    <header class="admin-header">
        <a class="admin-header-title" href="index.php">Painel administrativo</a>
        <?php renderThemeToggle($theme); ?>
    </header>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <p class="admin-sidebar-label">Menu</p>
            <nav aria-label="Navegação administrativa">
                <?php foreach ($navigation as $key => [$href, $label]): ?>
                    <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                       <?= $activePage === $key ? 'aria-current="page"' : '' ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <a class="admin-store-link" href="../index.php">Voltar à loja</a>
        </aside>
        <div class="admin-main-column">
            <main class="admin-content">
    <?php
}

function renderAdminLayoutEnd(): void
{
    ?>
            </main>
            <footer class="admin-footer">
                <small>&copy; 2026 Loxja Café.</small>
            </footer>
        </div>
    </div>
    <?php
}
