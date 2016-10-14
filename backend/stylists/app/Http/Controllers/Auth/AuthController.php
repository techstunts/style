<?php

namespace App\Http\Controllers\Auth;

use App\Models\Enums\Designation;
use App\Models\Enums\Gender;
use App\Models\Lookups\Expertise;
use App\Role;
use App\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Models\Validator\BlockedStylistsValidator;

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

    const BLOCKED_USER_MESSAGE = "User is blocked, please contact administrator.";

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
     * Login validation for blocked stylist
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = []){
        $rules["email"] .= "|blocked_stylist";
        $messages['blocked_stylist'] = self::BLOCKED_USER_MESSAGE;
        parent::validate($request, $rules, $messages, $customAttributes);
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
            'status_id' => BlockedStylistsValidator::BLOCKED_STATUS_ID,
            'expertise_id' => \App\Models\Enums\Expertise::Casuals,
            'gender_id' => Gender::Female,
            'designation_id' => Designation::CertifiedStylist,
        ]);

        $stylist_role = Role::where('name','stylist')->firstOrFail();

        $stylist->attachRole($stylist_role);

        return $stylist;
    }

    public function postRegister(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $this->create($request->all());

        return redirect('auth/thankyou')->with('status', 'User created!');
    }

    public function getThankyou(){
        if(Session::has('status')){
            return view('auth.thankyou');
        }
        return redirect($this->redirectAfterLogout);
    }
}
