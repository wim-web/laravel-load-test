<?php

namespace App\Http\Controllers\User;

use App\Article;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthManager;
use App\Http\Controllers\Controller;
use App\Like;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request, AuthManager $manager)
    {
        $articles = $manager->user()->articles()->get();
        
        return $articles;
    }
    
    public function store(Request $request, AuthManager $manager)
    {
        $user = $manager->user();
        
        return $user->articles()->create($request->only(['title', 'body']));
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
    
    public function likedArticle(AuthManager $manager)
    {
        $user = $manager->user();
        
        $likes = Like::query()->where('user_id', $user->id)->get();
        
        $likedArticles = Article::query()->findMany($likes->pluck('article_id'));
        
        return $likedArticles;
    }
}
