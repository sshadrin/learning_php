<?php  //роутинг для нашего проекта

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get("/text", [TextController::class, "read"]);  //основная страница, отображает базу данных на данный момент
Route::view("/add",  "add");  //шаблон верстки для создания новой строки в таблице
Route::post("/create", [TextController::class, "create"]); //обработка формы для создания новой строки в таблице
Route::view("/change", "change");  //шаблон верстки для изменения строки в таблице
Route::post("/update/{id}", [TextController::class, "update"]);  //обработка формы для изменения  строки в таблице
Route::view("/unset", "unset");  //шаблон верстки для удаления строки в таблице
Route::get("/delete/{id}", [TextController::class, "delete"]);  //обработка формы для удаления строки в таблице


