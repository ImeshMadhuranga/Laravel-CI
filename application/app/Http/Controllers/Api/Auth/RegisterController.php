<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use App\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Mail\verifyEmail;
use App\Mail\WelcomeUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Validator;
use DB;

use App\Http\Controllers\Api\VerificationController;
use Exception;

class RegisterController extends Controller
{
    use IssueTokenTrait;

	private $client;

	public function __construct(){
		$this->client = Client::find(2);
	}

    public function register(Request $request){

        $config = Setting::first();
        $validation = [];

    	if($config->mobile_enable == 1){
    	    
    	    $validation['mobile'] = 'required|numeric';
    	}
        try {
            $this->validate($request, array_merge($validation, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'user_img' => 'required|string|min:5'
            ]));
        }
        catch(ValidationException $error) {
            return response([
                'error' => $error->errors()
            ], 404);
        }     
    	
    	
    	
    	
        if($config->verify_enable == 0)
        {
            $verified = \Carbon\Carbon::now()->toDateTimeString();
        }
        else
        {
            $verified = NULL;
        }

    	$user = User::create([
    	    
    		'fname' => request('name'),
    		'email' => request('email'),
            'email_verified_at'  => $verified,
            'mobile' => $config->mobile_enable == 1 ? request('mobile') : NULL,
    		'password' => bcrypt(request('password')),
            'user_img' => @file_get_contents(public_path() . '/images/user_img/' . request('user_img')) ? request('user_img') : NULL

    	]);
        $user->assignRole('User');
        if($config->verify_enable == 0)
        {

$response = $this->issueToken($request, 'password');
$update_response = array_merge(['role'=> 'user'], json_decode($response->content(), true));
$response->setContent(json_encode($update_response));
return $response;

        }
        else
        {
            if($verified != NULL)
            {

$response = $this->issueToken($request, 'password');
$update_response = array_merge(['role'=> 'user'], json_decode($response->content(), true));
$response->setContent(json_encode($update_response));
return $response;

            }
            else
            {
                try {
                    $user->sendEmailVerificationNotificationViaAPI();
                    Mail::to(request('email'))->send(new WelcomeUser($user));
                    return response()->json(['error' => 'Verify your email'], 402);
                }
                catch(\Exception $e){
                    return response()->json(['error' => 'Eamil send failed. Please resend the code'], 402);
                }
            }
            
        }
        if($config->w_email_enable == 1){
        try{
                Mail::to(request('email'))->send(new WelcomeUser($user));
                return response()->json(['success' => 'Registration done.'], 200);
            }
            catch(\Exception $e){
                return response()->json(['error' => 'Registration done. Mail cannot be sent'], 201);
            }
        }
    }

    public function verifyemail(Request $request){
        $user = User::where(['email' => $request->email, 'verifyToken' => $request->token])->first();
        if($user){
            $user->status=1; 
            $user->verifyToken=NULL;
            $user->save();
            Mail::to($user->email)->send(new WelcomeUser($user));
            return $this->issueToken($request, 'password');
        }else{
            return response()->json('user not found', 401);
        }
    }

    public function imageUpload(Request $request) {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Secret Key is required'], 404);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['error' => 'Invalid Secret Key !'], 404);
        }
        try {
            $request->validate([
                'user_img' => 'required|image|mimes:png,jpg,jpeg',
            ]);
        }
        catch(ValidationException $error) {
            return response([
                'error' => $error->errors()
            ], 404);
        }     

        if ($file = $request->file('user_img')) {
            try {
                $name = time() . $file->getClientOriginalName();
                $file->move('images/user_img', $name);
                $input['file'] = $name;
                $input['url'] = url('/images/user_img/' . $name);
                return response()->json($input, 200);
            }
            catch( Exception $e) {
                return response()->json(['error' => $e->getMessage()], $e->getCode());
            }   
           
        }
    }

    public function imageRemove(Request $request) {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Secret Key is required'], 404);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['error' => 'Invalid Secret Key !'], 404);
        }
        try {
            $request->validate([
                'user_img' => 'required|string',
            ]);
        }
        catch(ValidationException $error) {
            return response([
                'error' => $error->errors()
            ], 404);
        } 
        try {
            $image_file = @file_get_contents(public_path() . '/images/user_img/' . request('user_img'));
            if ($image_file) {
                unlink(public_path() . '/images/user_img/' . request('user_img'));
                return response()->json([
                    'success' => request('user_img') . ' removed successfully'
                ], 200);
            }
        }
        catch(Exception $e) {
            return response()->json(
                ['error' => $e->getMessage()], $e->getCode()
            );
        }
    }

}