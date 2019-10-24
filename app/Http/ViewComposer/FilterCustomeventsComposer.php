<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\{Customevent, User};

class FilterCustomeventsComposer
{

    public function compose (View $view)
    {
        $models = Customevent::all()->pluck('model', 'model');
        $types = Customevent::all()->pluck('type', 'type');
        $users = User::find(Customevent::all()->pluck('user_id', 'user_id'))->pluck('name', 'id');
        return $view->with(compact('models', 'types', 'users'));
    }
}