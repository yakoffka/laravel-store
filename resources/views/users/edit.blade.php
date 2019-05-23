@extends('layouts.app')

@section('title')
edit profile
@endsection

@section('content')
<div class="container">

    <h1>edit profile {{ $user->name }}</h1>
    
    <div><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></div>

    <form method="POST" 
        action="{{ route('usersUpdate', ['user' => $user->id]) }}" 
        enctype="multipart/form-data">

        @csrf

        @method('PATCH')

        <div class="form-group">
            <label for="name">name user</label>
            <input type="text" id="name" name="name" class="form-control" 
                value="{{ old('name') ?? $user->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">email user</label>
            <input type="email" id="email" name="email" class="form-control" 
                value="{{ old('email') ?? $user->email }}">
        </div>

        <div class="form-group">
            <label for="name">password user</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary form-control">edit profile!</button>

    </form>
</div>
@endsection
