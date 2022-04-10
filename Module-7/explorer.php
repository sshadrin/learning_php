<?php

$searchRoot = null; // каталог для поиска
$searchName = null;  // имя файла для поиска
$searchResult = [];  // массив, который будет возвращать найденные значения


function search (string $searchRoot, string $searchName, array &$searchResult): array  // объявляем нашу функцию поиска
{
    $directory = scandir($searchRoot); // сканируем наш каталог
    var_dump($directory);  // выводим все папки(директории) содержащиеся в нашем каталоге
    $dir = opendir($searchRoot);  // открываем каталог для работы
    while (false !== ($file = readdir($dir))) {  // обходим все элементы каталога, пока они не кончатся
        if ($file != "." && $file != "..") { // пропускаем ненужные элементы
            if (is_dir($searchRoot."\\".$file)) {  // если в директории есть еще директории, вызываем рекурсию
                search($searchRoot . "\\" . $file, $searchName, $searchResult);
            } elseif ($file == $searchName) {  // если в директории есть необходимый файл
                $searchResult [] = $searchRoot . "\\" . $file;  // добавляем его в наш массив
            }
        }
    }
    closedir($dir);  // закрываем каталог
    return $searchResult;  // возвращаем значение нашей функции
}

search(__DIR__, "test.txt",
    $searchResult);  // вызываем нашу функцию с необходимыми параметрами

function sizeOfFile ($searchName): bool  // объявляем функцию для определения размера файла
{
    return filesize($searchName) > 0;  // возвращаем значение нашей функции, если размер файла больше 0
}

$searchResult = array_filter($searchResult, "sizeOfFile");  // используем фильтрацию нашего массива с помощью
// callback функции для отсеивания файлов с нулевым размером из нашего массива

if (empty($searchResult)) {  // если искомых файлов не найдено
    print_r("Files not found" . PHP_EOL);
} else {
    var_dump($searchResult);  // выводим наш отсортированный массив
}

