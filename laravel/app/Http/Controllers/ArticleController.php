<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::query()->paginate(100);
        
        return $articles;
    }
    
    public function store(Request $request, AuthManager $manager)
    {
        $user = $manager->user();
        
        return $user->articles()->create($request->only(['title', 'body']));
    }
    
    public function show(Article $article)
    {
        return $article;
    }
    
    public function update(Request $request, Article $article, AuthManager $manager)
    {
        $user = $manager->user();
        
        if ($article->user_id !== $user->id) {
            return new AuthorizationException("Not Authorized");
        }
        
        $article->fill($request->only(['title', 'body']))->save();
        
        return $article;
    }
    
    public function destroy(Article $article, AuthManager $manager)
    {
        $user = $manager->user();
        
        if ($article->user_id !== $user->id) {
            return new AuthorizationException("Not Authorized");
        }
        
        $article->delete();
        
        return response('Deletion successful', 204);
    }
}
