<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Role;
use App\User;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $config = DB::table('configurations')->get()->first();
        return view('landing.home', compact('config'));
    }
    public function terms_and_conditions() {
        $config = DB::table('configurations')->get()->first();
        return view('landing.termsandconditions', compact('config'));
    }
    public function privacy_policy() {
        $config = DB::table('configurations')->get()->first();
        return view('landing.privacypolicy', compact('config'));
    }
    public function about_us() {
        $config = DB::table('configurations')->get()->first();
        return view('landing.aboutus', compact('config'));
    }
    
    public function forgetsuccess() {
        \Auth::logout();
        return view('auth.passwords.forgetsuccess');
    }
    public function contact_form(Request $request) {
//        dd('m here');
        $requestData = $request->all();
        \App\Contact::create($requestData);
        return redirect()->back()->with('message', 'Message Sent. Will contact you soon.');
    }
}
