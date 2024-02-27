<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use App\User;
use App\Setting;
use Exception;
use Mail;
use Validator;
use Hash;
use Illuminate\Validation\ValidationException;
use Socialite;

class LoginController extends Controller
{

    use IssueTokenTrait;

	private $client;

	public function __construct(){
		$this->client = Client::find(2);
	}

    public function login(Request $request)
    {

        
        try {
            $this->validate($request, [
                'email' => 'required|email|min:5',
                'password' => 'required|min:6'
            ]);
        }
        catch(ValidationException $error) {
            return response([
                'error' => $error->errors()
            ], 404);
        }        
        $authUser = User::where('email', $request->email)->first();
        if(isset($authUser) && $authUser->status == 0){
            return response()->json(['error' => 'Blocked User'], 401); 
        }
        else{
            
            $setting = Setting::first();

            if(isset($authUser))
            {
                $verified = ($authUser->email_verified_at != NULL) ? 1 : 0;
                if($setting->verify_enable == 0)
                {
                    if(isset($request->role))
                    {

                        if($authUser->role == 'instructor' || $authUser->role == 'user')
                        {
                            $response = $this->issueToken($request, 'password');
                            $update_response = array_merge(['role'=> $authUser->role, 'verified' => $verified],json_decode($response->content(),true));
                            $response->setContent(json_encode($update_response));
                            return $response; 
                        }
                        else{
                            return response()->json(['error' => 'Invalid Login'], 404);  
                        }
                    }
                    else{
                        $response = $this->issueToken($request, 'password');
                        $update_response = array_merge(['role'=> $authUser->role, 'verified' => $verified],json_decode($response->content(),true));
                        $response->setContent(json_encode($update_response));
                        return $response; 
                    }
                }
                else
                {
                    if($authUser->email_verified_at != NULL)
                    {
                        if(isset($request->role))
                        {
                            if($authUser->role == 'instructor')
                            {
                                $response = $this->issueToken($request, 'password');
                                $update_response = array_merge(['role'=> $authUser->role, 'verified' => 1],json_decode($response->content(),true));
                                $response->setContent(json_encode($update_response));
                                return $response; 
                            }
                            else{
                                return response()->json(['error' => 'Invalid Login'], 404);  
                            }
                        }
                        else{
                            $response = $this->issueToken($request, 'password');
                            $update_response = array_merge(['role'=> $authUser->role, 'verified' => 1],json_decode($response->content(),true));
                            $response->setContent(json_encode($update_response));
                            return $response; 
                        }
                        
                    }
                    else
                    {
                        return response()->json(['error' => 'Verify your email'], 402); 
                    }
                }

            }
            else{

                return response()->json(['error' => 'invalid User login'], 401);

            }

            
            
        }

    }

    public function fblogin(Request $request){

        $this->validate($request, [
            'email' => 'required',
            'name' => 'required',
            'code' => 'required',
            'password' => ''
        ]);
        $authUser = User::where('email', $request->email)->first();
        if($authUser){
            $authUser->facebook_id = $request->code;
            $authUser->fname = $request->name;
            $authUser->save();
            if(isset($authUser) &&  $authUser->status == '0'){
                return response()->json('Blocked User', 401); 
            }
             else{
                   if (Hash::check('password', $authUser->password)) {

                        return $response = $this->issueToken($request,'password');

                } else {
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }

            }
        }
        else{

            $verified = \Carbon\Carbon::now()->toDateTimeString();

            $user = User::create([
                'fname' =>  request('name'),
                'email' => request('email'),
                'password' => Hash::make($request->password !='' ? $request->password : 'password'),
                'facebook_id' => request('code'),
                'status'=>'1',
                'email_verified_at'  => $verified
            ]);
            
            return $this->issueToken($request, 'password');
        }
    }

    public function googlelogin(Request $request){


        $this->validate($request, [
            'email' => 'required',
            'name' => 'required',
            'uid' => 'required',
            'password' => ''
        ]);

        $authUser = User::where('email', $request->email)->first();

        if($authUser){

            $authUser->google_id = $request->uid;
            $authUser->fname = $request->name;
            $authUser->save();

            if(isset($authUser) &&  $authUser->status == '0'){
                return response()->json('Blocked User', 401); 
            }
            else{
                if (Hash::check('password', $authUser->password)) {
                    return $response = $this->issueToken($request,'password');

                } else {
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }

            }
        }
        else{
            $verified = \Carbon\Carbon::now()->toDateTimeString();
            $user = User::create([
                'fname' =>  request('name'),
                'email' => request('email'),
                'password' => Hash::make($request->password !='' ? $request->password : 'password'),
                'google_id' => request('uid'),
                'status'=>'1',
                'email_verified_at'  => $verified
            ]);
           
            return $response = $this->issueToken($request, 'password');
        }
    }



