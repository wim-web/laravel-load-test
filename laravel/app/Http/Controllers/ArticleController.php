<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::query()->paginate(100);
        
        return $articles;
    }
    
    public function show(Article $article)
    {
        return $article;
    }
}
