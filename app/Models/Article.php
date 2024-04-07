<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_desc',
        'desc',
        'author_id'
    ];

    public function comment () {
        // hasMany означает связь "один ко многим"
        return $this->hasMany(Comment::class);
    }
}