<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Mail\AdminCommentMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewCommentNotify;
use App\Events\NewCommentEvent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Метод отображения всех комментариев
    public function index() {
        $page = '0';
        if (isset($_GET['page'])) $page = $_GET['page'];

        $comments = Cache::remember('index_comments/'.$page, 3000, function () {
            return Comment::latest()->paginate(6);
        });

        return view('comment.index', ['comments' => $comments]);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
            'article_id' => 'required'
        ]);
        $comment = new Comment;
        $comment->title = $request->title;
        $comment->text = $request->text;
        $comment->author_id = Auth::id();
        $comment->article_id = $request->article_id;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            Mail::to('ramil-frontend@mail.ru')->send(new AdminCommentMail($comment));
        }
    
        return redirect()->route('article.show', ['article' => $request->article_id, 'res'=>$res]);
    }

    public function delete($comment_id) {
        $comment = Comment::findOrFail($comment_id);
        Gate::authorize('comment', $comment);
        $article_id = $comment->article_id;
        $res = $comment->delete();
        
        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
        }

        return redirect()->route('article.show', ['article' => $article_id]);
    }

    public function update(Request $request, $comment_id) {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);
    
        $comment = Comment::findOrFail($comment_id);
        Gate::authorize('comment', $comment); // Проверка на право доступа

        $comment->title = $request->title;
        $comment->text = $request->text;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
        }
    
        return redirect()->route('article.show', ['article' => $comment->article_id]);
    }

    public function edit($comment_id) {
        $comment = Comment::findOrFail($comment_id);
        Gate::authorize('comment', $comment); // Проверка на право доступа

        return view('comment.edit_comment', ['comment' => $comment]);
    }

    // Метод для одобрения комментария
    public function accept($comment_id) {
        Gate::authorize('comment-admin');

        $comment = Comment::findOrFail($comment_id);
        $comment->status = true;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);

            $article = Article::findOrFail($comment->article_id);
            
            // Получаем всех пользователей кроме того, кто оставил комментарий
            $users = User::where('id', '!=', $comment->author_id)->get();
    
            Notification::send($users, new NewCommentNotify($article));
            NewCommentEvent::dispatch($article);
        }

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

        return redirect()->route('comments', ['page' => $page]);
    }

    // Метод для отклонения комментария
    public function reject($comment_id) {
        Gate::authorize('comment-admin');

        $comment = Comment::findOrFail($comment_id);
        $comment->status = false;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
        }

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

        return redirect()->route('comments', ['page' => $page]);
    }
    
    public function clearCacheForArticle($article_id=null) {
        if (isset($article_id)) {
            $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'comments/'.$article_id.'/*[0-9]'])->get();
            foreach($keys as $key) {
                Cache::forget($key->key);
            }    
        }
    }

    public function clearCacheForComments() {
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'index_comments/*[0-9]'])->get();
        foreach($keys as $key) {
            Cache::forget($key->key);
        }
    }
}
