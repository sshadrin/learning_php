<?php

function entities($className) {
    if(file_exists("entities/" . $className . ".php")) {
        require_once "entities/" . $className . ".php";
    }

}

spl_autoload_register("entities");
