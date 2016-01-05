<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use App\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/look/list';

    protected $redirectAfterLogout = '/auth/login';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Commented the below code as the logout was not happening when the below code was on.
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Calls AuthenticatesAndRegistersUsers methods like getLogin, postLogin, getRegister, postRegister
     *
     */
    public function index(Request $request, $action)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);

        if(Auth::check() && $method != 'getLogout'){
            return redirect(property_exists($this, 'redirectPath') ? $this->redirectPath : '/');
        }
        
        return $this->$method($request->method() == 'POST' ? $request : null);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:stylists',
            'password' => 'required|confirmed|min:6',
            'toc' => 'required'
        ]);
    }

    /**
     * Create a new stylist instance after a valid registration.
     *
     * @param  array  $data
     * @return Stylist
     */
    protected function create(array $data)
    {
        $stylist = Stylist::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $stylist_role = Role::where('name','stylist')->firstOrFail();

        $stylist->attachRole($stylist_role);

        return $stylist;
    }
}
