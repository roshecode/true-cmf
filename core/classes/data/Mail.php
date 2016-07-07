<?php

namespace Data;

use External\Libs;
use Models\UsersModel;

class Mail {

  protected static $to;
  protected static $from;

  public static function init() {
//    self::$to = 'sheva2012h@gmail.com';
    $user = UsersModel::getById(1);
    self::$to = $user['email'];
    self::$from = 'avtomagazin.dp.ua';
//    self::$from = 'sivokon1984@mail.ru';
  }

  public static function send($subject, $message,
                              $success = '<p>Данные успешно отправлены</p>',
                              $fail = '<p>Ошибка отправки данных</p>') {

//    $mail = Libs::phpMailer();
//    $mail->IsSMTP();// отсылать используя SMTP
//    $mail->SMTPAuth = true; // включить SMTP аутентификацию
//    $mail->SMTPSecure = "ssl";
//    $mail->Host = "smtp.mail.ru"; // SMTP сервер
//    $mail->Port = 465;
//    $mail->Username = "s.he.va@bk.ru"; // SMTP username
//    $mail->Password = "drol21755"; // SMTP password
//    $userMail = null;
//    if (isset($_POST['email'])) {
//      $userMail = $_POST['email'];
//    }
////    $mail->AddReplyTo($userMail,"User"); // е-мэил того кому прейдет ответ на ваше письмо
//    $mail->AddAddress(self::$to); // е-мэил кому отправлять
//    $mail->setFrom('s.he.va@bk.ru'); // укажите от кого письмо; // имя отправителя
////    $mail->WordWrap = 50;// set word wrap
//    $mail->IsHTML(true);// отправить в html формате
//
//    $mail->Subject = $subject; // тема письма
//    $mail->Body = $message; // тело письма в html формате
//
////    $mail->AltBody = $message; // тело письма текстовое
//
//    if(!$mail->Send())
//    {
//      return $success . $message;
//    } else {
//      return $fail . ' ' . $mail->ErrorInfo;
//    }

//    if (imap_mail(self::$to, $subject, $message)) {
//      return $success . $message;
//    } else {
//      return $fail;
//    }
//  }

//    $headers = "From: " . strip_tags($_POST['email']) . "\r\n";
    $eol = "\r\n";
//    $fromaddress = "iroman.via@gmail.com";

    $headers = "From: " . self::$from . $eol;
//    $headers = "From: " . $fromaddress . $eol;
//    $headers = '';
    if (isset($_POST['email'])) {
      $headers .= "Reply-To: ". strip_tags($_POST['email']) . $eol;
    } else {
      $headers .= "Reply-To: ". self::$to . $eol;
    }

    $headers .= 'Return-Path: MyName <' . self::$to . '>' . $eol;
    $headers .= "Errors-To: " . self::$to . $eol;
//      $headers .= "CC: susan@example.com\r\n"; // Copy
    $headers .= "MIME-Version: 1.0" . $eol;
//    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//    $headers .= 'Content-Type: text/html; charset=utf-8';
    $headers .= 'Content-Type: text/html; charset=utf-8';
//    $headers .= 'Content-Type: text/html; charset="Windows-1251"\r\n';
    $message .= 'Дата: ' . date('d.M.Y H:i:s');
    if (mail(self::$to, $subject, $message, $headers)) {
      return $success . $message;
    } else {
      return $fail;
    }
  }
}
