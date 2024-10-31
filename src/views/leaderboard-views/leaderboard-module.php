<?php

// Define the template without <tr> so that we can add special classes to the row in the next step
// you can add the parameters "encrypted" and "encryptedEmail" if you do not want to show personal sensitive data. It's important if you want to meet GDPR standards
$template =
    '<td>{ index }</td>
     <td>{ lead2.Email.value }</td>
     <td>{ resultData.scorePercentage }%</td>';

$template = '';

foreach ($data['injected']->getValue('leadFields') as $leadField) {
    $template .= '<td>{' . htmlentities($leadField) . '}</td>';
}

$spotTemplate = '<tr class="leaderboard-spot-td">' . $template . '</tr>';
$template = '<tr>' . $template . '</tr>';

$filler =
    '<tr>
        <td>...</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>';

?>

<!-- the following block will only be rendered, if the user is better than the min variable -->
<p class="leaderboard-module-better-than-heading">
    <?php echo $data['module']->renderShortcode('better-than', [
        'min' => $data['injected']->getValue('betterThanMin'),
        'template' => $data['injected']->getValue('betterThanTemplate'),
    ]); ?>
</p>

<p class="bold light-gray leaderboard-module-gray-heading"><?php echo $data['injected']->getValue('leaderboardHeading'); ?></p>
<p class="leadeboard-module-gray-text">
    <?php echo $data['injected']->getValue('leaderboardText'); ?>
</p>

<div class="riddle-table-responsive">
    <table class="riddle-table riddle-table-sm riddle-table-striped">
        <tr class="leaderboard-tr-head">
            <?php foreach ($data['injected']->getValue('leadHead') as $leadField): ?>
                <th><?php echo $leadField; ?></th>
            <?php endforeach; ?>
        </tr>

        <?php $amountEntries = $data['injected']->getValue('amountEntries'); ?>

        <?php if ('-1' === $amountEntries): // show all entries ?>
            <?php echo $data['module']->renderBlock('leaderboard-leads', [
                'range' => 'all',
                'template' => $template,
                'spotTemplate' => $spotTemplate,
            ]); ?>
        <?php else: ?>
            <!-- Here you can set how many entries you want to show on the leaderboard.
                Range can be a number. The code below shows the range from 1-5
                -->
            <?php echo $data['module']->renderBlock('leaderboard-leads', [
                'range' => [1, $amountEntries],
                'template' => $template,
                'spotTemplate' => $spotTemplate,
            ]); ?>

            <!-- Here you can set how many leading entries you want to show on top of the leaderboard.
                Range can be a number. The code below shows the range from 1-5
                -->
            <?php echo $data['module']->renderBlock('spot-leaderboard-leads', [
                'range' => [1, 1],
                'template' => $template,
                'spotTemplate' => $spotTemplate,
                'templatePrefix' => $filler,
            ]); ?>

            <?php echo $data['module']->renderBlock('last-leaderboard-lead', [
                'template' => $template,
                'templatePrefix' => $filler,
                'spotTemplate' => $spotTemplate,
            ]); ?>
        <?php endif; ?>
    </table>
</div>

<p class="leadeboard-module-gray-text">
    <?php echo $data['module']->renderShortcode('placement', [
        'template' => $data['injected']->getValue('placementTemplate'),
        'min' => 20,
        'placementStringMode' => 'number',
    ]); ?>
</p>

<!-- 
    place = what's the place the user has missed?
    placeName = specify how the place range should be named (e.g. "You've missed out on the 'top 10' by xxx")
-->
<p class="leadeboard-module-gray-text">
    <?php echo $data['module']->renderShortcode('missed-place', [
        'place' => $data['injected']->getValue('missedPlaceIndexTemplate'),
        'template' => $data['injected']->getValue('missedPlaceTemplate'),
    ]); ?>
</p>