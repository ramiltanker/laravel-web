<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ArticleMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Article;

class ArticleMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Article $article) {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('ramil-frontend@mail.ru')->send(new ArticleMail($this->article));
    }
}