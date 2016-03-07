<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:47 PM
 */
namespace App\Report;

use Illuminate\Support\ServiceProvider;
use App\Report\Reporter;

class ReportServiceProvider extends ServiceProvider {

    protected $defer = true;

    public function register() {
        $this->app->singleton('App\Report\Reporter');
    }

    public function provides() {
        return ['App\Report\Reporter'];
    }
}