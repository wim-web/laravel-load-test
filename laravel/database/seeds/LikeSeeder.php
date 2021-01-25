<?php

use App\Article;
use App\User;
use App\Like;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->chunk(10000, function ($users) {
            
            $ids = $users->pluck('id');
            $a_id = Article::query()->inRandomOrder()->first('id');
            
            $attributes = [];
            foreach ($ids as $id) {
                $attributes[] = ['user_id' => $id, 'article_id' => $a_id->id, 'created_at' => '2000-1-1', 'updated_at' => '2020-1-1'];
            }
            Like::query()->insert($attributes);
        });
    }
}
