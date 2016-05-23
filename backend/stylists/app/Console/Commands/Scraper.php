<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScraperController;

class Scraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will scrap the data';

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
        if($scraperObj->getFetchLatest()){
            $this->comment(PHP_EOL.'All products fetched from scraping hub'.PHP_EOL);
            $this->comment(PHP_EOL.'Merchant product import started...'.PHP_EOL);

            $scraperObj->getImport();
        }else{
            $this->comment(PHP_EOL.'Error identified in fetching the products'.PHP_EOL);
        }
        $this->comment(PHP_EOL.'Done :)'.PHP_EOL);
    }
}