    public function refresh(Request $request){
    	$this->validate($request, [
    		'refresh_token' => 'required'
    	]);

    	return $this->issueToken($request, 'refresh_token');
    }
    
    public function forgotApi(Request $request)
    { 
        $user = User::whereEmail($request->email)->first();
        if($user){

            $uni_col = array(User::pluck('code'));
            do {
              $code = str_random(5);
            } while (in_array($code, $uni_col));            
            try{
                $config = Setting::findOrFail(1);
                $logo = $config->logo;
                $email = $config->wel_email;
                $company = $config->project_title;
                Mail::send('forgotemail', ['code' => $code, 'logo' => $logo, 'company'=>$company], function($message) use ($user, $email) {
                    $message->from($email)->to($user->email)->subject('Reset Password Code');
                });
                $user->code = $code;
                $user->save();
                return response()->json(['success' => "Password reset email send successfully. Please check your email", 'code' => $code], 200);// @todo remove code from api response when mail setup done.
            }
            catch(\Swift_TransportException $e){
                return response()->json(['error' => 'Mail Sending Error'], 400);
            }
        }
        else{          
            return response()->json(['error'=>'user not found'], 401);  
        }
    }

    public function verifyApi(Request $request)
    { 
        if( ! $request->code || ! $request->email)
        {
            return response()->json(['error' =>'email and code required'], 449);
        }

        $user = User::whereEmail($request->email)->whereCode($request->code)->first();

        if( !$user)
        {            
            return response()->json(['error' => 'not found'], 401);
        }
        else{
            $user->code = null;
            $user->save();
            return response()->json(['success' => 'User verified successfully'], 200);
        }
    }

    public function resetApi(Request $request)
    { 
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:6',
                'email' => 'required|email|exists:users,email'
            ]);
            if(!$validator->fails()) {
                $user = User::whereEmail($request->email)->first();

                if($user){
        
                    $user->update(['password' => bcrypt($request->password)]);
        
                    $user->save(); 
                    
                    return response()->json(['success' => 'Password update successfully'], 200);
                }
                else{          
                    return response()->json(['error' =>'User not found'], 401);
                }
            }
            else {
                return response()->json(['error' => $validator->validate()], 400);
            }
        }
        catch(ValidationException $error) {
            return response([
                'error' => $error->errors()
            ], 400);
        }    
        
    }

    public function logoutApi()
    {
return response()->json(['message' => 'logged out successfully']);

        try {
            $token = Auth::user()->token();
            $token->revoke();
            $response = ['message' => 'You have been successfully logged out!'];
            return response()->json($response, 200);
        }
        catch(Exception $error) {

return response()->json(['error' => $error->getMessage()], 400);

        }
       

    }

    public function redirectToblizzard_sociallogin($provider){
        return Socialite::driver($provider)->stateless()->redirect();
    }


    public function blizzard_sociallogin(Request $request, $provider)
    {

        if(!$request->has('code') || $request->has('denied')) {
            return response()->json('Code not found !', 401); 
        }


        try{

           return Socialite::driver($provider)->stateless()->user();

        }catch(\Exception $e){

           return response()->json($e->getMessage(),401);
        }

        $authUser = $this->findOrCreateUser($user, $provider);
        if(isset($authUser) &&  $authUser->status == '0'){
            return response()->json('Blocked User', 401); 
        }

        else{

             $token = $authUser
                     ->createToken(config('app.name') . ' Password Grant Client')
                     ->accessToken;

            return response()->json(['accessToken' => $token], 200); 



        }

        


        // return $token
    }

    public function findOrCreateUser($user, $provider)
    {
        if($user->email == Null){
            $user->email = $user->id.'@facebook.com';
        }

        $authUser = User::where('email', $user->email)->first();
        $providerField = "{$provider}_id";

        if($authUser){
            if ($authUser->{$providerField} == $user->id) {
                $authUser->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
                $authUser->save();
                return $authUser;
            }
            else{
                $authUser->{$providerField} = $user->id;
                $authUser->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
                $authUser->save();
                return $authUser;
            }
        }

        if($user->avatar != NULL && $user->avatar != ""){
            $fileContents = @file_get_contents($user->getAvatar());
            $user_profile = File::put(public_path() . '/images/user_img/' . $user->getId() . ".jpg", $fileContents);
            $name = $user->getId() . ".jpg";
        }
        else {
            $name = NULL;
        }

        $verified = \Carbon\Carbon::now()->toDateTimeString();

        $setting = Setting::first();

        $auth_user = User::create([
            'fname'              => $user->name,
            'email'              => $user->email,
            'user_img'           => $name,
            'email_verified_at'  => $verified,
            'password'           => Hash::make('password'),
            $providerField       => $user->id,
        ]);


        if($setting->w_email_enable == 1){
            try{
               
                Mail::to($auth_user['email'])->send(new WelcomeUser($auth_user));
               
            }
            catch(\Swift_TransportException $e){

            }
        }



        return $auth_user;



    }
    
}