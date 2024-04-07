<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Path;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatMail;
use Illuminate\Support\Carbon;

class SendStatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pathCount = Path::all()->count();
        Path::whereNotNull('id')->delete();
        $commentCount = Comment::whereData('created_at', Carbon::today())->count();
        Mail::to('ramil-frontend@mail.ru')->send(new StatMail($pathCount, $commentCount));
    }
}