@extends('layouts.app')

@section('title', 'edit role')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            {{ Breadcrumbs::render('roles_edit', $role) }}
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Edit Role '{{ $role->name }}'</h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col col-sm-10 pr-0">

            <form method="POST" action="{{ route('roles.update', ['role' => $role->id]) }}">
                @csrf

                @method("PATCH")

                <h5 class="blue">specify the parameters of the new role:</h5>

                @input(['name' => 'name', 'value' => old('name') ?? $role->name, 'required' => 'required'])

                @input(['name' => 'display_name', 'value' => old('display_name') ?? $role->display_name, 'required' => 'required'])

                @input(['name' => 'description', 'value' => old('description') ?? $role->description, 'required' => 'required'])

                @input(['name' => 'rank', 'type' => 'number', 'value' => old('rank') ?? $role->rank, 'min' => 5, 'max' => 99,  'required' => 'required'])

                <h5 class="blue">select permissions for new role:</h5>
                @tablePermissions(['permissions' => $permissions, 'user' => $role, 'edit' => true])
                <br>

                <button type="submit" class="btn btn-primary form-control">edit role!</button>

            </form>
        </div>    
    </div>

@endsection
