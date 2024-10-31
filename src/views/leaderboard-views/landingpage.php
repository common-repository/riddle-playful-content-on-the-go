<div class="container-fluid">
    <div class="col-sm-12 riddle-landingpage-box">

        <?php if ('null' !== $data['injected']->getValue('leaderboardPictureURL')): ?>
        <div class="riddle-landingpage-box-top">
            <img src="<?php echo $data['injected']->getValue('leaderboardPictureURL'); ?>" alt="<?php echo $data['injected']->getValue('leaderboardPictureAlt'); ?>" style="width: 100%">
        </div>
        <?php endif; ?>
        <div class="riddle-landingpage-box-bottom">
            <?php if ($data['renderer']->hasData()): ?>
                <h3>
                    <span class="leaderboard-module-gray-heading"><?php echo $data['injected']->getValue('yourScoreText'); ?></span> 
                    <span class="leaderboard-module-score bold"><?php echo round($data['renderer']->get('resultData.scorePercentage'), 2); ?>%</span>
                </h3>
            <?php endif; ?>

            <?php echo $data['renderer']->renderModule('leaderboard', [
                'emptyMessage' => $data['injected']->getValue('emptyMessage'),
                ]); ?>
        </div>

    </div>
</div>