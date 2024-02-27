<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use App\User;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('resend', 'verifybyapi');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    
    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {

            return response(['message'=>'Already verified']);
        }
        //dd($request->user());
        try {
            $uni_col = array(User::pluck('code'));
            do {
            $code = str_random(5);
            } while (in_array($code, $uni_col));
            $requestUser = $request->user();
            $requestUser->code = $code;
            $request->user()->sendEmailVerificationNotificationViaAPI($code);
            $requestUser->save();
            return response(['message' => 'Email Sent', 'code' => $code]);
        }
        catch(Exception $error) {
            return response(['error' => 'Error when send message. try again later']);
        }
    }


    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verifybyapi(Request $request)
    { 
        try {
            if ($request->user()->hasVerifiedEmail()) {

                return response(['message'=>'Already verified']);
    
                // return redirect($this->redirectPath());
            }
    
            if( ! $request->code || ! $request->user())
            {
                return response()->json(['error' =>' code required or You are logged out. try again'], 449);
            }
    
            $user = User::whereEmail($request->user()->email)->whereCode($request->code)->first();
    
            if( !$user)
            {            
                return response()->json(['error' => 'Code not found'], 401);
            }
            else{
                $user->code = null;
                $user->save();
                if ($request->user()->markEmailAsVerified()) {
                    event(new Verified($request->user()));
                }
                return response()->json(['success' => 'User verified successfully'], 200);
            }
        }
        catch(Exception $error) {
            return response(['error' => $error->getMessage()], 401);
        }
        
    }

   
}