<?php

namespace App\Http\Controllers;

use App\EmailQueue;
use App\Jobs\SendEmail;
use Illuminate\Http\Request;

use App\Http\Requests;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action, $id)
    {
        if(isset($_SERVER["HTTP_REFERER"])){
            $url = $_SERVER["HTTP_REFERER"];
            $url_components = parse_url($url);

            if($url_components['host'] == 'istyleyou.in'){
                $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
                if($id){
                    $this->resource_id = $id;
                }

                return $this->$method($request);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        $eq = EmailQueue::find($this->resource_id);
        if($eq->id){
            $job = (new SendEmail($eq))->onQueue('emails')->delay(60);
            echo $this->dispatch($job);
        }
    }
}
