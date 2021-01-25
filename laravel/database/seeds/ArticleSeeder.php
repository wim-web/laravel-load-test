<?php

use App\Article;
use App\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->chunk(12000, function ($users) {
            
            $ids = $users->pluck('id');
            
            $attributes = [];
            foreach ($ids as $id) {
                $attributes[] = ['user_id' => $id, 'title' => 'title', 'body' => 'body', 'created_at' => '2000-1-1', 'updated_at' => '2020-1-1'];
            }
            Article::query()->insert($attributes);
        });
    }
}
