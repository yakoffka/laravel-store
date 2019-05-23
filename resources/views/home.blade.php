@extends('layouts.app')

@section('title')
home
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    
                    <!-- Zizaco -->
                    @if ( Auth::user()->hasRole(['owner', 'admin']) )
                        <p>read usage <a href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></p>
                    @endif  
                    
                    @role('owner')
                        <p>youre role is owner</p>
                    @endrole

                    @role('admin')
                        <p>youre role is admin</p>
                    @endrole

                    @role('manager')
                        <p>youre role is manager</p>
                    @endrole

                    @role('user')
                        <p>youre role is user</p>
                    @endrole
                    <!-- /Zizaco -->

                    <?php
                        echo Auth::user()->roles->first()->name;
                    ?>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
