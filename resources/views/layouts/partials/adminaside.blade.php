{{-- <h3>Admin aside</h3> --}}

    @if ( Auth::user()->hasRole(['owner']) )
        <h4 class="grey"><i class="fas fa-book-dead"></i> Readme:</h4>
        <div class="submenuitem">- <a target="_blank" href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></div>
        <div class="submenuitem">- <a target="_blank" href="https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs">Breadcrumbs</a></div>
        {{-- <div class="submenuitem">- <a href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></div>
        <div class="submenuitem">- <a href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></div> --}}
        {{-- @include('layouts.partials.separator') --}}
    @endif

    @role('owner')
        <h4 class="grey"><i class="fas fa-tools"></i> Tools:</h4>
        <div class="submenuitem">- <a target="_blank" href="/telescope">Telescope</a></div>
        <div class="submenuitem">- <a target="_blank" href="http://adminer.local">Adminer</a></div>
        {{-- @include('layouts.partials.separator') --}}
    @endrole


    @permission('view_products')
        <h4 class="grey"><i class="fas fa-boxes"></i> Products:</h4>
        <div class="submenuitem">- <a href="{{ route('products.index') }}">List of products</a></div>
        @permission('create_products')
            <div class="submenuitem">- <a href="{{ route('products.create') }}">Create new product</a></div>
        @endpermission
        @permission('edit_products')
            <h4 class="grey"><i class="fas fa-image"></i> Images:</h4>
            <div class="submenuitem">- 
                <a href="{{ route('products.rewatermark') }}" title="Resave all image with new Watermark">Rewatermark</a>
            </div>
            {{-- @include('layouts.partials.separator') --}}
        @endpermission
    @endpermission
    


    @permission('view_roles')
        <h4 class="grey"><i class="fas fa-sliders-h"></i> Roles:</h4>
        <div class="submenuitem">- <a href="{{ route('roles.index') }}">List of roles</a></div>
        @permission('create_roles')
            <div class="submenuitem">- <a href="{{ route('roles.create') }}">Create new role</a></div>
            {{-- @include('layouts.partials.separator') --}}
        @endpermission
    @endpermission


    @permission('view_users')
        <h4 class="grey"><i class="fas fa-users"></i> Users:</h4>
        <div class="submenuitem">- <a href="{{ route('users.index') }}">List of users</a></div>
        {{-- @include('layouts.partials.separator') --}}
    @endpermission
    {{-- @permission('create_users')
        <div class="submenuitem">- <a href="{{ route('users.create') }}">Create new user</a></div>
    @endpermission --}}


    @permission('view_categories')
        <h4 class="grey"><i class="fas fa-folder"></i> Categories:</h4>
        <div class="submenuitem">- <a href="{{ route('categories.index') }}">List of categories</a></div>
        @permission('create_categories')
            <div class="submenuitem">- <a href="{{ route('categories.create') }}">Create new category</a></div>
            {{-- @include('layouts.partials.separator') --}}
        @endpermission
    @endpermission


    @permission('view_orders')
        <h4 class="grey"><i class="fas fa-shipping-fast"></i> Orders:</h4>
        <div class="submenuitem">- <a href="{{ route('orders.index') }}">List of orders</a></div>
        {{-- @include('layouts.partials.separator') --}}
    @endpermission
    {{-- @permission('create_orders')
        <div class="submenuitem">- <a href="{{ route('orders.create') }}">Create new category</a></div>
    @endpermission --}}

    @permission('view_settings')
        <h4 class="grey"><i class="fas fa-cogs"></i> Settings:</h4>
            <div class="submenuitem">- <a href="{{ route('settings.index') }}">List of settings</a></div>
        @permission('create_settings')
            {{-- <div class="submenuitem">- <a href="{{ route('settings.create') }}">Create new settings</a></div> --}}
        @endpermission
        {{-- @permission('edit_settings')
            <div class="submenuitem">- <a href="{{ route('settings.edit') }}">List of settings</a></div>
        @endpermission --}}
        {{-- @permission('delete_settings')
            <div class="submenuitem">- <a href="{{ route('settings.destroy') }}">List of settings</a></div>
        @endpermission --}}
        {{-- @include('layouts.partials.separator')  --}}
    @endpermission

    {{-- @role('admin')
        <p>youre role is admin</p>
    @endrole

    @role('manager')
        <p>youre role is manager</p>
    @endrole

    @role('user')
        <p>youre role is user</p>
    @endrole --}}
