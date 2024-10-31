<?php self::view('components/navbar.php', ['title' => 'Connect account']); ?>

<div class="riddle-page">
    <h2 class="riddle-page-title mb-5">Connect your Riddle account to get started</h2>

    <div class="row">
        <div class="col-sm-12 col-md-5">
            <?php if ($data['hasOldToken']): ?>
                <div class="alert alert-warning">
                    <p><strong>Note:</strong> We deprecated our old Creator 1.0 tokens for connecting Wordpress with Riddle. Please use our new Creator 2.0 authentication below to continue using this plug-in.</p>
                </div>
            <?php endif; ?>

            <p>Click the button below to integrate with your own Riddle account.</p>
            <a href="<?php echo $data['authUrl']; ?>" class="btn btn-riddle btn-cyan mt-4 mb-4">Authenticate</a>

            <p class="text-muted">
                Need help? <br>
                Check out our <a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-help') ?>">step-by-step guide here.</a>
            </p>
        </div>
    </div>
</div>