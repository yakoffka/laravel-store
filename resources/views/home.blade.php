@extends('layouts.app')

@section('title', 'home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard <?php echo Auth::user()->roles->first()->name; ?></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- You are logged in! --}}
                    @role('user')
                        <p>You are logged in!</p>
                    @endrole
                    
                    <!-- Zizaco -->
                    @if ( Auth::user()->hasRole(['owner', 'admin']) )
                        <h2 class="grey">Zizaco:</h2>
                        <p>read usage <a href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></p>
                    @endif  
                    {{-- @role('owner')
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
                    @endrole --}}
                    <!-- /Zizaco -->


                    @permission('view_products')
                        <h2 class="grey">Products:</h2>
                        <h5><a href="{{ route('products.index') }}">List of products</a></h5>
                    @endpermission
                    @permission('create_products')
                        <h5><a href="{{ route('products.create') }}">Create new product</a></h5>
                    @endpermission
                    @permission('edit_products')
                        <h2 class="grey">Images:</h2>
                        <h5><a href="{{ route('products.rewatermark') }}">Resave all image with new Watermark</a></h5>
                    @endpermission
                    


                    @permission('view_roles')
                        <h2 class="grey">Roles:</h2>
                        <h5><a href="{{ route('roles.index') }}">List of roles</a></h5>
                    @endpermission
                    @permission('create_roles')
                        <h5><a href="{{ route('roles.create') }}">Create new role</a></h5>
                    @endpermission


                    @permission('view_users')
                        <h2 class="grey">Users:</h2>
                        <h5><a href="{{ route('users.index') }}">List of users</a></h5>
                    @endpermission
                    {{-- @permission('create_users')
                        <h5><a href="{{ route('users.create') }}">Create new user</a></h5>
                    @endpermission --}}


                    @permission('view_categories')
                        <h2 class="grey">Categories:</h2>
                        <h5><a href="{{ route('categories.index') }}">List of categories</a></h5>
                    @endpermission
                    @permission('create_categories')
                        <h5><a href="{{ route('categories.create') }}">Create new category</a></h5>
                    @endpermission


                    @permission('view_orders')
                        <h2 class="grey">Orders:</h2>
                        <h5><a href="{{ route('orders.index') }}">List of orders</a></h5>
                    @endpermission
                    {{-- @permission('create_orders')
                        <h5><a href="{{ route('orders.create') }}">Create new category</a></h5>
                    @endpermission --}}



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
