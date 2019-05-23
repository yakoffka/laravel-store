@extends('layouts.app')

@section('title')
user
@endsection

@section('content')
<div class="container">

    <h1>show user {{ $user->name }}</h1>
    
    <div>{{ $user->id }}</div>
    <div><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></div>
    <div>{{ $user->name }}</div>
    <div>{{ $user->email }}</div>
    <div>{{ $user->roles->first()->name }}</div>
    <div>{{ $user->created_at ?? '-' }}</div>
    <div>{{ $user->updated_at ?? '-' }}</div>

    @permission(['edit_users', 'delete_users'], true)

        <div>
            <div class="div user_buttons row">

                <div class="col-sm-6">
                    <a href="{{ route('usersEdit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                        <i class="fas fa-pen-nib"></i>
                    </a>
                </div>

                <div class="col-sm-6">
                    <form action="{{ route('usersDestroy', ['user' => $user->id]) }}" method='POST'>
                        @csrf

                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>

    @endpermission


    <!-- @ permission('edit_users') -->
    @if( Auth::user()->can('edit_users') and Auth::user()->cannot('delete_users'))

        <div>
            <div class="div user_buttons row">

                <div class="col-sm-12">
                    <a href="{{ route('usersEdit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                        <i class="fas fa-pen-nib"></i>
                    </a>
                </div>

            </div>
        </div>

    <!-- @ endpermission -->
    @endif


</div>
@endsection
