<?php print_r($session); ?>
<menu class="r block__menu">
<!--  --><?php //foreach ($this->items as $field): ?>
  <?php foreach (\Views\MenuView::$items as $index => $field): ?>
    <li class="c-m-2 <?php if($session->menu_item == $index) { echo 'active'; } ?>">
<!--    <li class="c-m-2 --><?//= $session->menu_item ?><!--">-->
      <a href="<?= HOME . '/' . $field->id ?>">
        <i class="fa fa-<?= $field->icon ?> fa-lg"></i>
        <span><?= $field->text ?></span>
      </a>
    </li>
  <?php endforeach; ?>
</menu>
