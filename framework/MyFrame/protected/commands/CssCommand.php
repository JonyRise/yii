<?php
class CssCommand extends CConsoleCommand
{
  private function writeInFile($path, $text)
  {
    $file = fopen($path, 'w+');
    fwrite($file, $text);
    fclose($file);
  }

  public function actionMinimize(string $path = '\css\new\template.css')
  {
    // Чтение информации с файла
    $root = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
    $text = file_get_contents($root . $path);

    // Убираем закоментированые свойства
    $text = preg_replace('~//.{1,};~', "", $text);
    $text = preg_replace('~/\*.{1,}\*/~', "", $text);

    // Убираем переносы на следующую строку, табуляцию и избыточные пробелы
    $text = str_replace(["\n", "\t"], "", $text);
    $text = preg_replace('~\s{2,}~', " ", $text);

    // Создаем путь к новому файлу
    $path_new_file = preg_replace('~\..{0,}~', "_min.css", $path);
    // Создаем имя нового файла на основе пути к нему
    preg_match('~[\w.\-_]{1,}\..{0,}~', $path_new_file, $name_new_file);
    $name_new_file = $name_new_file[0];

    // Определяем папку, в которой лежат файлы и создаем масив с файлами в этой папке
    $folder = $root . str_replace($name_new_file, "", $path_new_file);
    $folder_files = scandir($folder);


    // Проверяем был ли создан файл ранее 
    if (in_array($name_new_file, $folder_files)) {

      echo "Файл $name_new_file был создан ранее, перезаписать?";
      $command = readline("[Y/N]");

      if ((in_array($command, ['Y','y']))) {

        $this->writeInFile($root . $path_new_file, $text);
        echo "Перезаписано!";
      } else {

        echo "Отменено";
      }
    } else {

      $this->writeInFile($root . $path_new_file, $text);
      echo "Файл создан!";
    }
  }

  
}
