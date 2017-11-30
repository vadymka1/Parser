<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\NewsRepository as NewsRepository;

use App\News;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Goutte\Client;
use GuzzleHttp\Client as Guzzle;

class Parser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new parser';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(NewsRepository $news)
    {
         $news= $news->parserAction();

        $this->info('The Parsing ended');
    }

}
