<?php

namespace App\Http\Controllers;

use App\Models\Lookups\Lookup;
use App\Models\Lookups\StylistStatus;
use App\Models\Enums\EntityType;
use App\Models\Enums\ProfileImageStatus;
use App\Stylist;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

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

        $status_list = StylistStatus::all()->keyBy('id');
        $status_list[0] = new StylistStatus();

        $stylists =
            Stylist::with('gender','expertise','designation')
                ->orderBy('id', 'desc')
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
        $upload_images = function ($query) {
            $query->with('type');
            $query->where('uploaded_by_entity_type_id', EntityType::STYLIST);
            $query->where('status_id', ProfileImageStatus::Active);
        };
        $stylist = Stylist::with(['looks' => function ($query) {
            $query->orderBy('id', 'desc')->limit(3);
            },
            'upload_images' => $upload_images,
        ])->find($this->resource_id);

        $lookup = new Lookup();
        $image_types = $lookup->type('image_type')->where('entity_type_id', EntityType::STYLIST)->get();
        $image_type_names = array();
        foreach ($image_types as $image_type) {
            $image_type_names[] = $image_type->name;
        }
        $view_properties = null;
        if ($stylist) {
            $status_list = StylistStatus::all()->keyBy('id');
            $status_list[0] = new StylistStatus();

            $view_properties['stylist'] = $stylist;
            $view_properties['status_list'] = $status_list;
            $view_properties['looks'] = $stylist->looks;
            $view_properties['image_type_names'] = $image_type_names;
            $view_properties['is_owner_or_admin'] = Auth::user()->hasRole('admin') || $stylist->id == Auth::user()->id;
            $view_properties['profile_images'] = Storage::disk('public_images')->files('stylish/profile/' . $stylist->id);
        } else {
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
    public function getEdit()
    {
        $stylist = Stylist::find($this->resource_id);
        $is_admin = Auth::user()->hasRole('admin');
        if(!$is_admin){
            if($stylist->id != Auth::user()->id){
                return view('404', array('title' => 'You do not have permission to change this Stylist\'s details'));
            }
        }

        $view_properties = null;
        if($stylist){
            $lookup = new Lookup();

            $view_properties['stylist'] = $stylist;
            $view_properties['gender_id'] = intval($stylist->gender_id);
            $view_properties['genders'] = $lookup->type('gender')->get();
            $view_properties['status_id'] = intval($stylist->status_id);
            $view_properties['statuses'] = $lookup->type('stylist_status')->get();
            $view_properties['expertise_id'] = intval($stylist->expertise_id);
            $view_properties['expertises'] = $lookup->type('expertise')->get();
            $view_properties['designation_id'] = intval($stylist->designation_id);
            $view_properties['designations'] = $lookup->type('designation')->get();
            $view_properties['is_admin'] = $is_admin;
            $view_properties['image_types'] = $lookup->type('image_type')->where('entity_type_id', EntityType::STYLIST)->get();

        }
        else{
            return view('404', array('title' => 'Stylist not found'));
        }

        return view('stylist.edit', $view_properties);
    }

    public function postImage(Request $request)
    {
        $stylist = Stylist::find($this->resource_id);

        if($stylist) {

            $imageValidator =  Validator::make($request->all(), [
                'image' => 'required|image',
            ]);
            if($imageValidator ->fails()){
                return redirect('stylist/edit/' . $this->resource_id)
                    ->withErrors($imageValidator)
                    ->withInput();
            }

            if ($request->file('image')->isValid()) {
                $destinationPath = public_path() . '/' . env('STYLIST_IMAGE_PATH');
                $filename = preg_replace('/[^a-zA-Z0-9_.]/', '_', $request->file('image')->getClientOriginalName());
                $request->file('image')->move($destinationPath, $filename);
                $stylist->image = 'stylish/' . $filename;
                $stylist->save();
            }
        }
        else{
            return view('404', array('title' => 'Stylist not found'));
        }

        return redirect('stylist/edit/' . $this->resource_id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()){
            return redirect('stylist/edit/' . $this->resource_id)
                ->withErrors($validator)
                ->withInput();
        }

        $stylist = Stylist::find($this->resource_id);

        if(!Auth::user()->hasRole('admin') && $stylist->id != Auth::user()->id){
            return view('404', array('title' => 'You do not have permission to change this Stylist\'s details'));
        }

        $stylist->name = isset($request->name) && $request->name != '' ? $request->name : '';
        $stylist->description = isset($request->description) && $request->description != '' ? $request->description : '';
        $stylist->age = isset($request->age) && $request->age != '' ? $request->age : '';
        $stylist->profile = isset($request->profile) && $request->profile != '' ? $request->profile : '';
        if(Auth::user()->hasRole('admin')){
            $stylist->code = isset($request->code) && $request->code != '' ? $request->code : '';
            $stylist->status_id = isset($request->status_id) && $request->status_id != '' ? $request->status_id : '';
            $stylist->email = isset($request->email) && $request->email != '' ? $request->email : '';
            $stylist->designation_id = isset($request->designation_id) && $request->designation_id != '' ? $request->designation_id : '';
        }
        $stylist->expertise_id = isset($request->expertise_id) && $request->expertise_id != '' ? $request->expertise_id : '';
        $stylist->gender_id = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
        $stylist->blog_url = isset($request->blog_url) && $request->blog_url != '' ? $request->blog_url : '';
        $stylist->facebook_id = isset($request->facebook_id) && $request->facebook_id != '' ? $request->facebook_id : '';
        $stylist->twitter_id = isset($request->twitter_id) && $request->twitter_id != '' ? $request->twitter_id : '';
        $stylist->pinterest_id = isset($request->pinterest_id) && $request->pinterest_id != '' ? $request->pinterest_id : '';
        $stylist->instagram_id = isset($request->instagram_id) && $request->instagram_id != '' ? $request->instagram_id : '';
        try{
            $stylist->save();
        }
        catch(\Exception $e){
            return redirect('stylist/edit/' . $this->resource_id)
                ->withErrors([$e->getMessage()])
                ->withInput();

        }

        return redirect('stylist/view/' . $this->resource_id);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $fields = [
            'name' => 'required|max:255|min:5',
            'age' => 'required|integer|max:50|min:18',
            'expertise_id' => 'required|integer|max:20|min:1',
            'gender_id' => 'required|integer|max:20|min:1',
            'blog_url' => 'url|min:5',
            'facebook_id' => 'string|min:5',
            'twitter_id' => 'string|min:5',
            'pinterest_id' => 'string|min:5',
            'instagram_id' => 'string|min:5',
        ];

        if(Auth::user()->hasRole('admin')){
            $fields = array_merge($fields,[
                'email' => 'required|email|max:255',
                'status_id' => 'required|integer|max:20|min:1',
                'designation_id' => 'required|integer|max:20|min:1',
                'code' => 'required|max:6|min:6',
                ]);
        }
        return Validator::make($data, $fields);
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
