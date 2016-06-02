<?php
//require_once 'db.php';
$db = DB::getInstance();

$delimiter = '&';

$fh = fopen("test.csv", "rb"); // Открываем файл в режиме чтения
if ($fh)
{
  $i = 0;
  while (!feof($fh))
  {
    $row = explode($delimiter, fgets($fh, 999));
//    echo $row . "<br />";
//    echo $row[3] . '<br />';

//    echo preg_split('/[\s,]+/', $row[3])[0] . '<br />';
//    echo preg_split('/[a-zA-Z,;]+/', $row[3])[0] . '<br />';

//    echo $row[2] . ' --- ' . preg_split('/[b-zA-Z,;]+/', $row[3])[0] . '<br />';
    try {
      $db->insert('categories', [
        'id' => ++$i,
//        'name' => preg_split('/[\sa-zA-Z][d-zA-Z,;:]+/', $row[3])[0]
//        'name' => preg_split('/[a-zA-Z,;:]+/', $row[3])[0]
        'name' => preg_split('/(\s[a-zA-Z])|([,;:])/', $row[3])[0]
      ]);
    } catch (PDOException $e) {
      --$i;
    }
  }
}
else echo "Ошибка при открытии файла!";
fclose($fh);
