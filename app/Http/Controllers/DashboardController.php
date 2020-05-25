<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;


class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $info['users'] = Customer::where('role', 'User')->count();
        

        return view('dashboard/index', compact('info'));
    }
}
