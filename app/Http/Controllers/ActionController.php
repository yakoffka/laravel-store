<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;

class ActionController extends Controller
{

    // расставить разрешения!!!
    public function __construct() { $this->middleware('auth'); }


    /**
     * Display a listing of the action all users.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( auth()->user()->cannot('view_actions'), 403 );
        // $actions = Action::orderBy('created_at', 'desc')->paginate();
        $actions = Action::orderBy('created_at', 'desc')
            ->filter(request())
            ->paginate();
        $appends = request()->query->all();

        return view('dashboard.adminpanel.actions.index', compact('actions', 'appends'));
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
