<?php

include_once "autoload.php";

$object = new FileStorage();
$obj = new Text("Sergey", "object_2021_12_27.txt" ,date("Y_m_d"), $object);
$object->logMessage("Запись лога");
$object->attachEvent("create", function () use ($object) {$object->logMessage("Обработчик событий подключен");});
$obj->editText("Module-13", "learning how to work with file system");
$object->create();
$obj->text();

