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
use APP\User;
use App\Models\Customer;

class CustomersController extends Controller
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
    public function index(Request $request) {
        
        try {
            $title = 'Customers';
            $url = route('Customers-index');

            $results = self::search($request);

            // ajax search
            if ($request->ajax()) {                
                return view('Customers.partials.listing', compact('results'));
            }

            // on page load            
            return view('Customers.index', compact('results', 'title', 'url'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return redirect()->route('dashboard')->with('error', $errorMsg);
        }
    }

    /**
     * find records into database
     * @param object $request
     * @return row
     */
    public static function search($request = null)
    {

        $query = Customer::query()->where('role', 'User');

        // ajax search
        if (!empty($request->search)) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('name', 'like', "%$request->search%");
                $subquery->orWhere('email', 'like', "%$request->search%");
                $subquery->orWhere('mobile', 'like', "%$request->search%");
            });
        }

        if (isset($request->is_verified) && $request->is_verified != '') {
            $is_verified = (!empty($request->is_verified)) ? true : false;
            $query->where('is_verified', $is_verified);
        }

        if (isset($request->status) && $request->status != '') {
            $status = (!empty($request->status)) ? true : false;
            $query->where('status', $status);
        }

        $sort  = 'id';
        $order = 'DESC';
        if ((isset($request->sort) && $request->sort != '') || (isset($request->order)
            && $request->order != '')) {
            $sort  = $request->sort;
            $order = $request->order;
        }
        $query->orderBy($sort, $order);

        // on page load
        if(isset($request->export)) {
            $results =  $query->get();
        } else {
            $results = $query->paginate(config('siteconstants.PER_PAGE_LIMIT'));
        }

        return $results;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function add() {

        try {
            $title = 'User:Add';
            $url = url('Customers/create');
            $rowInfo = new Customer;

            return view('Customers.create', compact('rowInfo', 'url', 'title'));
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
            $allImages[] = $request->file('image');
            $input = $request->all();
            
            $input['role'] = 'User';
            $input['is_verified'] = true;
            
            unset($input['image']); // remove image from fields
            //dd($input);

            $validator = validator::make($input, [
                'name' => 'required|max:100',
                'mobile' => 'required|max:14|unique:Customers',
                'email' => 'required|max:191|email:rfc,dns|unique:Customers',
                'address' => 'required|max:255',
                'status' => 'required',
                'room_no' => 'required',
            ]);
            
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            // // Password and Confirm Password Validatio
            // if(trim($input['password']) !== trim($input['confirm_password'])) {
            //     return back()->with('error', 'Password and Confirm Password should be same.');
            // }

            // // Hash Password
            // $input['password'] = Hash::make(trim($input['password']));

            if($record = Customer::create($input)) {

                $id = $record->id;

                // upload images
                if($request->hasFile('image')) {

                    // create directory to upload images in it
                    createUserImageDirectories($id);

                    $images = [];
                    foreach ($allImages as $key=>$image) {
                        
                        $image_name = '';
                        $uploadpath = public_path('uploads/users/'.$id.'/');
                        $original_name = $image->getClientOriginalName();

                        if (!$image->isValid() || empty($uploadpath)) {
                            return $image_name;
                        }

                        if ($image->isValid()) {
                            $image_prefix = 'customer_' . rand(0, 999999999) . '_' . date('d_m_Y_h_i_s');
                            $ext = $image->getClientOriginalExtension();
                            $image_name = $image_prefix . '.' . $ext;
                            $image_array[] = $image_name;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->resize(1024, 1024);
                            $image_resize->save(public_path('uploads/users/'.$id.'/' .$image_name));
                            $image_resize->resize(75, 75);
                            $image_resize->save(public_path('uploads/users/'.$id.'/thumb/' .$image_name));
                            $image_resize->resize(480,320);
                            $image_resize->save(public_path('uploads/users/'.$id.'/medium/' .$image_name));
                            $image->move($uploadpath, $image_name);

                            $images[] = $image_name;
                        }
                    }
                    
                    if(!empty($images)) {
                        $input['image'] = $images[0];
                        $record->fill($input)->save();
                    }
                }
                
                return redirect()->route('Customers-index')->with('success', 'Record Saved Successfully');
            } else {
                return redirect()->route('Customers-index')->with('error', 'Error in record saving time please try again');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        
        try {
            $title = 'Customer:Edit';
            $url = url('Customers/update');

            $rowInfo = Customer::where('role', 'User')->findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('Customers-index')->with('error', 'Record not found');
            }

            return view('Customers.create', compact('rowInfo', 'url', 'title'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request) {

        try {
            $allImages[] = $request->file('image');
            $input = $request->all();
            $id = $input['id'];
            unset($input['image']); // remove image from update fields
            unset($input['email']); // remove email from update fields
            
            $record = Customer::where('role', 'User')->findOrFail($id);
            
            if (empty($record)) {
                return redirect()->route('Customers-index')->with('error', 'Record not found');
            }
            
            $oldImage = $record->image;
            
            $validator = validator::make($input, [
                'name' => 'required|max:100',
                'mobile' => 'required|max:14|unique:Customers,mobile,'.$id,
                //'email' => 'required|max:255|email:rfc,dns|unique:Customers,email,'.$id,
                //'image' => 'required|max:255',
                'address' => 'required|max:255',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            if($record->fill($input)->save()) {
                
                // upload images
                if($request->hasFile('image')) {

                    // create directory to upload images in it
                    createUserImageDirectories($id);

                    $images = [];
                    foreach ($allImages as $key=>$image) {
                        
                        $image_name = '';
                        $uploadpath = public_path('uploads/users/'.$id.'/');
                        $original_name = $image->getClientOriginalName();

                        if (!$image->isValid() || empty($uploadpath)) {
                            return $image_name;
                        }

                        if ($image->isValid()) {
                            $image_prefix = 'customer_' . rand(0, 999999999) . '_' . date('d_m_Y_h_i_s');
                            $ext = $image->getClientOriginalExtension();
                            $image_name = $image_prefix . '.' . $ext;
                            $image_array[] = $image_name;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->resize(1024, 1024);
                            $image_resize->save(public_path('uploads/users/'.$id.'/' .$image_name));
                            $image_resize->resize(75, 75);
                            $image_resize->save(public_path('uploads/users/'.$id.'/thumb/' .$image_name));
                            $image_resize->resize(480,320);
                            $image_resize->save(public_path('uploads/users/'.$id.'/medium/' .$image_name));
                            $image->move($uploadpath, $image_name);

                            $images[] = $image_name;
                        }
                    }
                    
                    if(!empty($images)) {
                        $input['image'] = $images[0];
                        $record->fill($input)->save();

                        unlinkOldImages($oldImage, 'Customers/'.$id);
                    }
                }
                
                return redirect()->route('Customers-index')->with('success', 'Record Updated Successfully');
            } else {
                return redirect()->route('Customers-index')->with('error', 'Error in record update, Please try again.');
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
    public function view($id) {
        
        try {
            $title = 'User:View';
            $sub_title = 'User View';
            
            $rowInfo = Customer::where('role', 'User')->findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('Customers-index')->with('error', 'Record not found');
            }

            return view('Customers.view', compact('rowInfo', 'title', 'sub_title'));
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

            $record = Customer::where('role', 'User')->findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }
            
            if ($record->delete()) {

                $result = array(
                    'status' => 'success',
                    'message' => 'Record deleted sucessfully.',
                );
            } else {

                $result = array('status' => 'error', 'message' =>'Error at delete time please try agian.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }

    /**
     * active/deactive the specified resource.
     * @return Response
     */
    public function status(Request $request) {
        
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = Customer::where('role', 'User')->findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }

            $record->status = (empty($record->status)) ? true : false;

            if ($record->save()) {

                $status = ($record->status) ? 
                '<a href="javascript:;" class="btn btn-success btn-circle btn-sm" title="Disable"><i class="fas fa-check"></i></a>'
                : '<a href="javascript:;" class="btn btn-warning btn-circle btn-sm" title="Enable"><i class="fas fa-times"></i></a>';

                $result = array(
                    'status' => 'success',
                    'message' => 'Status updated successfully.',
                    'text' => $status
                );
            } else {

                $result = array('status' => 'error', 'message' => 'Error in status update please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }

    /**
     * Show the form for change password.
     * @return Response
     */
    public function changePassword($id) {

        try {        
            $title = 'User:Change Password';
            $url = url('Customers/update-password');

            $rowInfo = Customer::where('role', 'User')->findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('Customers-index')->with('error', 'Record not found');
            }

            return view('Customers.update-password', compact('rowInfo', 'url', 'title'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Update Password method
     * @param Request $request
     * @return type
     */
  
    /**
     * verify the specified resource.
     * @return Response
     */
    public function verify(Request $request) {
        
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = Customer::where('role', 'User')->findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }

            $record->is_verified = true;

            if ($record->save()) {

                $result = array(
                    'status' => 'success',
                    'message' => 'Status updated successfully.',
                );
            } else {

                $result = array('status' => 'error', 'message' => 'Error in verification please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }
	
}
