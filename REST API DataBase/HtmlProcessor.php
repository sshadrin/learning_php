<?php

    header("Content-Type: application/json");  // устанавливаем формат json

    if ($_SERVER["REQUEST_METHOD"] === "GET") {  // если был использован метод GET для получения данных с сайта
        $file = json_decode(file_get_contents("raw_text.json"), 1);  // переданный объект декодируем в ассоциированный массив

        $html = file_put_contents("raw_text.html", $file);  //создаем html файл для замены в нем ссылок
        $htmlFile = file_get_contents("raw_text.html");

        $dom = new DOMDocument;  // с помощью DOM заменяем атрибуты ссылок
        $dom->loadHTML($htmlFile);
        foreach ($dom->getElementsByTagName('a') as $link) {
            $link->removeAttribute('href');
        }
        foreach ($dom->getElementsByTagName('link') as $key) {
            $link->removeAttribute('rel');
        }

        $formated_text = file_put_contents("formated_raw_text.html", $dom->saveHTML());  // записываем в новый файл
        $formatedFile = file_get_contents("formated_raw_text.html");
        $json = file_put_contents("formated_raw_text.json", json_encode(["formated_raw_text" => $formatedFile]));  // создаем отформатированный json объект
        $jsonContent = json_decode(file_get_contents("formated_raw_text.json"), 1);  // декодируем
        print_r($jsonContent);  // выводим в браузер



    } else {
        echo http_response_code(500);  // иначе выдаем ошибку
    }


