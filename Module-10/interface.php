<?php

interface LoggerInterface {

    public function logMessage($str);

    public function lastMessages($number);

}

interface EventListenerInterface {

    public function attachEvent($name, $callback);

    public function detouchEvent($name);

}

class Text
{
    public string $title;
    public string $text;
    public string $author;
    public string $published;
    public string $slug;
    private $storage;

    public function __construct($author, $slug, $published, Storage $storage)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = $published;
        $this->storage = $storage;
    }

    public function storeText ()
    {
        $this->storage->update($this->slug,[
            "text" => $this->text,
            "title" => $this->title,
            "author" => $this->author,
            "published" => $this->published
        ]);
    }

    public function loadText ()
    {
        if (file_exists($this->slug)) {
            $data = $this->storage->read($this->slug);
        }

        return $data;
    }

    public function editText ($title, $text)
    {
        $this->title = $title;
        $this->text = $text;
    }
}

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

abstract class View {
    public  $storage;

    public function __construct($storage) {
        $this->storage = $storage;
    }

    abstract function displayTextById ();
    abstract function displayTextByUrl ();
}

abstract  class User implements EventListenerInterface {
    public int $id;
    public string $name;
    public string $role;
    public $events = [];

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

$storage = new FileStorage();
$storage->attachEvent("create", function () use ($storage) {$storage->logMessage("add create");});
$storage->create();

