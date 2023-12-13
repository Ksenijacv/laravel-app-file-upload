<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "image",
        "description"
    ];

    //funkcija za vracanje kolona iz tabele posts i pretvara ih u niz radi lepseg pregleda
    public static function getAllPosts(){
        $result = DB::table('posts')->select('id','name','description')->get()->toArray();
        return $result;
    }
}
