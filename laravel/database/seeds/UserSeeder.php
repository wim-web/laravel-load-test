<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::newModelInstance()->forceFill(['name' => 'test', 'password' => 'test'])->save();
        
        for ($i = 0; $i < 50; $i++) { 
            
            $attributes = [];
            for ($j = 0; $j < 15000; $j++) { 
                $attributes[] = [
                    'name' => "dummy_$i", 'password' => "dummy_$i", 'created_at' => '2000-1-1', 'updated_at' => '2020-1-1'
                ];
            }
            
            User::query()->insert($attributes);

        }
    }
}
