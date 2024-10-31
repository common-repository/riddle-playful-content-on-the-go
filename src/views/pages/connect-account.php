<?php self::view('components/navbar.php', ['title' => 'Connect account']); ?>

<div class="riddle-page">
    <h2 class="riddle-page-title mb-5">Connect your Riddle account to get started</h2>

    <div class="row">
        <div class="col-sm-12 col-md-5">
            <form method="POST">
                <small>API Token</small>
                <input type="text" value="<?php echo $data['bearer']; ?>" name="bearer" class="riddle-form-field form-control riddle-input" placeholder="Paste your API token here" required>
                <br>
                <small>API Key</small>
                <input type="text" value="<?php echo $data['apikey']; ?>" name="apikey" class="form-control riddle-input riddle-form-field" placeholder="Paste your API key here" required>
                <input type="submit" value="CONNECT" class="btn btn-riddle btn-cyan mt-4 mb-4">
            </form>
            <p class="text-muted">
                Need help? <br>
                You can find your API details in the Account section of Riddle. <br>
                Check out our <a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-help') ?>">step-by-step guide here.</a>
            </p>
        </div>
    </div>
</div>