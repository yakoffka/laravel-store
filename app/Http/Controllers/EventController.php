<?php

namespace App\Http\Controllers;

use App\Event;

class EventController extends Controller
{

    // расставить разрешения!!!
    public function __construct() { $this->middleware('auth'); }


    /**
     * Display a listing of the events.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( auth()->user()->cannot('view_events'), 403 );
        // $events = Event::orderBy('created_at', 'desc')->paginate();
        $events = Event::orderBy('created_at', 'desc')
            ->filter(request())
            ->paginate();
        $appends = request()->query->all();

        return view('dashboard.adminpanel.events.index', compact('events', 'appends'));
    }
    /**
     * Display a listing of the event all users.
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        abort_if ( auth()->user()->cannot('view_events'), 403 );
        return view('dashboard.adminpanel.events.show', compact('event'));
    }

}
