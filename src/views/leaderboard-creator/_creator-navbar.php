<?php $baseUrl = 'riddle-admin-menu&subpage=%s&id=' . $data['type']->getId(); ?>

<ul class="nav nav-fill justify-content-center">
  <?php foreach ($data['creatorPages'] as $subpage): ?>
    <?php $active = $subpage === $data['creatorPage'] ? 'active' : ''; ?>

    <li class="nav-item">
      <a class="nav-link pb-3 <?php echo $active; ?>" href="<?php echo self::getAdminUrl(sprintf($baseUrl, 'creator-' . $subpage)); ?>"><?php echo strtoupper($subpage); ?></a>
    </li>
  <?php endforeach; ?>
</ul>