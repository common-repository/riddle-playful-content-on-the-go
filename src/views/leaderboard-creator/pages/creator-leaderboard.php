<?php

$type = $data['type'];
$availableFields = $data['availableFields'];
$leadfieldNames = $type->getLeadfieldNames();
$leadfieldLabels = $type->getLeadfieldLabels();

?>

<form method="POST" class="crp-form">

<?php if (!isset($availableFields['Email'])): ?>
    <h2>Whoops! There's no Email field in your lead form.</h2>
    <p>Please add a lead email field with the ID 'Email' to your lead form.</p>
    <p>Your RiddleID: <?php echo $type->getValue('id'); ?></p>

    <?php return false; ?>
<?php else: ?>
    <input type="submit" value="UPDATE" class="btn btn-success mt-4 mb-4"> <br>
    <input type="hidden" name="riddle_type_id" value="<?php echo $type->getValue('id'); ?>">

    <div class="riddle-form-field-cell row">
        <div class="col-sm-9 my-auto">
            <p>Sort by</p>
        </div>
        <div class="col-sm-3 my-auto">
            <select name="riddle_type_leaderboardMode" id="" class="form-control crp-form-input riddle-form-field">
                <?php foreach (unserialize(LEADERBOARD_MODES) as $value => $label): ?>
                    <option value="<?php echo $value; ?>" <?php echo $value === $type->getValue('leaderboardMode') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-9 my-auto">
            <p>Number of entries</p>
        </div>
        <div class="col-sm-3 my-auto">
            <select id="displayModeSelect" class="form-control crp-form-input riddle-form-field">
                <?php foreach ($data['amountEntriesOptions'] as $value => $label): ?>
                    <?php $selected = $value == $type->getValue('amountEntries'); ?>
                    <option value="<?php echo $value; ?>" <?php echo $selected ? 'selected' : ''; ?>><?php echo $label; ?> <?php echo $selected ? '(selected)' : ''; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-12">
            <div class="displayModeInputDiv pt-2" <?php echo in_array($type->getValue('amountEntries'), [-1, 10]) ? 'style="display:none;"' : ''; ?>>
                <p>Enter a number of entries:</p>
                <input type="number" placeholder="Enter how many entries should be displayed..." name="riddle_type_amountEntries" class="displayModeInput form-control crp-form-input" value="<?php echo $type->getValue('amountEntries'); ?>" required>
            </div>
        </div>
    </div>

    <div class="riddle-form-field-cell row">
        <div class="col-sm-10 my-auto">
            <p>Column headings</p>
        </div>
        <div class="col-sm-2 my-auto">
        </div>
        <div class="col-sm-12">
            <ul id="sortable">
                <?php $i = 0; ?>
                
                <?php foreach($availableFields as $fieldName => $attr): ?>
                    <?php $selected = in_array($fieldName, $leadfieldNames); ?>
                    <?php $label = isset($leadfieldLabels[$fieldName]) ? $leadfieldLabels[$fieldName] : ''; ?>
                    <?php $sanitizedFieldName = str_replace(' ', '_', $fieldName); ?>
                    
                    <li class="ui-state-default">
                        <div class="row" field="<?php echo $fieldName; ?>" sanitized_field="<?php echo $sanitizedFieldName; ?>">
                            <div class="col-md-2 col-lg-1 my-auto">
                                <img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-reorder.png" alt="Leaderboard Reorder Icon">
                            </div>
                            <div class="col-md-8 col-lg-9 my-auto">
                                <?php echo $fieldName; ?><small class="pl-2 text-muted"><?php echo $attr['desc'] ?? 'imported from your lead fields'; ?></small>
                                <input type="text" placeholder="Custom label for <?php echo $fieldName; ?>..." class="form-control label-input riddle-form-field" value="<?php echo $label; ?>">
                            </div>
                            <div class="col-md-2 col-lg-2 my-auto">
                                <label class="switch">
                                    <input type="checkbox" class="riddle-checkbox" id="checkbox-<?php echo $sanitizedFieldName; ?>" <?php echo $selected ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </li>

                    <?php $i++; ?>
                <?php endforeach; ?>
            </ul>

            <input type="hidden" name="riddle_type_leadfieldNames" id="leadfieldNames" value="<?php echo implode(',', $leadfieldNames); ?>">
            <input type="hidden" name="riddle_type_leadfieldNamesOrder" id="leadfieldNamesOrder" value="<?php echo $type->getValue('leadfieldNamesOrder'); ?>">
            <input type="hidden" name="riddle_type_leadfieldLabels" id="leadfieldLabels" value="<?php echo $type->getValue('leadfieldLabels'); ?>">

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<?php endif; ?>


<input type="hidden" name="riddle_type_sent" value="1">
<input type="submit" value="UPDATE" class="btn btn-success">

</form>