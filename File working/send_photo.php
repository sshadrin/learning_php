<!DOCTYPE html>
<!-- Создаем нашу форму -->
<html lang="ru">
<head>
    <title>Photo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<style>
    .reg-form {
        max-width: 800px;
        margin: 0 auto;
    }
    .style {
        font-family: Tahoma sans-serif;
        font-size: 18px;
        font-style: italic;
        opacity: 0.5;;
    }
    .file {
        color: blue;
        background: aqua;
        font-weight: bold;
    }
    .form {
        font-family: Tahoma sans-serif;
        color: green;
        font-size: 18px;
        font-style: italic;
        text-align: center;
    }
    .error {
        color: red;
        border: 1px solid black;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }
</style>
<body>
<!-- Добавляем возможность отправлять файлы -->
<form class="reg-form" method="post" action="send_photo.php" enctype="multipart/form-data"> <!-- Создаем нашу форму -->
    <h1 id="#zakladka">Отправляем фото</h1>
    <hr>
    <div class="mb-3">
        <h2><label for="photo" class="form-label"><b>Добавьте фото:</b></label></h2>
        <input type="file" id="photo" name="photo" accept="image/png, image/jpeg" class="file reg-form__input form-control style">
    </div>
    <div class="mb-3">
        <input type="submit" class="btn btn-success" value="отправить">
    </div>
</form>
</body>
</html>

<?php

    session_start();  // начинаем старт сессии

    try {   // оборачиваем в try catch для отлова ошибок
        if ($_FILES["photo"]["size"] > 2097152) {  // проверяем размер файла, не должен превышать 2мбайт
            // если превышает выводим ошибку
            echo "<div class='error mb-3 reg-form'>Ошибка, превышен максимальный размер файла или формат!</div>";
        // создаем условие, чтобы на сервер можно было добавить файлы с определенными разрешениями
        } elseif (strpos($_FILES["photo"]["name"], ".png") || strpos($_FILES["photo"]["name"], ".jpeg")
        || strpos($_FILES["photo"]["name"], ".jpg")) {
            // если условия проходят проверку, проверяем, отправлен ли файл на сервер
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], "./img/" . $_FILES["photo"]["name"])) {
                // если файл отправлен устанавливаем sent = 0 и при каждой итерации увеличиваем счетчик сессии
                if (! isset($_SESSION["sent"])) {
                    $_SESSION["sent"] = 0;
                } else {
                    $_SESSION["sent"]++;
                }
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $sent = $_SESSION["sent"];  // оборачиваем элемент массива в переменную для удобства

    if ($sent === null) {  // если sent = null просим загрузить фото
        echo "<div class='form mb-3 reg-form'>Загрузите ваше фото в формате .jpeg или .png!</div>";
    } else if ($sent === 0) {  // если sent = 0 вводим доп. переменную счетчик для корректного отображения результата
        $i = 1;
        echo "<div class='form mb-3 reg-form'>Фото успешно загружено!</div>";
        $count = $sent + $i;
        echo "<div class='form mb-3 reg-form'>Вы загрузили максимальное число фото = $count!</div>";
    } else {  // если sent > 1 выводим ошибку
        echo "<div class='error mb-3 reg-form'>Ошибка, превышено максимальное число фото!</div>";
        unlink("./img/" . $_FILES["photo"]["name"]);  // уничтожаем все последующие файлы
    }

    $dir = opendir("./img/");  // открываем директорию для работы
    while (false !== ($file = readdir($dir))) {  // циклом обходим все ее элементы
        if ($file != "." && $file != "..") { //  ненужные элементы пропускаем
            if ($file === $_FILES["photo"]["name"]) {  // если в директории есть наш файл
                $url = "./img/" . $_FILES["photo"]["name"];
                header("location: $url"); // делаем на него редирект
            }
        }
    }
    closedir($dir);  // директорию

