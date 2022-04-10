<?php

$textStorage = []; // создаем массив, в который будем записывать наши новости

function add (string $title, string $text, &$textStorage) {  // создаем функцию, для добавления заголовка и текста
    $textStorage[] = [
        "title" => $title,
        "text" => $text
    ];
}

add("О личностных особенностях человека", "Рассмотрим, некоторые типы личности(черты характера) присущих 
человеку", $textStorage);  // добавляем первый заголовок и текст к нему в наш массив

add("Демонстративный тип(демонстративные черты)", "Главной особенностью людей с доминирующими демонстративными 
чертами характера является привлечение внимания окружающих к себе. Человек с преобладающими чертами демонстративности,
никогда не даст забыть о себе в компании. Это коммуникабельные, инициативные, настойчивые, целеустремленные люди, все
свои положительные качества немедленно доносятся до окружающих их людей. Это люди лидеры, способны организовать себя и
окружающих в краткосрочной перспективе, однако после всплеска энергии быстро теряют данные способности лидера. К 
негативным аспектам относятся завышенная(неадекватно) самооценка, лживость, склонность к интригам и пустой демагогии
(постоянное подчеркивание своих положительных качеств), игра на публику, эмоциональная неуравновешенность при 
задевании негативных черт. При разоблачении выдумок такого человека возможны аффективные вспышки(слезы у женщин, 
кулаки у мужчин).", $textStorage);  // добавляем второй заголовок и текст к нему

var_dump($textStorage); // выводим наш массив

function remove (int $elem, &$textStorage): bool  // создаем функцию для удаления выбранного текста из массива
{
    unset($textStorage[$elem]["text"]);
    $count = count($textStorage);
        return $elem < $count;
}

remove(0, $textStorage);  // удаляем текст первой новости, оставляем один заголовок

remove(5, $textStorage);  // пробуем удалить текст не существующего элемента массива

var_dump($textStorage);  // выводим полученный результат

function edit (int $index, array &$textStorage, string $title = null, string $text = null): bool  // создаем функцию для
// редактирования выбранного заголовка и текста, если заголовок или текст отсутствует, функция повторно удаляет
// отсутствующие элементы
{
    if (isset($textStorage[$index])){  // проверяем соответствует ли индекс null
        if (!empty($title)) {
            $textStorage[$index]['title'] = $title;  // если title не пустой, функция меняет его значение
        }
        if (!empty($text)) {
            $textStorage[$index]['text'] = $text; // аналогично title для text
        }
        return true; // Пусть функция возвращает true, если текст с нужным индексом существует
    }
    return false; // false, если вдруг такого текста в массиве не оказалось
}

edit(0,$textStorage, "О характере человека", "");  // редактируем заголовок первой новости

edit(10,$textStorage, "О особенностях эмоциональной сферы искусственного интеллекта",
    "Исследования в данной области еще не проводились, так как не получен ответ на извечный вопрос Бытие 
    или Компьютер"); // пробуем отредактировать заголовок и текст не существующей новости(несуществующего элемента)

var_dump($textStorage); // выводим полученные результаты

