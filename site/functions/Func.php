<?php

namespace Functions;

use Data\Session;

class Func {

  public static function createCallbackMailMessage() {
    $message = '<table rules="all" style="border-color: #666;" cellpadding="10">';
    $message .= '<tbody>';
    $message .= '<tr><td><strong>Телефон: </strong></td><td>' . $_POST['phone'] . '</td></tr>';
    $message .= '<tr><td><strong>Имя: </strong></td><td>' . $_POST['name'] . '</td></tr>';
    $message .= '</tbody>';
    $message .= '</table>';
    return '<div class="review">' . $message . '</div>';
  }

  public static function createVinMailMsg() {
    $message = '<table class="review" rules="all" style="border-color: #666;" cellpadding="10">';
    $message .= '<tbody>';
    $message .= '<tr><td><strong>Телефон: </strong></td><td>' . $_POST['phone'] . '</td></tr>';
    $message .= '<tr><td><strong>E-mail: </strong></td><td>' . $_POST['email'] . '</td></tr>';
    $message .= '<tr><td><strong>VIN-код: </strong></td><td>' . $_POST['vin'] . '</td></tr>';
    $message .= '<tr style="background: #eee;"><td>Запчасть</td><td>Кол-во</td></tr>';
    foreach ($_POST['details'] as $index => $detail) {
      $message .= '<tr><td>' . $detail . '</td><td>' . $_POST['quantity'][$index] . ' шт.</td></tr>';
    }
    $message .= '</tbody>';
    $message .= '</table>';
    return '<div class="review">' . $message . '</div>';
  }
  
  public static function createProductsMailMsg($products) {
    $message = '<table class="review" rules="all" style="border-color: #666;" cellpadding="10">';
    $message .= '<thead>';
    $message .= '<tr><td><strong>Имя и фамилия: </strong></td><td  colspan="6">' . $_POST['name'] . '</td></tr>';
    $message .= '<tr><td><strong>Телефон: </strong></td><td  colspan="6">' . $_POST['phone'] . '</td></tr>';
    $message .= '<tr><td><strong>E-mail: </strong></td><td  colspan="6">' . $_POST['email'] . '</td></tr>';
    $message .= '<tr><td><strong>Город: </strong></td><td  colspan="6">' . $_POST['city'] . '</td></tr>';
    $message .= '</thead>';
    $message .= '<tbody>';
    $message .= '<tr style="background: #eee;"><td>Код</td><td>Поставщик</td><td>Название</td><td>Описание</td><td>Цена</td><td>Кол-во</td><td>Стоимость</td></tr>';
    foreach ($products as $index => $product) {
      $message .= '<tr><td>' . $product['code'] . '</td><td>' . $product['vendor'] . '</td><td>' . $product['name'] . '</td><td>' . $product['description'] . '</td><td>' . $product['price'] . ' грн.</td><td>' . $product['quantity'] . ' шт.</td><td>' . $product['summary'] . ' грн.</td></tr>';
    }
    $message .= '<tr><td colspan="5"><strong>Общая стоимость: </strong></td><td><strong>' . Session::get('productsCount') . ' шт.</strong></td><td><strong>' . Session::get('totalCost') . ' грн.</strong></td></tr>';
    $message .= '</tbody>';
    $message .= '<tfoot>';
    $message .= '<tr><td><strong>Комментарий: </strong></td><td  colspan="7">' . $_POST['comment'] . '</td></tr>';
    $message .= '</tfoot>';
    $message .= '</table>';
    return '<div class="review">' . $message . '</div>';
  }
}
