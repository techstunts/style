<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScraperController;

class Fetcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will fetch the data from scraping hub';

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
    public function handle()
    {
        $scraperObj = new ScraperController();

        $this->comment(PHP_EOL.'Product fetching started...'.PHP_EOL);
        if($scraperObj->getFetchLatest()){
            $this->comment(PHP_EOL.'All products fetched from scraping hub'.PHP_EOL);
            $this->comment(PHP_EOL.'Done :)'.PHP_EOL);
        }else{
            $this->comment(PHP_EOL.'Error identified in fetching the products'.PHP_EOL);
        }
    }
}
