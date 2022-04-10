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
    private string $title;
    private string $text;
    private string $author;
    private string $published;
    private string $slug;
    private $storage;

    public function __construct($author, $slug, $published, Storage $storage)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = $published;
        $this->storage = $storage;
    }

    public function text () {
        $this->storeText();
        return $this->loadText();
    }

    private function storeText ()
    {
        $this->storage->update($this->slug,[
            "text" => $this->text,
            "title" => $this->title,
            "author" => $this->author,
            "published" => $this->published
        ]);
    }

    private function loadText ()
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

    public function setAuthor ($author)
    {
        if (strlen($author) > 120 !== false) {
            $this->author = $author;
        } else {
            echo "error" . PHP_EOL;
        }
    }

    public function getAuthor ()
    {
        return $this->author;
    }

    public function setSlug ($slug)
    {
        $this->slug = $slug;
        $slug = str_split($slug);
        foreach ($slug as $elem) {
            if (ctype_alnum($elem) || stripos($elem,".") !== false || stripos($elem,"_") !== false
                || stripos($elem,"/") !== false || stripos($elem,"-") !== false) {
                continue;
            } else {
                echo "error\n";
                break;
            }
        }
    }

    public function getSlug ()
    {
        return $this->slug;
    }

    public function setPublished ($date)
    {
        $this->published = date("Y_m_d");
        if ($this->published >= $date) {
            return $this->published;
        }
        return false;
    }

    public function getPublished ()
    {
        return $this->published;
    }

    public function __set ($name, $value) {
        if ($name == "author") {
            $this->setAuthor($value);
        } elseif ($name == "slug") {
            $this->setSlug($value);
        } elseif ($name == "published") {
            $this->setPublished($value);
        } elseif ($name == "text") {
            return $this->text;
        }
        return false;
    }

    public function __get ($name) {
        if ($name == "author") {
            return $this->getAuthor();
        } elseif ($name == "slug") {
            return $this->getSlug();
        } elseif ($name == "published") {
            return $this->getPublished();
        } elseif ($name == "text") {
            return $this->text;
        }
        return false;
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

$object = new FileStorage();
$obj = new Text("Sergey", "object_2021_11_28.txt", date("Y_m_d"), $object);
$obj->editText("Module #11", "get and set");
$obj->text();
var_dump($obj->text());

