<?php
class TelegraphText
{
    public string $title;
    public string $text;
    public string $author;
    public string $published;
    public string $slug;

    public function __construct($author, $slug, $published)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = $published;
    }

    public function storeText ()
    {
        file_put_contents($this->slug, serialize([
            "text" => $this->text,
            "title" => $this->title,
            "author" => $this->author,
            "published" => $this->published,]));
    }

    public function loadText ()
    {
        if (file_exists($this->slug)) {
            unserialize(file_get_contents($this->slug));
        }
        return $this->text;
    }

    public function editText ($title, $text)
    {
        $this->title = $title;
        $this->text = $text;
    }
}

$article = new TelegraphText("Sergey", "test_text_file.txt", date("r"));
$article->editText("Основы ООП", "Классы, объекты, методы, поля и свойства, как же во всем разобраться?");
$article->storeText();
$article->loadText();

print_r($article);
print_r($article->loadText());
