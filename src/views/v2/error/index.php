<?php self::view('components/navbar.php', ['title' => 'Connect account']); ?>

<div class="riddle-page">
    <h2 class="riddle-page-title mb-5">We could not process your request.</h2>

    <div class="row">
        <div class="col-sm-12 col-md-5">
            <p><?php echo $data['error']; ?></p>
            
            <p class="text-muted mb-0 mt-5"><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&disconnect=1') ?>">Reset Riddle connection</a></p>
            <p class="text-muted mt-0"><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-help') ?>">Back to the main view</a></p>
        </div>
    </div>
</div>