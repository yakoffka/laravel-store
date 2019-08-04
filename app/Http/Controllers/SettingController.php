<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Auth;

class SettingController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( !Auth::user()->can('view_settings'), 403 );
        $settings = Setting::all();
        $groups = Setting::pluck('group')->unique();
        return view('settings.index', compact('settings', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if ( !Auth::user()->can('create_settings'), 403 );
        return __method__;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if ( !Auth::user()->can('create_settings'), 403 );
        return __method__;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        abort_if ( !Auth::user()->can('view_settings'), 403 );
        return __method__;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        abort_if ( !Auth::user()->can('edit_settings'), 403 );
        return __method__;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Setting $setting)
    {
        abort_if ( !Auth::user()->can('edit_settings'), 403 );

        if ($setting->type == 'select') {
            $validator = request()->validate([
                'value' => 'required|string',
            ]);

            $value = request('value');
    
        } elseif($setting->type == 'email') {

            $arr_bcc = [];
            for ( $i = 1; $i <= config('mail.max_quantity_add_bcc'); $i++ ) {
                $arr_validate['value_email' . $i] = 'nullable|email';
                if ( request('value_email' . $i )) {
                    $arr_bcc[] = request("value_email$i");
                }
            }
            $validator = request()->validate($arr_validate);

            $value = implode(', ', $arr_bcc);

        } else {
            return back()->withErrors('недопустимый тип пункта настроек');
        }

        $setting->update([
            'value' => $value,
        ]);

        return back();
        return __method__;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        abort_if ( !Auth::user()->can('delete_settings'), 403 );
        return __method__;
    }
}
