@extends('layouts.app')

@section('title')
create role
@endsection

@section('content')
<div class="container">

    <h1>Create new role</h1>

    <form method="POST" action="{{ route('roles.store') }}">
        @csrf

        <h5 class="blue">specify the parameters of the new role:</h5>
        <div class="form-group">
            <!-- <label for="name">name</label> -->
            <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{ old('name') ?? 'name_new_roles' }}" required>
        </div>

        <div class="form-group">
            <!-- <label for="display_name">display_name</label> -->
            <input type="text" id="display_name" name="display_name" class="form-control" placeholder="display_name" value="{{ old('display_name') ?? 'Display Name New Roles' }}" required>
        </div>

        <div class="form-group">
            <!-- <label for="description">description</label> -->
            <input type="text" id="description" name="description" class="form-control" placeholder="description" value="{{ old('description') ?? 'Description New Roles' }}" required>
        </div>


        <h5 class="blue">select permissions for new role:</h5>
        <table class="blue_table">

            <tr>
                <?php
                    foreach($permissions as $i => $permission) {

                        if ( empty( $permissions[$i-1] ) or $permissions[$i-1]['group'] !== $permission['group'] ) {
                            echo '</tr><tr><td>group: <strong>' . $permission['group'] . '</strong>
                            </td>';
                        }
                        echo '<td style="text-align: right;">' . $permission['name'] . ': </td><td><input type="checkbox" name="' . $permission['name'] . '"></td>';
                    }
                ?>
            </tr>

        </table><br>

        <button type="submit" class="btn btn-primary form-control">Create new role!</button>

    </form>

</div>
@endsection
