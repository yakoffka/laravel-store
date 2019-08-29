{{-- <h3>Admin aside</h3> --}}


{{-- Readme --}}
    @if ( Auth::user()->hasRole(['owner']) )
        <h4 class="grey"><i class="fas fa-book-dead"></i> Readme:</h4>
        <div class="submenuitem">- <a target="_blank" href="https://laravel.com">Laravel.com</a></div>
        <div class="submenuitem">- <a target="_blank" href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></div>
        <div class="submenuitem">- <a target="_blank" href="https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs">Breadcrumbs</a></div>
        <div class="submenuitem">- <a target="_blank" href="https://github.com/alexeymezenin/laravel-best-practices/blob/master/russian.md">laravel-best-practices</a></div>
    @endif
{{-- /Readme --}} 


{{-- Tools --}}
    @role('owner')
        <h4 class="grey"><i class="fas fa-tools"></i> Tools:</h4>
        <div class="submenuitem">- <a target="_blank" href="/telescope">Telescope</a></div>
        <div class="submenuitem">- <a target="_blank" href="/phpmyadmin">PMA</a></div>
        <div class="submenuitem">- <a target="_blank" href="http://adminer.local">Adminer</a></div>
        <div class="submenuitem">- <a href="{{ route('clear') }}">CacheClear</a></div>
    @endrole
{{-- /Tools --}}


{{-- Tasks --}}
    @auth
        <h4 class="grey"><i class="fas fa-tasks"></i> Tasks:</h4>
        <div class="submenuitem">- <a href="{{ route('tasks.index') }}">my tasks</a></div>
        <div class="submenuitem">- <a href="{{ route('directives.index') }}">my directives</a></div>
        {{-- @permission('view_tasks')
            <div class="submenuitem">- <a href="">all task</a></div>
            <div class="submenuitem">- <a href="">all directives</a></div>
        @endpermission --}}
    @endauth
{{-- /Tasks --}}


{{-- Products --}}
    @permission('view_products')
        <h4 class="grey"><i class="fas fa-boxes"></i> Products:</h4>
        <div class="submenuitem">- <a href="{{ route('products.index') }}">List of products</a></div>
        @permission('create_products')
            <div class="submenuitem">- <a href="{{ route('products.create') }}">Create new product</a></div>
        @endpermission
        @permission('edit_products')
            <div class="submenuitem">- 
                <a href="{{ route('products.rewatermark') }}" title="Resave all image with new Watermark">Rewatermark</a>
            </div>
            @endpermission
    @endpermission
{{-- /Products --}}


{{-- Orders --}}
    @permission('view_orders')
        <h4 class="grey"><i class="fas fa-shipping-fast"></i> Orders:</h4>
        <div class="submenuitem">- <a href="{{ route('orders.index') }}">List of orders</a></div>
    @endpermission
{{-- /Orders --}}


{{-- Categories --}}
    @permission('view_categories')
        <h4 class="grey"><i class="fas fa-folder"></i> Categories:</h4>
        <div class="submenuitem">- <a href="{{ route('categories.index') }}">List of categories</a></div>
        @permission('create_categories')
            <div class="submenuitem">- <a href="{{ route('categories.create') }}">Create new category</a></div>
        @endpermission
    @endpermission
{{-- /Categories --}}


{{-- Users --}}
    @permission('view_users')
        <h4 class="grey"><i class="fas fa-users"></i> Users:</h4>
        <div class="submenuitem">- <a href="{{ route('users.index') }}">List of users</a></div>
    @endpermission
{{-- /Users --}}
    

{{-- Roles --}}
    @permission('view_roles')
        <h4 class="grey"><i class="fas fa-sliders-h"></i> Roles:</h4>
        <div class="submenuitem">- <a href="{{ route('roles.index') }}">List of roles</a></div>
        @permission('create_roles')
            <div class="submenuitem">- <a href="{{ route('roles.create') }}">Create new role</a></div>
            @endpermission
    @endpermission
{{-- /Roles --}}


{{-- Settings --}}
    @permission('view_settings')
        <h4 class="grey"><i class="fas fa-cogs"></i> Settings:</h4>
        @permission('edit_settings')
            <div class="submenuitem">- <a href="{{ route('settings.index') }}">List of settings</a></div>
        @endpermission
    @endpermission
{{-- /Settings --}}


{{-- History --}}
    @permission('view_actions')
        <h4 class="grey"><i class="fas fa-history"></i> History:</h4>
        @permission('view_products')
            <div class="submenuitem">- <a href="{{ route('actions.products') }}">Products history</a></div>
        @endpermission
        @permission('view_orders')
            <div class="submenuitem">- <a href="{{ route('actions.orders') }}">Orders history</a></div>
        @endpermission
        @permission('view_categories')
            <div class="submenuitem">- <a href="{{ route('actions.categories') }}">Categories history</a></div>
        @endpermission
        @permission('view_users')
            <div class="submenuitem">- <a href="{{ route('actions.registrations') }}">Registrations history</a></div>
        @endpermission
        @permission('view_settings')
            <div class="submenuitem">- <a href="{{ route('actions.settings') }}">Settings change history</a></div>
        @endpermission
        @permission('view_users')
            <div class="submenuitem">- <a href="{{ route('actions.users') }}">All history</a></div>
        @endpermission
    @endpermission
{{-- /History --}}



