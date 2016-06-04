<!--<div class="c-s-6 c-m-6 c-l-3 block__phones">-->
<!--  <div class="r field__phone">-->
<!--    <span class="os-t-2 c-t-4 c-s-3 os-m-4 os-l-0 c-l-4 field__operator">Kyivstar</span>-->
<!--    <a href="tel:+380975343332">+38 (097) 534 33 32</a>-->
<!--  </div>-->
<!--  <div class="r field__phone">-->
<!--    <span class="os-t-2 c-t-4 c-s-3 os-m-4 os-l-0 c-l-4 field__operator">MTS</span>-->
<!--    <a href="tel:+380665713332">+38 (066) 571 33 32</a>-->
<!--  </div>-->
<!--  <div class="r field__phone">-->
<!--    <span class="os-t-2 c-t-4 c-s-3 os-m-4 os-l-0 c-l-4 field__operator">Life</span>-->
<!--    <a href="tel:+380935203332">+38 (093) 520 33 32</a>-->
<!--  </div>-->
<!--</div>-->

<div class="c-s-6 c-m-6 c-l-3 block__phones">
  <?php foreach ($this->items as $field)
  $this->display('fields', $field);
  ?>
</div>
