<?php self::view('components/navbar.php', ['title' => 'My Riddles']); ?>

<div class="riddle-page">
    <h2 class="riddle-page-title mb-4">Riddle account connected! <small class="text-muted"><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&disconnect=1') ?>">Disconnect</a></small> </h2>

    <?php if (!empty($data['teams'])): ?>
        <?php self::view('pages/my-riddles_team-selector.php', $data); ?>
    <?php endif; ?>
    
    <?php if(!empty($data['riddles'])): ?>
        <p class="mt-4 mb-0">Select one of your Riddles to embed in to Wordpress. You can also create leaderboards here.</p>
        <?php self::view('pages/my-riddles_shortcode-modal.php', $data); ?>
        <?php self::view('pages/my-riddles_riddle-cards.php', $data); ?>
    <?php else: ?>
    <?php endif; ?>
</div>