<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProductSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product_sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will sync the products from Nicobar into ISY';

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
        $productController = new ProductController();

        $this->comment(PHP_EOL.'Sync started...'.PHP_EOL);
        $response = $productController->postSyncProducts();
        if ($response['status']) {
            $this->comment(PHP_EOL . 'Sync complete :)' . PHP_EOL);
        } else {
            $this->comment(PHP_EOL . 'Error identified in syncing the products' . PHP_EOL);
        }
        Log::info($response['message']);
    }
}
