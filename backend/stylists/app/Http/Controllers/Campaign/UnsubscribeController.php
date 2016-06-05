<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 05/06/16
 * Time: 1:05 PM
 */

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\MailerType;
use Redirect;
use Illuminate\Http\Request;
use App\Unsubscription;
use Validator;
use Carbon\Carbon;

class UnsubscribeController extends Controller{

    public function index(Request $request, $action="index")
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        return $this->$method($request);
    }

    public function getIndex(Request $request)
    {
        return view('campaign.unsubscribe.index', ['email'=> $request->e, 'reasons' =>Unsubscription::getReasons()]);
    }

    public function postSave(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()) return view('campaign.unsubscribe.index', ['email'=> $request->email, 'reasons' =>Unsubscription::getReasons()])->withErrors($validator);

        $unsubscribe = Unsubscription::where('email', $request->email)->first();
        
        if($unsubscribe){
            $unsubscribe->reason =  substr($request->unsubscribe_reason, 0, 50);
            $unsubscribe->mailer_type_id = MailerType::CAMPAIGN_MAILER_TYPE_ID;
            $unsubscribe->updated_at = Carbon::now();
            $unsubscribe->save();
        }else{
            Unsubscription::create([ 'email' => $request->email,
                                     'mailer_type_id' => MailerType::CAMPAIGN_MAILER_TYPE_ID,
                                     'reason' => substr($request->unsubscribe_reason, 0, 50)]);
        }

        return view('campaign.unsubscribe.save', ['email'=> $request->email]);
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, ['email' => 'required|email|min:4|max:50',
                                            'unsubscribe_reason' =>'required|min:4|max:50']);

        $validator->after(function($validator) use($data) {
            if($data['unsubscribe_reason'] == Unsubscription::REASON_OTHER && empty(trim($data['unsubscribereason_others'])))
            {
                $validator->getMessageBag()->add('unsubscribe_reason', 'Reason value must not empty.');
            }
        });
        return $validator;
    }

} 
