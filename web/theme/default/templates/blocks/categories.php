<ul class="block__categories">
  <?php foreach ($this->items as $field): ?>
    <li class="c-m-2">
      <a href="#">
        <span><?= $field->name ?></span>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
