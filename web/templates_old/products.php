<div class="c-m-9 block__content">
  <table>
    <thead>
    <tr>
      <td>Код</td>
      <td>Поставщик</td>
      <td>Название</td>
      <td>Описание</td>
      <td>Цена, грн.</td>
      <td>Купить</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach (\Views\ProductsView::$items as $item): ?>
      <tr>
        <td><?= $item['code'] ?></td>
        <td><?= $item['vendor_id'] ?></td>
        <td><?= $item['name'] ?></td>
        <td><?= $item['description'] ?></td>
        <td class="price"><?= $item['price'] ?></td>
        <td><a href="addtocart"><i class="fa fa-shopping-cart fa-lg"></i></a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
