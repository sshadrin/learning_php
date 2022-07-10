<?php  //создали модель Text с которой осуществлялась работа

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;

    protected $fillable = [  //поля для баззы данных
        'author',
        'title',
        'text',
        'published',
    ];

}
