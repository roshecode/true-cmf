<?php

//$mail->setFrom('sheva2012h@gmail.com', 'Mailer');
//$mail->addAddress('sheva2012h@gmail.com', 'Joe User');
//$mail->Subject = 'Here is the subject';
//$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//setcookie("name", 'Roman', time() + 600, "/index.php", ".avto.ua");

require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/bootstrap.inc';

\Data\Session::init(Array(
  'products' => Array(),
  'productsCount' => 0,
  'totalCost' => 0
));
//\Data\Session::destroy();
\Models\BaseModel::init();
\Views\BaseView::init(\External\Libs::twig());
//$mail = \External\Libs::phpMailer();

\Data\Mail::init();
//\Route\Router::init();
\Route\Router::start();

//\Data\Transfer::import('test.csv', ['delimiter' => '&']);
//\Data\Transfer::import('price.csv', ['delimiter' => '&']);
//\Data\Transfer::addOrder();
