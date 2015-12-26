<?php

namespace App\Http\Controllers;

use App\Status;
use App\Stylist;
use Illuminate\Http\Request;

use App\Http\Requests;

class StylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getList(Request $request){
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $status_list = Status::all()->keyBy('id');
        $status_list[0] = new Status();

        $stylists =
            Stylist::
                orderBy('stylish_id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['stylists'] = $stylists;
        $view_properties['status_list'] = $status_list;

        return view('stylist.list', $view_properties);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView()
    {
        $stylist = Stylist::find($this->resource_id);
        $view_properties = null;
        if($stylist){
            $status_list = Status::all()->keyBy('id');
            $status_list[0] = new Status();

            $view_properties['stylist'] = $stylist;
            $view_properties['status_list'] = $status_list;
        }
        else{
            return view('404', array('title' => 'Stylist not found'));
        }

        return view('stylist.view', $view_properties);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
