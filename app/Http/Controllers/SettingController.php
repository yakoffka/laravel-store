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
        abort_if ( !Auth::user()->can('edit_settings'), 403 );
        $settings = Setting::all();
        $groups = Setting::pluck('name_group', 'group');
        return view('dashboard.adminpanel.settings.index', compact('settings', 'groups'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        abort(404);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        abort(404);
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
        if ( $setting->type === 'checkbox' ) {
            request()->validate([
                'value' => 'string',
            ]);
            $value = !empty(request('value')) ? 1 : 0;
    
        } elseif( $setting->type === 'select' or $setting->type === 'text' ) {
            request()->validate([
                'value' => 'required|string',
            ]);
            $value = request('value');

        } elseif( $setting->type === 'email' ) {
            $arr_bcc = [];
            for ( $i = 1; $i <= config('mail.max_quantity_add_bcc'); $i++ ) {
                $arr_validate['value_email' . $i] = 'nullable|email';
                if ( request('value_email' . $i )) {
                    $arr_bcc[] = request("value_email$i");
                }
            }
            request()->validate($arr_validate);

            $value = implode(', ', $arr_bcc);

        } else {
            return back()->withErrors('недопустимый тип пункта настроек');
        }

        $setting->update([
            'value' => $value,
        ]);

        return redirect('/settings/' . '#setting_' . $setting->name);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        abort(404);
    }
}
