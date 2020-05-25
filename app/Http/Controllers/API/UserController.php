<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use App\User;
use Validator;
use URL;
use DB;
use Mail;
use Config;

class UserController extends Controller
{

	public $successStatus = 200;

	/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'role' => request('role')])){ 
            $user = Auth::user();
			if($user->is_deleted=='1'){
				return response()->json(['status'=>'0','msg'=>'Your Account has been blocked.','error'=>'Unauthorised'],  $this->successStatus); 
			}
			if($user->is_verified=='0'){
				return response()->json(['status'=>'0','msg'=>'Your Account Not Verified.','error'=>'Unauthorised'],  $this->successStatus); 
			}
			if($user->status=='0'){
				return response()->json(['status'=>'0','msg'=>'Your Account Not Active.','error'=>'Unauthorised'],  $this->successStatus); 
			}
			
			$user['profile_pic'] = userProfile($user->id);
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['user'=>$user,'status'=>'1','msg'=>'You are successfully logged in.','success' => $success], $this->successStatus); 
        } 
        else{ 
            return response()->json(['status'=>'0','msg'=>'Invalid username or password','error'=>'Unauthorised'],  $this->successStatus); 
        } 
    }

}
		