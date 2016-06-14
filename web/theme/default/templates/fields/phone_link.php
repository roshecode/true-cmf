<div class="r field__phone">
  <span class="os-t-2 c-t-4 c-s-3 os-m-4 os-l-0 c-l-4 field__operator"><?= $this->item['operator'] ?></span>
  <a href="tel:<?= str_replace([' ', '(', ')'], '', $this->item['number']) ?>"><?= $this->item['number'] ?></a>
</div>
