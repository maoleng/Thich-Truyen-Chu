<?php

namespace App\Console\Commands;

use App\Http\Controllers\ComicController;
use Illuminate\Console\Command;

class CloneComic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comic:clone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clone comic';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        (new ComicController)->clone();
    }
}
