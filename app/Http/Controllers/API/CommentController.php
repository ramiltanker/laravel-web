<?php

namespace App\Http\Controllers\API;

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
use App\Http\Controllers\Controller;
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

        return response()->json(['comments' => $comments]);
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
            Mail::to('i.d.pereverzev@mail.ru')->send(new AdminCommentMail($comment));
            
            return response()->json([
                'result' => $res,
                'article' => $comment
            ]);
        }
    
        return response()->json([
            'result' => $res,
            'message' => 'Не удалось сохранить комментарий'
        ]);
    }

    public function delete($comment_id) {
        $comment = Comment::findOrFail($comment_id);
        Gate::authorize('comment', $comment);
        $article_id = $comment->article_id;
        $res = $comment->delete();
        
        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
            
            return response()->json([
                'result' => $res,
                'message' => 'Комментарий успешно удален'
            ]);
        }

        return response()->json([
            'result' => $res,
            'message' => 'Не удалось удалить комментарий'
        ]);
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
            
            return response()->json([
                'result' => $res,
                'article' => $comment
            ]);
        }
    
        return response()->json([
            'result' => $res,
            'message' => 'Не удалось обновить комментарий'
        ]);
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
            
            return response()->json([
                'result' => $res,
                'message' => 'Статья успешно принят'
            ]);
        }

        return response()->json([
            'result' => $res,
            'message' => 'Не удалось принять комментарий'
        ]);
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
            
            return response()->json([
                'result' => $res,
                'message' => 'Статья успешно отклонена'
            ]);
        }

        return response()->json([
            'result' => $res,
            'message' => 'Не удалось отклонить статью'
        ]);
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
