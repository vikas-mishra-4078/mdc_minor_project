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
use Helper;

class HomepageController extends Controller
{

	public function pages(Request $request){
		$input = $request->all();
		$page_slug = $input['page_slug'];
		$pages = Pages::where('title','=',$page_slug)->first();
		return response()->json(['title'=>'1','msg'=>'Page description','page_data'=>$pages], $this->successStatus);
	}	
	
}