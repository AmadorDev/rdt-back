<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $req)
    {
        \Log::debug($req["tranlations"]);

        $data = [
            'author' => 'Gummibeer',
            'en'     => ['title' => 'one', "content" => 'config'],
            'es'     => ['title' => 'uno', "content" => 'configuracion'],
        ];
        $post = Post::create($data);
    }

    function list($value = '') {
        \App::setLocale('es');
        $post = Post::paginate(2);

        return $post;
    }
}
