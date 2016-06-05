<div class="c-s-6 c-m-6 c-l-3 block__phones">
  <?php foreach ($this->items as $field): ?>
  <div class="r field__phone">
    <span class="os-t-2 c-t-4 c-s-3 os-m-4 os-l-0 c-l-4 field__operator"><?= $field->operator ?></span>
    <a href="tel:<?= str_replace([' ', '(', ')'], '', $field->number) ?>"><?= $field->number ?></a>
  </div>
  <?php endforeach; ?>
</div>
