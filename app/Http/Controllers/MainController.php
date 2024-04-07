<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{   
	// Отображение всех статей
    public function show_all_articles() {
        $articles = json_decode(file_get_contents(public_path().'/articles.json'));
        return view('article.all_articles_json', ['articles' => $articles]);
    }

	// Отображение одной статьи
    public function show_one_article(Request $request) {
        $id = $request->id;
        $articles = json_decode(file_get_contents(public_path().'/articles.json'));
        $data = [];
        foreach ($articles as $article) {
            if ($article->id == $id) {
                $data = $article;
                break;
            } 
        }
        return view('article.one_article_json', ['article' => $data]);
    }

	// Отображение страницы "О нас"
    public function show_about_us() {
        return view('main.about_us');
    }

	// Отображение страницы "Контакты"
    public function show_contacts() {
        $data = ['Ivan', 'Elena', 'Matvei'];
        return view('main.contacts', ['data' => $data]);
    }
}