<?php

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

abstract class Storage {

    abstract function create ();
    abstract function read ($slug);
    abstract function update($slug, $data);
    abstract function delete ($slug);
    abstract function list ();

}

abstract class View {
    public  $storage;

    public function __construct($storage) {
        $this->storage = $storage;
    }

    abstract function displayTextById ();
    abstract function displayTextByUrl ();
}

abstract  class User {
    public int $id;
    public string $name;
    public string $role;

    abstract function getTextsToEdit ();
}

class FileStorage extends Storage {

    function create()
    {
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
    {   if (file_exists($slug)) {
            return unserialize(file_get_contents($slug));
        }
        return false;
    }

    function update($slug, $data)
    {
        if (file_exists($slug)) {
            return file_put_contents($slug, serialize($data));
        }

        return false;
    }

    function delete($slug)
    {
        if (file_exists($slug)) {
            unlink($slug);
        }
    }

    function list()
    {
        foreach (scandir(__DIR__) as $elem) {
            if(substr_count($elem, ".txt")) {
                $object [] = unserialize(file_get_contents($elem));
            }
        }

        return $object;
    }

}

$array = new FileStorage();
$article = new Text("Sergey", "object_2021_11_01.txt", date("Y_m_d"), $array);
$article->editText("Abstraction", "abstraction");
$array->create();
$article->storeText();
var_dump($article->loadText());

