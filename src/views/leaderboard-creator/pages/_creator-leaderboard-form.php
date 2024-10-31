<?php

$type = $data['type'];
$availableFields = $data['availableFields'];

?>

<form method="POST" class="crp-form">

<?php if (!isset($availableFields['Email'])): ?>
    <h2>Whoops! There's no Email field in your lead form.</h2>
    <p>Please add a lead email field with the ID 'Email' to your lead form.</p>
    <p>Your RiddleID: <?php echo $type->getValue('id'); ?></p>

    <?php return false; ?>
<?php else: ?>
    <div class="riddle-form-field-cell row">
        <div class="col-sm-12">
            <input type="submit" value="<?php echo $data['submitText']; ?>" class="btn btn-success mt-2 mb-2">
        </div>
    </div>
    
    <input type="hidden" name="riddle_type_id" value="<?php echo $type->getValue('id'); ?>">
    <input type="hidden" name="riddle_type_slug" value="<?php echo $type->getValue('slug'); ?>">

    <div class="riddle-form-field-cell row" style="padding-right: 40px;">
        <div class="col-sm-10 my-auto">
            <p>Main Image</p>
            <p class="riddle-image-preview" <?php echo $type->getValue('leaderboardPictureURL') === 'null' ?  'style="display:none;"' : ''; ?>>Selected: <a class="riddle-image-preview-link" style="text-decoration: underline;" href="<?php echo $type->getValue('leaderboardPictureURL'); ?>" target="__blank">click</a></p>
        </div>
        <div class="col-sm-2 my-auto">
            <input id="riddlePluginLeaderboardUploadPicture" type="button" class="btn btn-riddle btn-cyan" value="ADD" <?php echo $type->getValue('leaderboardPictureURL') !== 'null' ?  'style="display:none;"' : ''; ?>/>
            <button type="button" class="btn btn-sm btn-riddle btn-danger" id="btn-remove-img" <?php echo $type->getValue('leaderboardPictureURL') === 'null' ?  'style="display:none;"' : ''; ?>>REMOVE</button>

            <input id="riddleLeaderboardPictureURL" type="hidden" name="riddle_type_leaderboardPictureURL" value="<?php echo $type->getValue('leaderboardPictureURL'); ?>" />
            <input id="riddleLeaderboardPictureAlt" type="hidden" name="riddle_type_leaderboardPictureAlt" value="<?php echo $type->getValue('leaderboardPictureAlt'); ?>">
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-10 my-auto">
            <p>Score Message</p>
        </div>
        <div class="col-sm-2 my-auto">
        </div>
        <div class="col-sm-12">
            <input type="text" name="riddle_type_yourScoreText" class="form-control crp-form-input riddle-form-field" placeholder="Enter a 'your score' text" value="<?php echo $type->getValue('yourScoreText'); ?>" required>
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-10 my-auto">
            <p>Leaderboard title</p>
        </div>
        <div class="col-sm-2 my-auto">
        </div>
        <div class="col-sm-12">
            <input type="text" name="riddle_type_leaderboardHeading" class="form-control crp-form-input riddle-form-field" placeholder="Enter a leaderboard heading" value="<?php echo $type->getValue('leaderboardHeading'); ?>" required>
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-10 my-auto">
            <p>Description</p>
        </div>
        <div class="col-sm-2 my-auto">
        </div>
        <div class="col-sm-12">
            <input type="text" name="riddle_type_leaderboardText" class="form-control crp-form-input riddle-form-field" placeholder="Enter a description" value="<?php echo $type->getValue('leaderboardText'); ?>" required>
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-10 my-auto">
            <p>Result text (with %%PERCENTAGE%% variable)</p>
        </div>
        <div class="col-sm-2 my-auto">
        </div>
        <div class="col-sm-12">
            <input type="text" name="riddle_type_betterThanTemplate" class="form-control crp-form-input riddle-form-field" placeholder="Enter a 'better than' template" value="<?php echo $type->getValue('betterThanTemplate'); ?>" required>
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-10 my-auto">
            <p>Text message if there are no entries</p>
        </div>
        <div class="col-sm-2 my-auto">
        </div>
        <div class="col-sm-12">
            <input type="text" name="riddle_type_emptyMessage" class="form-control crp-form-input riddle-form-field" placeholder="Enter a text" value="<?php echo $type->getValue('emptyMessage'); ?>" required>
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-10 my-auto">
            <p>Motivational message</p>
        </div>
        <div class="col-sm-2 my-auto">
        </div>
        <div class="col-sm-12">
            <input type="text" name="riddle_type_missedPlaceTemplate" class="form-control crp-form-input riddle-form-field" placeholder="Enter a text that motivates your user to achieve a better result" value="<?php echo $type->getValue('missedPlaceTemplate'); ?>" required>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<?php endif; ?>


<input type="hidden" name="riddle_type_sent" value="1">
<div class="riddle-form-field-cell row" style="border-bottom: 0px !important;">
    <div class="col-sm-12">
        <input type="submit" value="<?php echo $data['submitText']; ?>" class="btn btn-success mt-2 mb-2">
    </div>
</div>

</form>