<?php

namespace App\Http\Controllers;

use App\Customevent;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class CustomeventController extends Controller
{
    /**
     * CustomeventController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the events.
     * @return Factory|View
     */
    public function index()
    {
        abort_if ( auth()->user()->cannot('view_customevents'), 403 );
        $customevents = Customevent::orderBy('created_at', 'desc')
            ->filter(request())
            ->paginate();
        $appends = request()->query->all();
        return view('dashboard.adminpanel.customevents.index', compact('customevents', 'appends'));
    }

    /**
     * Display a listing of the event all users.
     * @param Customevent $customevent
     * @return Factory|View
     */
    public function show(Customevent $customevent)
    {
        abort_if ( auth()->user()->cannot('view_customevents'), 403 );
        return view('dashboard.adminpanel.customevents.show', compact('customevent'));
    }
}
