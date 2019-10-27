<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['home',]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        if ( auth()->user() and auth()->user()->can('view_adminpanel') ) {
            return view('dashboard.adminpanel.welcome');
        } else {
            return view('dashboard.userpanel.welcome');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $categories = Category::all()
            ->where('parent_id', '=', 1)
            ->where('id', '>', 1)
            ->where('seeable', '=', 'on')
            ->where('parent_seeable', '=', 'on'); // getParentSeeableAttribute

        $videobackground = true;

        return view('home', compact('categories', 'videobackground'));
    }

}
