<!--<menu class="r block__menu">-->
<!--  <li class="c-m-2 active"><a href="#"><i class="fa fa-home fa-lg"></i><span>О компании</span></a></li>-->
<!--  <li class="c-m-2"><a href="#"><i class="fa fa-money fa-lg"></i><span>Доставка и оплата</span></a></li>-->
<!--  <li class="c-m-2"><a href="#"><i class="fa fa-info-circle fa-lg"></i><span>Гарантия и возврат</span></a></li>-->
<!--  <li class="c-m-2"><a href="#"><i class="fa fa-question-circle fa-lg"></i><span>Как заказать</span></a></li>-->
<!--  <li class="c-m-2"><a href="#"><i class="fa fa-envelope fa-lg"></i><span>Контакты</span></a></li>-->
<!--</menu>-->

<menu class="r block__menu">
<?php //foreach ($this->items as $field) {
////  $this->item = $field;
//  $this->display('fields', $field);
//} ?>

  <?php foreach ($this->items as $field): ?>
    <li class="c-m-2">
      <a href="<?= HOME . '/' . $field['alias'] ?>">
        <i class="fa fa-<?= $field['icon'] ?> fa-lg"></i>
        <span><?= $field['text'] ?></span>
      </a>
    </li>
  <?php endforeach; ?>
</menu>
