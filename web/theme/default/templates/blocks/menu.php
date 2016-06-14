<menu class="r block__menu">
  <?php foreach ($this->items as $field): ?>
    <li class="c-m-2">
      <a href="<?= HOME . '/' . $field->alias ?>">
        <i class="fa fa-<?= $field->icon ?> fa-lg"></i>
        <span><?= $field->text ?></span>
      </a>
    </li>
  <?php endforeach; ?>
</menu>
