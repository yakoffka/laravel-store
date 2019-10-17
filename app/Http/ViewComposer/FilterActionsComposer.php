<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\{Action, User};

class FilterActionsComposer
{

    public function compose (View $view)
    {
        $models = Action::all()->pluck('model', 'model');
        $types = Action::all()->pluck('type', 'type');
        // $users = Action::all()->pluck('user_id', 'user_id');
        $users = User::find(Action::all()->pluck('user_id', 'user_id'))->pluck('name', 'id');
        // dd($models, $types, $users, $users2);
        return $view->with(compact('models', 'types', 'users'));
    }
}