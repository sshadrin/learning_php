<?php

abstract class View {
    public  $storage;

    public function __construct($storage) {
        $this->storage = $storage;
    }

    abstract function displayTextById ();
    abstract function displayTextByUrl ();
}
