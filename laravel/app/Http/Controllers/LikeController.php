<?php

namespace App\Http\Controllers;

use App\Article;
use App\Like;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function like(Article $article, AuthManager $manager)
    {
        return DB::transaction(function () use ($article, $manager) {

            $user = $manager->user();

            $article->query()->where('id', $article->id)->lockForUpdate()->increment('like_count');

            return Like::newModelInstance()
                ->create(['user_id' => $user->id, 'article_id' => $article->id]);
        });
    }

    public function unlike(Article $article, AuthManager $manager)
    {
        DB::transaction(function () use ($article, $manager) {

            $user = $manager->user();

            $article->query()->where('id', $article->id)->lockForUpdate()->decrement('like_count');

            Like::query()
                ->where(['user_id' => $user->id, 'article_id' => $article->id])
                ->delete();
        });
        
        return response('Unlike successful', 204);
    }
}
