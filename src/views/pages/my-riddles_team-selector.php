
<div class="riddle-form-wrapper mb-4">
    <form method="POST" class="team-select-form">
        <small class="text-muted">Choose your workspace</small>
        <select name="team" class="riddle-form-field form-control riddle-select">
            <option value="">My personal account</option>
            <?php foreach ($data['teams'] as $teamId => $team): ?>
                <option value="<?php echo $teamId; ?>" <?php echo $data['selectedTeam'] == $teamId ? "selected" : ""; ?>> <?php echo $team; ?> </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
