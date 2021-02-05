<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page');

        $articles = Article::query()
            ->where('id', '>=', (($page - 1) * 100) + 1)
            ->where('id', '<=', ($page * 100))
            ->get();

        return $articles;
    }
    
    public function show(Article $article)
    {
        return $article;
    }
    
    public function store(Request $request)
    {
        Article::newModelInstance()
            ->forceFill(['user_id' => 1])
            ->fill($request->only(['title', 'body']))
            ->save();
    }
}
