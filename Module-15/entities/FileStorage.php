<?php

require_once "Storage.php";
require_once "Text.php";

class FileStorage extends Storage {

    private $events = [];

    function create()
    {
        if(isset($this->events[__FUNCTION__])) {
            $this->events[__FUNCTION__]();
        }

        $firstUnderscorePosition = strpos("object.txt",'.');
        $fileName = substr("object.txt", 0, $firstUnderscorePosition) . '_' .
            date('Y_m_d') . "." . "txt";

        $i = 1;
        while (file_exists(__DIR__.'/'.$fileName)) {
            $fileName = substr("object.txt", 0, $firstUnderscorePosition) . '_' . date('Y_m_d') .
                "_" . $i . "." . "txt";
            $i++;
        }
        file_put_contents($fileName, "");

        return $fileName;
    }

    function read ($slug)
    {
        if(isset($this->events[__FUNCTION__])) {
            $this->events[__FUNCTION__]();
        }

        if (file_exists($slug)) {
            return unserialize(file_get_contents($slug));
        }

        return false;
    }

    function update($slug, $data)
    {
        if(isset($this->events[__FUNCTION__])) {
            $this->events[__FUNCTION__]();
        }

        if (file_exists($slug)) {
            return file_put_contents($slug, serialize($data));
        }

        return false;
    }

    function delete($slug)
    {
        if(isset($this->events[__FUNCTION__])) {
            $this->events[__FUNCTION__]();
        }

        if (file_exists($slug)) {
            unlink($slug);
        }
    }

    function list()
    {
        if(isset($this->events[__FUNCTION__])) {
            $this->events[__FUNCTION__]();
        }

        foreach (scandir(__DIR__) as $elem) {
            if(substr_count($elem, ".txt")) {
                $object [] = unserialize(file_get_contents($elem));
            }
        }

        return $object;
    }

    public function logMessage($str)
    {
        if(isset($this->events[__FUNCTION__])) {
            $this->events[__FUNCTION__]();
        }

        $log = date("Y-m-d H:i:s") . " - " . $str . ";";
        return file_put_contents(__DIR__ . "/log.txt", $log . PHP_EOL, FILE_APPEND);
    }

    public function lastMessages($number)
    {
        if(isset($this->events[__FUNCTION__])) {
            $this->events[__FUNCTION__]();
        }

        $arr = file_get_contents("log.txt");
        $array = explode(PHP_EOL, $arr);
        $output = array_slice($array, count($array) - $number, $number);

        if (count($array) >= $number)
        {
            return $output;
        }

        return "error";
    }

    public function attachEvent($name, $callback)
    {
        $this->events[$name] = $callback;
    }

    public function detouchEvent($name)
    {
        unset($this->events[$name]);
    }

}
