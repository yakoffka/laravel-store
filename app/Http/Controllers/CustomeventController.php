<?php

namespace App\Http\Controllers;

use App\Customevent;

class CustomeventController extends Controller
{

    // расставить разрешения!!!
    public function __construct() { $this->middleware('auth'); }


    /**
     * Display a listing of the events.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( auth()->user()->cannot('view_customevents'), 403 );
        // $customevents = Customevent::orderBy('created_at', 'desc')->paginate();
        $customevents = Customevent::orderBy('created_at', 'desc')
            ->filter(request())
            ->paginate();
        $appends = request()->query->all();
        return view('dashboard.adminpanel.customevents.index', compact('customevents', 'appends'));
    }
    /**
     * Display a listing of the event all users.
     * @return \Illuminate\Http\Response
     */
    public function show(Customevent $customevent)
    {
        abort_if ( auth()->user()->cannot('view_customevents'), 403 );
        return view('dashboard.adminpanel.customevents.show', compact('customevent'));
    }

}
