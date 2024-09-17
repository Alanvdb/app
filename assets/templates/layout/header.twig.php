<body
    <?php if (isset($darkMode) && $darkMode) : ?>
        class="darkMode"
    <?php endif; ?>
>
    <header class="header">
        <p class="header-logo"><a href="<?= $uriGenerator->generateUri('home') ?>">MyBlog</a></p>
    </header>