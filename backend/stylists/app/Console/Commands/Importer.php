<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScraperController;

class Importer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will import the fetched data';

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

        $this->comment(PHP_EOL.'Import started...'.PHP_EOL);
        if ($scraperObj->getImport()) {
            $this->comment(PHP_EOL . 'Import complete :)' . PHP_EOL);
        } else {
            $this->comment(PHP_EOL . 'Error identified in importing the products' . PHP_EOL);
        }
    }
}
