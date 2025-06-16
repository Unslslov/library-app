<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\BookWaitlist;
use App\Models\User;
use App\Notifications\BookAvailableNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendAvailableBookJob implements ShouldQueue
{
    use Queueable;


    public function __construct()
    {
    }

    public function handle(): void
    {
        $books = Book::where('available_copies', '>', 0)->get();

        foreach ($books as $book) {
            $waitlistBooks = BookWaitlist::where('book_id', $book->id)->get();
            foreach ($waitlistBooks as $waitlistBook) {
                $waitlistBook->user->notify(new BookAvailableNotification($book));

                $waitlistBook->delete();
            }
        }
    }

}
