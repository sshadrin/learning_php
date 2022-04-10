<?php

interface EventListenerInterface {

    public function attachEvent($name, $callback);

    public function detouchEvent($name);

}
