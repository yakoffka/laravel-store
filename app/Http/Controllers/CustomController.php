<?php

namespace App\Http\Controllers;
use App\Customevent;

class CustomController extends Controller
{
    /**
     * Create records in table events.
     *
     * @return string $customevent->description or false in case of failure
     */
    protected function createCustomevent($model, $dirty_properties, $original, $type, $additional_description = '')
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
        } elseif ( !empty($dirty_properties) ) {
            foreach ( $dirty_properties as $property => $value ) {
                if ( !empty(!empty($original) ?? $original[$property]) or !empty($model->$property) ) {
                    $details[$property] = [
                        $property, !empty($original[$property]) ? $original[$property] : '-', $model->$property ?? '-',
                    ];
                }
            }
        }

        // create event record
        $customevent = new Customevent;

        if ( auth()->user() ) {
            $customevent->user_id = auth()->user()->id;
        }
        $customevent->model = $shortModelName;
        $customevent->model_id = $model->id;
        $customevent->type = $type;
        $customevent->description = $description;
        $customevent->details = serialize($details);
        $customevent->additional_description = $additional_description;

        if ( $customevent->save() ) {
            return 'success_' . $description;
        } else {
            return false;
        }
    }
}
