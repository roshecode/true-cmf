<main>
  <div class="container-fluid">
    <?php foreach ($this->items as $region) {
      $this->display('regions', $region);
    } ?>
  </div>
</main>
