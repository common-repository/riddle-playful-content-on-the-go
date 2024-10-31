<div class="row">
    <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10 card riddle-card">
        <div class="row">
            <div class="col-md-3 col-xl-2 my-auto">
                <img src="<?php echo $data['image']; ?>" alt="Riddle Thumbnail">
            </div>
            <div class="col-md-6 col-xl-7 mt-auto">
                <h5 class="riddle-card-title mb-4"><?php echo $data['title']; ?></h5>

                <div class="row mb-auto align-items-end">
                    <div class="col-md-2">
                        <small class="text-muted"><?php echo $data['type']; ?></small>
                    </div>
                    <div class="col-md-10">
                        <?php if (null !== $publishedAt = @$data['published']['at']): ?>
                            <small class="text-muted">Published @ <?php echo \date('d.m.Y', \strtotime($publishedAt)); ?></small>
                        <?php elseif (null !== $modifiedAt = @$data['published']['at']): ?>
                            <small class="text-muted">Modified @ <?php echo \date('d.m.Y', \strtotime($publishedAt)); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xl-3 my-auto riddle-float-right">
                <button type="button" class="btn btn-riddle btn-cyan btn-riddle-clipboard" data-shortcode="rid" data-riddle-id="<?php echo $data['UUID']; ?>" data-toggle="modal" data-target="#shortcodeModal">
                    Get shortcode
                    <img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-code.png" alt="Wordpress Publish Code Icon">
                </button>
            </div>
        </div>
    </div>
</div>