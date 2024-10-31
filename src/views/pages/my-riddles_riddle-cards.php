<div class="riddle-list-row">
<?php foreach ($data['riddles'] as $riddle): ?>
<div class="row">
<div class="col-sm-12 col-md-10 col-lg-10 col-xl-10 card riddle-card">
    <div class="row">
        <div class="col-md-3 col-xl-2">
            <img src="<?php echo $riddle["thumb"]; ?>" alt="Riddle Thumbnail">
        </div>
        <div class="col-md-6 col-xl-7 mt-auto">
            <h5 class="riddle-card-title mb-4"><?php echo $riddle["title"]; ?></h5>

            <div class="row mb-auto align-items-end">
                <div class="col-md-2">
                    <small class="text-muted"><?php echo \ucfirst($riddle['type']); ?></small>
                </div>
                <div class="col-md-3">
                    <!-- @todo get date format from Wordpress -->
                    <small class="text-muted"><?php echo date('d.m.Y', strtotime($riddle['datepublished'])); ?></small>
                </div>
                <?php if ('quiz' === $riddle['type']): ?>
                    <?php $type = isset($data['landingPages'][$riddle['id']]) ? $data['landingPages'][$riddle['id']] : false; ?>
                    <div class="col-md-4">
                        <?php if (!$type): ?>
                            <small><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&subpage=creator-create&type=leaderboard&riddleId=' . $riddle['id'] . '&slug=' . urlencode($riddle["title"])); ?>">
                                <img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-add.png" alt="Add Leaderboard Icon">
                                Add leaderboard
                            </a></small>
                        <?php else: ?>
                            <small><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&subpage=creator-edit&id=' . $type->getId()); ?>">
                                <img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-star.png" alt="View Leaderboard Icon">
                                View leaderboard
                            </a></small>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($type && $type->hasLeads()): ?>
                        <div class="col-md-3">
                            <small><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&subpage=leads&id=' . $type->getId()); ?>">
                                View entries
                            </a></small>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- <div class="alert alert-success riddle-copy-success mt-3" role="alert" data-riddle-id="<?php echo $riddle["id"]; ?>">
                Copied!
            </div> -->
        </div>
        <div class="col-md-3 col-xl-3 my-auto riddle-float-right">
            <button type="button" class="btn btn-riddle btn-cyan btn-riddle-clipboard" data-shortcode="rid" data-riddle-id="<?php echo $riddle["id"]; ?>" data-toggle="modal" data-target="#shortcodeModal">
                Get shortcode
                <img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-code.png" alt="Wordpress Publish Code Icon">
            </button>
        </div>
    </div>
</div>
</div>
<?php endforeach; ?>
</div>