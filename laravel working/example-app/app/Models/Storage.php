<?php  //создали модель Storage по заданию

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;
    protected $fillable = [
        'author',
        'title',
        'text',
        'published',
    ];
}
