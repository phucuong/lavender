<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
      <ul>
        <?php foreach ($categories as $cat) { ?>
        <li>
          <?php if ($cat['cat_id'] == $cat_id) { ?>
          <a href="<?php echo $cat['href']; ?>" class="active"><?php echo $cat['name']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $cat['href']; ?>"><?php echo $cat['name']; ?></a>
          <?php } ?>
          <?php if ($cat['children']) { ?>
          <ul>
            <?php foreach ($cat['children'] as $child) { ?>
            <li>
              <?php if ($child['cat_id'] == $child_id) { ?>
              <a href="<?php echo $child['href']; ?>" class="active"> - <?php echo $child['name']; ?></a>
              <?php } else { ?>
              <a href="<?php echo $child['href']; ?>"> - <?php echo $child['name']; ?></a>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
