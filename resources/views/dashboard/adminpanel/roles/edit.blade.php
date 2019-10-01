@extends('layouts.app')

@section('title', 'edit role')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('roles.edit', $role) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Edit Role '{{ $role->name }}'</h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            <form method="POST" action="{{ route('roles.update', ['role' => $role->id]) }}">
                @csrf

                @method("PATCH")

                <h5 class="blue">specify the parameters of the new role:</h5>

                @input(['name' => 'name', 'value' => old('name') ?? $role->name, 'required' => 'required'])

                @input(['name' => 'display_name', 'value' => old('display_name') ?? $role->display_name, 'required' => 'required'])

                @input(['name' => 'description', 'value' => old('description') ?? $role->description, 'required' => 'required'])

                <h5 class="blue">select permissions for new role:</h5>
                @tablePermissions(['permissions' => $permissions, 'user' => $role, 'edit' => true])
                <br>

                <button type="submit" class="btn btn-primary form-control">edit role!</button>

            </form>
        </div>    
    </div>

@endsection
