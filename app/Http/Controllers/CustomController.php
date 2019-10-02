<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;

class CustomController extends Controller
{
    /**
     * Create records in table actions.
     *
     * @return string $action->description or false in case of failure
     */
    protected function createAction($model, $dirty_properties, $original)
    {
        $reflect = new \ReflectionClass($model);
        $shortName = $reflect->getShortName();

        $description = 'Редактирование модели ' . $shortName . ' "' . $model->name . '"';
        $details = [];
        foreach ( $dirty_properties as $property => $value ) {
            $details[$property] = [
                $property, $original[$property], $model->$property,
            ];
        }

        // create action record
        $action = new Action;

        $action->user_id = auth()->user()->id;
        $action->type = $shortName;
        $action->type_id = $model->id;
        $action->action = 'update';
        $action->description = $description;
        $action->details = serialize($details);

        if ( $action->save() ) {
            return $description;
        } else {
            return false;
        }
    }
}
