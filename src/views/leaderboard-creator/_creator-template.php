<?php self::view('components/navbar.php', ['breadcrumbs' => [
    ['text' => 'My Riddles'],
    ['text' => $data['riddle']['title'] ?? 'Riddle ' . $data['type']->getValue('id')],
    ['text' => 'Add leaderboard'],
]]); ?>
<?php self::view('components/shortcode-modal.php', ['title' => 'Leaderboard']); ?>
<?php self::view('leaderboard-creator/pages/_creator-tutorial-modal.php', ['title' => 'Leaderboard']); ?>

<div class="container-fluid" id="riddle-leaderboard-creator-container">
    <div class="row">
        <div class="col-sm-5 riddle-leaderboard-navbar">
            <div class="riddle-leaderboard-navbar-block">
                <div class="row pl-3 pt-5 mb-5">
                    <div class="col-sm-12">
                        <h2 class="riddle-page-title mb-4">Add a leaderboard</h2>
                        <p class="text-muted mb-2">Turn any quiz into a competitive event. You can display the top scores - and show each user how they compare.</p> 
                        <p class="text-muted mb-2">Highly flexible, you can set how many scores to display, as well as all text messages.</p>
                        <p class="text-muted mb-4">Leaderboards are great for engagement and boosting lead generation - more quiz takers will sign up to your quiz lead form to participate.</p>

                        <p class="text-muted mt-3">Need help? Check out our <a href="#" id="riddle-leaderboard-show-tutorial-modal">step-by-step guide here.</a></p>
                    </div>
                </div>
            </div>

            <?php if ('create' !== $data['creatorPage']): //navbar gets available once the leaderboard was created ?>
                <?php self::view('leaderboard-creator/_creator-navbar.php', $data); ?>
            <?php endif; ?>
            
            <div class="col-sm-12">
                <?php self::view('leaderboard-creator/pages/creator-' . $data['creatorPage'] . '.php', $data); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <?php if ('create' !== $data['creatorPage']): ?>
            <div class="row mb-4 mt-2">
                <div class="col-sm-12" style="text-align: right;">
                    <button type="button" class="btn btn-riddle btn-cyan btn-riddle-clipboard" data-shortcode="rid-custom-page id" data-riddle-id="<?php echo $data["type"]->getId(); ?>" data-toggle="modal" data-target="#shortcodeModal">
                        Get shortcode
                        <img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-code.png" alt="Wordpress Publish Code Icon">
                    </button>
                </div>
            </div>
            <?php endif; ?>
            <div id="crp-preview">
                <p class="riddle-page-title">Loading leaderboard preview...</p>
                <p class="text-muted mt-4">This preview is not loading? Your blog does probably block script execution inside <strong>wp-contents/</strong>.</p>
            </div>
        </div>
    </div>
</div>

<?php if(isset($data['previewUrl'])): ?>
<script>
    var previewUrl = '<?php echo $data['previewUrl'] ?? ''; ?>';
    var renderPreviewOnStart = previewUrl != '';
    var options = JSON.parse('<?php echo htmlspecialchars(json_encode($data['type']->getAllValues()), ENT_QUOTES, 'UTF-8'); ?>'.replace( /&quot;/g, '"' ));
</script>
<?php endif;