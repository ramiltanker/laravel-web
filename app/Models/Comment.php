<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article; // импорт модели
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    public function article () {
        // belongTo означает обратную связь, 
        // принадлежность к другой модели
        return $this->belongsTo(Article::class);
    }

    public function user() {
        return $this->belongTo(User::class);
    }

    public function getAuthorName() {
        return User::find($this->author_id)->name;
    }
}
