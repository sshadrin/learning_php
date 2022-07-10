<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create new Text</title>
    <style>
        .create {
            background: green;
            color: whitesmoke;
            padding: 5px;
            border: 1px solid black;
            border-radius: 5px;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
@csrf
{{"Ваша запись успешно добавлена!"}}
<br>
<br>
<a href="/text" class="create">Вернуться назад</a>
</body>
</html>
