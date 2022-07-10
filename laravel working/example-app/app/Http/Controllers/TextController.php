<?php  //наш контроллер который осуществляет всю логику взаимодействия базы данных с версткой

namespace App\Http\Controllers;

use App\Models\Text;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TextController extends Controller
{


    public function create(Request $request)  //метод создания строки таблицы в базе данных
    {
        $author = $request->get("author");  //переменные соответствующие полям таблицы в базе данных
        $title = $request->get("title");
        $text = $request->get("text");
        $published = $request->get("published");

        $object = new Text();  //создаем новую строку в таблице и выводим в браузер сообщение с возможностью редиректа
        $object->author = $author;
        $object->title = $title;
        $object->text = $text;
        $published = date("Y-m-d H:i:s");
        $object->published = $published;
        $object->save();
        $view = view("create")->with(["author" => $author, "title" => $title, "text" => $text, "published" => $published]);
        return $view;
    }

    public function read()  //метод вывода таблицы в браузер
    {
        $text = \App\Models\Text::all();
        $view = view("read")->with(["text" => $text]);
        return new Response($view);
    }

    public function update(Request $request)  //метод изменения строки в таблице базе данных с возможностью редиректа
    {
        $id = $request->get("id");
        $author = $request->get("author");
        $title = $request->get("title");
        $text = $request->get("text");
        $published = $request->get("published");
        $published = date("Y-m-d H:i:s");
        \App\Models\Text::findorFail($id)->update(["author" => $author, "title" => $title, "text" => $text, "published" => $published]);  //добавил проверку на id в если не существует выдаст код ответа 404
        $view = view("update");
        return $view;

    }

    public function delete(Request $request)  //метод удаления строки в таблице базе данных с возможностью редиректа
    {
        $id = $request->get("id");
        \App\Models\Text::findorFail($id)->delete();  //добавил проверку на id в если не существует выдаст код ответа 404
        $view = view("delete");
        return $view;
    }
}
