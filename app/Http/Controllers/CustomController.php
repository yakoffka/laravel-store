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
        if ( $model->name ) {
            $description = ' "' . $model->name . '"';
        } else {
            $description = ' #' . $model->id;
        }
        $description = __($type) . __($shortModelName . '_model') . $description;

        // details
        $details = [];
        if ( $type !== 'model_delete' ) {
            if ( !$dirty_properties ) {
                return false;
            }
            foreach ( $dirty_properties as $property => $value ) {
                if ( !empty(!empty($original) ?? $original[$property]) or !empty($model->$property) ) {
                    $details[$property] = [
                        $property, !empty($original[$property]) ? $original[$property] : '-', $model->$property ?? '-',
                    ];
                }
            }
        }

        // create action record
        $action = new Action;

        if ( auth()->user() ) {
            $action->user_id = auth()->user()->id;
        }
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
