<div class="riddle-page pt-2 pb-1">
    <div class="row">
        <div class="col-sm-1 pl-0">
            <img class="riddle-dark-icon" src="<?php echo RIDDLE_IMAGE_PATH; ?>/icon-color-large.png" alt="Riddle Dark Icon">
        </div>
        <div class="col-sm-10 align-items-end">
            <?php if (isset($data['breadcrumbs'])): ?>
                <h1 class="riddle-navbar-title">
                
                <?php foreach ($data['breadcrumbs'] as $i => $breadcrumb): ?>
                    <span><?php echo $breadcrumb['text']; ?></span>

                    <?php if ($i + 1 < count($data['breadcrumbs'])): ?>
                        <img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-chev-right-small.png" alt="BreadCrumb chev right small">
                    <?php endif; ?>
                <?php endforeach; ?>
                
                </h1>
            <?php else: ?>
                <h2 class="riddle-navbar-title"><?php echo $data['title'] ?? 'Riddle Plugin'; ?></h2>
            <?php endif; ?>
        </div>
    </div>
</div>
<hr style="margin-bottom: 0;">