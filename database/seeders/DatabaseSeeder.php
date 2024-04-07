<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Comment;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // Создание статей с рандомными значениями и рандомными комментариями
        Article::factory(10)->has(Comment::factory(3))->create();
        
        // Использование сидеров
		$this->call([
			// ArticleSeeder::class, // Создание статей из json файл
            RoleSeeder::class, // Создание двух пользователей с разными ролями
		]);
    }
}