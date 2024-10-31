<?php self::view('components/navbar.php', ['title' => 'My Riddles']); ?>


<div class="riddle-page">
    <div class="row mb-4">
        <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
            <?php self::view('v2/riddle/_workspaceSelector.php', $data); ?>
        </div>
    </div>

    <?php if(!empty($data['riddles'])): ?>

        <?php if ($data['showV2Riddles']): ?>
            <div class="row">
                <div class="col-sm-12 pl-0">
                    <p class="mt-4 mb-0">Select one of your Riddles to embed in to Wordpress.</p>
                </div>
            </div>
            <?php self::view('pages/my-riddles_shortcode-modal.php', $data); ?>

            <?php foreach ($data['riddles'] as $riddle): ?>
                <?php self::view('v2/riddle/_riddleItem.php', $riddle); ?>
            <?php endforeach; ?>

        <?php else: // Show old Riddles: Use old Views as well! ?>
            <div class="row">
                <div class="col-sm-12 pl-0">
                    <p class="mt-4 mb-0">Select one of your Riddles to embed in to Wordpress. You can also create leaderboards here.</p>
                </div>
            </div>

            <?php self::view('pages/my-riddles_shortcode-modal.php', $data); ?>
            <?php self::view('pages/my-riddles_riddle-cards.php', $data); ?>
        <?php endif; ?>

    <?php else: ?>
            <p>No riddles found for the given workspace.</p>
    <?php endif; ?>
</div>