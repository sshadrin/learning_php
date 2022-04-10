<?php

require_once "C:/xampp\htdocs\welcome\php-developer-base\Module-15\interfaces\LoggerInterface.php";
require_once "C:/xampp\htdocs\welcome\php-developer-base\Module-15\interfaces\EventListenerInterface.php";
require_once "Text.php";
require_once "FileStorage.php";

abstract class Storage implements LoggerInterface, EventListenerInterface {

    abstract function create ();
    abstract function read ($slug);
    abstract function update($slug, $data);
    abstract function delete ($slug);
    abstract function list ();
    abstract function logMessage($str);
    abstract function lastMessages($number);
    abstract function attachEvent($name, $callback);
    abstract function detouchEvent($name);
}

