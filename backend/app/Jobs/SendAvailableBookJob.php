<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\BookWaitlist;
use App\Models\User;
use App\Notifications\BookAvailableNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendAvailableBookJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected Book $book;


    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function handle(): void
    {
        if ($this->book->available_copies <= 0) {
            return;
        }

        $waitlists = BookWaitlist::where('book_id', $this->book->id)->with('user')->get();

        foreach ($waitlists as $waitlist) {
            try {
                $waitlist->user->notify(new BookAvailableNotification($this->book));
                $waitlist->delete();
            } catch (\Exception $e) {
                Log::error("Не удалось отправить уведомление пользователю {$waitlist->user_id}: " . $e->getMessage());
            }
        }
    }

}
