<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ExpireReservationsJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $reservation;

    /**
     * Create a new job instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!($this->reservation)) {
            return; // Бронь удалена или не существует
        }

        if ($this->reservation->expires_at < now()) {
            $this->reservation->delete();
        }
    }
}
