<?php

require_once "Storage.php";
require_once "FileStorage.php";

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
