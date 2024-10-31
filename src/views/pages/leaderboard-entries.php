<?php self::view('components/navbar.php', ['breadcrumbs' => [
    ['text' => 'My Riddles'],
    ['text' => $data['riddle']['title'] ?? 'Riddle ' . $data['type']->getValue('id')],
    ['text' => 'Leaderboard Entries'],
]]); ?>

<div class="riddle-page">
    <h2 class="riddle-page-title mb-4">
        Leaderboard Entries 
        <small class="text-muted"><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&subpage=leads&action=reset&id=' . $data['type']->getId()) ?>">Reset entries</a></small> 
        <small class="text-muted"><a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&subpage=leads&action=download&id=' . $data['type']->getId()) ?>">Download entries</a></small> 
    </h2>

    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <?php if (!$data['type']->hasLeads()): ?>
                <p>This leaderboard hasn't got any entries yet.</p>
            <?php else: ?>
                <table class="table">
                    <tr>
                        <th>Index</th>
                        <th>Key</th>
                        <th>Added on</th>
                    </tr>
                    <?php foreach ($data['type']->getLeads()['entries'] as $i => $entry): ?>
                    <tr>
                        <td><?php echo $i +1; ?></td>
                        <td><?php echo $entry['key']; ?></td>
                        <td><?php echo $entry['dates'][0]; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>