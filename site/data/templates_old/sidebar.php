<aside class="c-m-3">
  <div class="block__search">
    <input type="search" placeholder="Поиск по коду..." />
  </div>
  <div class="block__categories">
    <ul>
      <?php foreach (\Views\SidebarView::$items as $item): ?>
        <li>
<!--          <i class="fa fa-gear" style="float: left;"></i>-->
          <a href="<?= HOME ?>/category/<?= $item->id ?>"><?= $item->name ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</aside>
