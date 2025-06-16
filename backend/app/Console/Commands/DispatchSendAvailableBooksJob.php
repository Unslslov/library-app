<?php

namespace App\Console\Commands;

use App\Jobs\SendAvailableBookJob;
use Illuminate\Console\Command;

class DispatchSendAvailableBooksJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-available-books-job';

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
        dispatch(new SendAvailableBookJob());
    }
}
