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
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $actions = Action::all()->sortByDesc('created_at')->slice(0, config('custom.num_last_actions'));// last 50!
        $categories = Category::all()->where('parent_id', '=', 1)->where('id', '>', 1);
        $videobackground = true;
        return view('home', compact('categories', 'videobackground'));
    }
}
