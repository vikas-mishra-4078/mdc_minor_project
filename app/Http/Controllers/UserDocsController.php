<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use URL;
use DB;
use Image;
use Hash;
use App\User;
use App\Models\UserDoc;

class UserDocsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($user_id) {
        
        try {
            $title = 'User Docs';
            $results = UserDoc::query()->where('user_id', $user_id)->get();

            // on page load            
            return view('user_docs.index', compact('results', 'title', 'user_id'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function add($user_id) {

        try {
            $title = 'User Doc:Add';
            $url = url('user_docs/create');
            $backUrl = route('user-docs-index', ['user_id' => $user_id]);
            $rowInfo = new UserDoc;
            
            $userInfo = User::findOrFail($user_id);

            if (empty($userInfo)) {
                return back()->with('error', 'User not found');
            }

            $docTypes = UserDoc::getDocTypes();

            return view('user_docs.create', compact('rowInfo', 'url', 'title', 'user_id', 'docTypes', 'userInfo', 'backUrl'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Create a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request) {
        
        try {
            $input = $request->all();
            
            unset($input['doc_file']); // remove file from fields
            //dd($input);

            // upload file
            $isFileUploaded = false;
            if($request->hasFile('doc_file')) {

                // create directory to upload documents in it
                createUserDocsDirectories($input['user_id']);

                $image = $request->file('doc_file');
                
                $image_name = '';
                $uploadpath = public_path('uploads/users/'.$input['user_id'].'/docs/');
                $original_name = $image->getClientOriginalName();

                if (!$image->isValid() || empty($uploadpath)) {
                    return $image_name;
                }

                if ($image->isValid()) {
                    $image_prefix = 'document_' . rand(0, 999999999) . '_' . date('d_m_Y_h_i_s');
                    $ext = $image->getClientOriginalExtension();
                    $image_name = $image_prefix . '.' . $ext;
                    $image_array[] = $image_name;
                    $image_resize = Image::make($image->getRealPath());
                    $image->move($uploadpath, $image_name);

                    $input['doc_file'] = $image_name;
                    if(UserDoc::create($input)) {
                        $isFileUploaded = true;
                    }
                }
            }

            if($isFileUploaded) {
                return redirect()->route('user-docs-index', ['user_id' => $input['user_id']])->with('success', 'Record Saved Successfully');
            } else {
                return redirect()->route('user-docs-index', ['user_id' => $input['user_id']])->with('error', 'Error in record saving time please try again');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }
    
    /**
     * Show the view of specified resource.
     * @return Response
     */
    public function view($user_id, $id) {
        
        try {
            $title = 'User Doc:View';
            $sub_title = 'User Doc View';
            
            $rowInfo = UserDoc::where('user_id', $user_id)->findOrFail($id);

            if (empty($rowInfo)) {
                return back()->with('error', 'Record not found');
            }
            
            $backUrl = route('user-docs-index', ['user_id' => $user_id]);

            return view('user_docs.view', compact('rowInfo', 'title', 'sub_title', 'backUrl'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete(Request $request) {
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = UserDoc::findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }
            
            $oldFile = $record->doc_file;

            if ($record->delete()) {

                $result = array(
                    'status' => 'success',
                    'message' => 'Record deleted sucessfully.',
                );

                unlinkOldImages($oldFile, 'users/'.$record->user_id.'/docs');
            } else {

                $result = array('status' => 'error', 'message' =>'Error at delete time please try agian.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }
	
}
