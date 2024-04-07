<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = json_decode(file_get_contents(public_path().'/articles.json'));
        foreach ($articles as $article) {
            Article::create([
                'name'          =>  $article->name,
                'short_desc'    =>  $article->short_desc,
                'desc'          =>  $article->desc,
                'author_id'     =>  '1',
                'preview_image' =>  $article->preview_image,
                'full_image'    =>  $article->full_image,
                'date'          =>  $article->date,
            ]);
        }
    }
}