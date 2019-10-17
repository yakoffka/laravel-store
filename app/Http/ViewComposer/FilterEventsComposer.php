<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\{Event, User};

class FilterEventsComposer
{

    public function compose (View $view)
    {
        $models = Event::all()->pluck('model', 'model');
        $types = Event::all()->pluck('type', 'type');
        $users = User::find(Event::all()->pluck('user_id', 'user_id'))->pluck('name', 'id');
        return $view->with(compact('models', 'types', 'users'));
    }
}