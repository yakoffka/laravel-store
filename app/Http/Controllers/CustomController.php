<?php

namespace App\Http\Controllers;
use App\Event;

class CustomController extends Controller
{
    /**
     * Create records in table events.
     *
     * @return string $event->description or false in case of failure
     */
    protected function createEvents($model, $dirty_properties, $original, $type)
    {
        // dd($model->getAttributes());
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
        if ( $type === 'model_delete' ) {
            foreach ( $model->getAttributes() as $property => $value ) {
                if ( !empty(!empty($original) ?? $original[$property]) or !empty($model->$property) ) {
                    $details[$property] = [
                        $property, $value, '-',
                    ];
                }
            }
        } else {
            foreach ( $dirty_properties as $property => $value ) {
                if ( !empty(!empty($original) ?? $original[$property]) or !empty($model->$property) ) {
                    $details[$property] = [
                        $property, !empty($original[$property]) ? $original[$property] : '-', $model->$property ?? '-',
                    ];
                }
            }
        }

        // create event record
        $event = new Event;

        if ( auth()->user() ) {
            $event->user_id = auth()->user()->id;
        }
        $event->model = $shortModelName;
        $event->type_id = $model->id;
        $event->type = $type;
        $event->description = $description;
        $event->details = serialize($details);

        if ( $event->save() ) {
            return 'success_' . $description;
        } else {
            return false;
        }
    }
}
