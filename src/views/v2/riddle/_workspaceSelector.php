
<div class="row mb-0">
    <div class="col-sm-12 mb-0 pb-0 pl-0">
        <h2 class="riddle-page-title">Riddle account connected! <small class="text-muted"><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&disconnect=1') ?>">Disconnect</a></small> </h2>
    </div>
</div>

<div class="row mt-0">
    <div class="col-sm-12 card riddle-card mb-0 pb-0">
        <div class="riddle-form-wrapper">
            <form method="POST" class="team-select-form">
                <?php if (!empty($data['teams'])): ?>
                    <small class="text-muted">Choose your workspace</small>
                    <select name="team" class="riddle-form-field form-control riddle-select">
                        <option value="">My personal account</option>

                        <?php foreach ($data['teams'] as $team): ?>
                            <option value="<?php echo $team['id']; ?>" <?php echo $data['selectedTeam'] === $team['id'] ? 'selected'  : ''; ?>> <?php echo $team['name']; ?> </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <input type="hidden" name="submitted" value="1">
            </form>

            <ul class="nav nav-tabs mt-4">
                <li class="nav-item" style="min-width: 20%">
                    <a class="nav-link pt-3 pl-4 <?php echo !src\Api\RiddleLoaderV2::shouldShowV2Riddles() ? 'btn-cyan' : ''; ?>" href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&apiVersion=1') ?>" style="text-decoration: none;">Riddles</a>
                </li>
                <li class="nav-item" style="min-width: 20%">
                    <a class="nav-link pt-3 pl-4 <?php echo src\Api\RiddleLoaderV2::shouldShowV2Riddles() ? 'btn-cyan' : ''; ?>" href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&apiVersion=2') ?>" style="text-decoration: none;">2.0 Riddles</a>
                </li>
            </ul>
        </div>
    </div>
</div>