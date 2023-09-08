<?php

namespace App\Jobs;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshReserv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $maxReserve = 30;
        $books = Book::all();
        foreach ($books as $book) {
            $reserveTime = (new Carbon($book->reserved_at))->addSeconds($maxReserve);
            if ($reserveTime < Carbon::now()) {
                $book->reserved_at = null;
                $book->user_id = null;
                $book->save();
            }
        }
    }
}
