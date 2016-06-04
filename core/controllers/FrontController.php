<?php

require_once MODEL . '/categoriesModel.php';
require_once VIEW . '/layout/html.php';

$categories = new \Model\CategoriesModel();
$categories->init();

?><pre><?php
print_r($categories->getAll());
?></pre>