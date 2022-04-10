<?php

require_once "php-developer-base/Module-13/interfaces/EventListenerInterface.php";

abstract  class User implements EventListenerInterface {
    protected int $id;
    protected string $name;
    protected string $role;
    protected $events = [];

    abstract function getTextsToEdit ();

    public function attachEvent($name, $callback)
    {
        $this->events[$name] = $callback;
    }

    public function detouchEvent($name)
    {
        unset($this->events[$name]);
    }
}
