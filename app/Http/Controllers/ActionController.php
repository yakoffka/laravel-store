<?php

namespace App\Http\Controllers;

use App\{Action, Product, User, Order};

class ActionController extends Controller
{

    // расставить разрешения!!!
    public function __construct() {
        // $this->middleware(['auth', 'permission:view_actions']);
        $this->middleware('auth');
    }


    /**
     * Display a listing of the action all users.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( auth()->user()->cannot('view_actions'), 403 );
        $actions = Action::orderBy('created_at', 'desc')->paginate();
        return view('dashboard.adminpanel.actions.index', compact('actions'));
    }
    /**
     * Display a listing of the action all users.
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        abort_if ( auth()->user()->cannot('view_actions'), 403 );
        return view('dashboard.adminpanel.actions.show', compact('action'));
    }

}
