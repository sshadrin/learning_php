<?php

function entities($className) {
    if(file_exists("entities/" . $className . ".php")) {
        require_once "entities/" . $className . ".php";
    }
}

function composer () {
    require_once "vendor/" . "autoload.php";
}


spl_autoload_register("entities");
spl_autoload_register("composer");
