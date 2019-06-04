@extends('layouts.app')

@section('title', 'edit role')

@section('content')
<div class="container">

    <h1>Edit Role '{{ $role->name }}'</h1>

    <form method="POST" action="{{ route('roles.update', ['role' => $role->id]) }}">
        @csrf

        @method("PATCH")

        <h5 class="blue">specify the parameters of the new role:</h5>

        @input(['name' => 'name', 'value' => old('name') ?? $role->name, 'required' => 'required'])

        @input(['name' => 'display_name', 'value' => old('display_name') ?? $role->display_name, 'required' => 'required'])

        @input(['name' => 'description', 'value' => old('description') ?? $role->description, 'required' => 'required'])


        <h5 class="blue">select permissions for new role:</h5>
        <table class="blue_table">

            <tr>

                <?php
                    foreach($permissions as $i => $permission) {

                        if ( empty( $permissions[$i-1] ) or $permissions[$i-1]['group'] !== $permission['group'] ) {
                            echo '</tr><tr><td>group: <strong>' . $permission['group'] . '</strong>
                            </td>';
                        }
                        echo '<td style="text-align: right;">' . $permission['name'] . ': </td>';
                        if ( in_array($permission['id'], $arr_role_permissions) ) {
                            echo '<td><input type="checkbox" name="' . $permission['name'] . '" checked></td>';
                        } else {
                            echo '<td><input type="checkbox" name="' . $permission['name'] . '"></td>';
                        }
                    }
                ?>
                
            </tr>

        </table><br>

        <button type="submit" class="btn btn-primary form-control">edit role!</button>

    </form>

</div>
@endsection
