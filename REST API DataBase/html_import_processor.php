<!DOCTYPE html>
<!-- Создаем нашу форму -->
<html lang="ru">
<head>
    <title>Parsing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<style>
    .reg-form {
        max-width: 800px;
        margin: 0 auto;
    }
    textarea {
        font-family: Tahoma sans-serif;
        font-size: 5px;
        font-style: italic;
        opacity: 0.5;
    }
    .style {
        font-family: Tahoma sans-serif;
        font-size: 16px;
        font-style: italic;
        opacity: 0.5;
    }
</style>
<body>
<form class="reg-form" method="post" action="html_import_processor.php">
    <h1 id="#zakladka">Получаем контент</h1>
    <br>
    <div class="mb-3">
        <label for="name" class="form-label">Введите URL адрес:</label>
        <input type="text" id="name" name="name" class="reg-form__input form-control style" placeholder="введите url">
    </div>
    <div class="mb-3">
        <input type="submit" class="btn btn-success" value="принять">
    </div>
</form>
<?php
    $curl = curl_init();  // инициируем функцию curl
    $text = fopen("raw_text.txt", "w");  // открываем поток для записи в файл
    curl_setopt($curl, CURLOPT_URL, $_POST["name"]);  // устанавливаем параметры для curl - url введенный в форму
    curl_setopt($curl, CURLOPT_HTTPGET, 1);  // метод GET для получения текста страницы
    curl_setopt($curl, CURLOPT_PORT, 443);  // порт https
    curl_setopt($curl, CURLOPT_FILE, $text);  // файл в который будет записан результат
    curl_setopt($curl, CURLOPT_HEADER, 0); // заголовок
    $exec = curl_exec($curl);  // запускаем curl
    if(curl_error($curl)) {  // проверяем на ошибки
        fwrite($text, curl_error($curl));
    }
    fclose($text);  // закрываем поток
    curl_close($curl);  // закрываем curl

    $file = file_get_contents("raw_text.txt");  // получаем текст
    $json = file_put_contents("raw_text.json", json_encode(["raw_text" => $file]));  // создаем json объект где значение полученный текст

    $ch = curl_init();  // инициируем функцию curl для редиректа json объекта
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_close($ch);  // закрываем curl
    if ($_POST["name"]) {  // если форма отправлена делаем редирект
        header("location: HtmlProcessor.php");
    } else {
        echo http_response_code(500);  // иначе выводим ошибку
    }
?>
</body>
</html>

