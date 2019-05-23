@extends('layouts.app')

@section('title')
users
@endsection

@section('content')
<div class="container">

    <h1>List of users</h1>
    
    <table class="blue_table">
        <tr>
            <th>#</th>
            <th>id</th>
            <th>img</th>
            <th>name</th>
            <th>email</th>
            <th>role</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>actions</th>
        </tr>

        @foreach($users as $i=>$user)

        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $user->id }}</td>
            <td><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->roles->first()->name }}</td>
            <td>{{ $user->created_at ?? '-' }}</td>
            <td>{{ $user->updated_at ?? '-' }}</td>
            <td>
                <div class="td user_buttons row">

                    <div class="col-sm-4">
                        <a href="{{ route('usersShow', ['user' => $user->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>

                    <div class="col-sm-4">
                        <a href="{{ route('usersEdit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-pen-nib"></i>
                        </a>
                    </div>

                    <div class="col-sm-4">
                        <form action="{{ route('usersDestroy', ['user' => $user->id]) }}" method='POST'>
                            @csrf

                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </td>
        </tr>

        @endforeach

    </table>


</div>
@endsection
