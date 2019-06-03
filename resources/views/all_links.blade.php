@extends('layouts.app')

@section('title')
list of links
@endsection

@section('content')
<div class="container">

    <h1>list of all links for test RBAC</h1>


    <div class="row">
        
        <div class="col-md-4">
            <h5 class="blue">products:</h5>
            <a target="_blank" href="/products">index</a><br>
            <a target="_blank" href="/products/1">show</a><br>
            <a target="_blank" href="/products/create">create</a><br>
            <a target="_blank" href="/products/edit/1">edit</a><br>
            <form action="{{ route('products.destroy', ['product' => '1']) }}" method='POST'>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
        
        <div class="col-md-4">
            <h5 class="blue">users:</h5>
            <a target="_blank" href="/users">index</a><br>
            <a target="_blank" href="/users/1">show</a><br>
            <!-- <a target="_blank" href="/users/create">create</a><br> -->
            <a target="_blank" href="/users/edit/1">edit</a><br>
            <form action="{{ route('users.destroy', ['product' => '1']) }}" method='POST'>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
        
        <div class="col-md-4">
            <h5 class="blue">roles:</h5>
            <a target="_blank" href="/roles">index</a><br>
            <a target="_blank" href="/roles/1">show</a><br>
            <a target="_blank" href="/roles/create">create</a><br>
            <a target="_blank" href="/roles/edit/1">edit</a><br>
            <form action="{{ route('roles.destroy', ['product' => '1']) }}" method='POST'>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
        
    </div>


</div>
@endsection
