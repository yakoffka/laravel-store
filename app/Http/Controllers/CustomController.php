<?php

namespace App\Http\Controllers;
use App\Action;

class CustomController extends Controller
{
    /**
     * Create records in table actions.
     *
     * @return string $action->description or false in case of failure
     */
    protected function createAction($model, $dirty_properties, $original, $type)
    {
        $reflect = new \ReflectionClass($model);
        $shortModelName = $reflect->getShortName();
        $description = __($type) . $shortModelName . ' "' . $model->name . '"';

        // details
        $details = [];
        if ( $type !== 'model_delete' ) {
            if ( !$dirty_properties ) {
                return false;
            }
            foreach ( $dirty_properties as $property => $value ) {
                $details[$property] = [
                    $property, !empty($original) ? $original[$property] : '', $model->$property,
                ];
            }
        }

        // create action record
        $action = new Action;

        $action->user_id = auth()->user()->id;
        $action->model = $shortModelName;
        $action->type_id = $model->id;
        $action->type = $type;
        $action->description = $description;
        $action->details = serialize($details);

        if ( $action->save() ) {
            return $description;
        } else {
            return false;
        }
    }
}
