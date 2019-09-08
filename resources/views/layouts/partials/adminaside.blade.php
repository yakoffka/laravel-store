{{-- <h5>Admin aside</h5> --}}


{{-- Readme --}}
<div class="admin_menu_block">
    @if ( Auth::user()->hasRole(['owner']) )
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseReadme" role="button" aria-expanded="false" aria-controls="collapseReadme"><i class="fas fa-book-dead"></i> Readme</span></h5>
        <div class="collapse" id="collapseReadme">
            <div class="submenuitem">- <a target="_blank" href="https://laravel.com">Laravel.com</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs">Breadcrumbs</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/nicolaslopezj/searchable">Search</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/Intervention/image">Intervention/image</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/UniSharp/laravel-filemanager">UniSharp/lfm</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/alexeymezenin/laravel-best-practices/blob/master/russian.md">laravel-best-practices</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://si-dev.com/ru/blog/laravel-model-observers">laravel-model-observers</a></div>
        </div>
    @endif
</div>
{{-- /Readme --}}



{{-- Tools --}}
<div class="admin_menu_block">
    @role('owner')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseTools" role="button" aria-expanded="false" aria-controls="collapseTools"><i class="fas fa-tools"></i> Tools</span></h5>
        <div class="collapse" id="collapseTools">
            <div class="submenuitem">- <a target="_blank" href="/telescope">Telescope</a></div>
            <div class="submenuitem">- <a target="_blank" href="/phpmyadmin">PMA</a></div>
            <div class="submenuitem">- <a target="_blank" href="http://adminer.local">Adminer</a></div>
            <div class="submenuitem">- <a href="{{ route('clear') }}">CacheClear</a></div>
        </div>
    @endrole
</div>
{{-- /Tools --}}

{{-- Tasks --}}
<div class="admin_menu_block">
    @auth
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseTasks" role="button" aria-expanded="false" aria-controls="collapseTasks"><i class="fas fa-tasks"></i> Tasks</span></h5>
        <div class="collapse" id="collapseTasks">
            <div class="submenuitem">- <a href="{{ route('tasks.index') }}">my tasks</a></div>
            <div class="submenuitem">- <a href="{{ route('directives.index') }}">my directives</a></div>
            {{-- @permission('view_tasks')
                <div class="submenuitem">- <a href="">all task</a></div>
                <div class="submenuitem">- <a href="">all directives</a></div>
            @endpermission --}}
        </div>
    @endauth
</div>
{{-- /Tasks --}}


{{-- Products --}}
<div class="admin_menu_block">
    @permission('view_products')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseProducts" role="button" aria-expanded="false" aria-controls="collapseProducts"><i class="fas fa-boxes"></i> Products</span></h5>
        <div class="collapse" id="collapseProducts">
            <div class="submenuitem">- <a href="{{ route('products.adminindex') }}">List of products</a></div>
            @permission('create_products')
                <div class="submenuitem">- <a href="{{ route('products.create') }}">Create new product</a></div>
            @endpermission
            @permission('edit_products')
                <div class="submenuitem">- 
                    <a href="{{ route('products.rewatermark') }}" title="Resave all image with new Watermark">Rewatermark</a>
                </div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Products --}}


{{-- Orders --}}
<div class="admin_menu_block">
    @permission('view_orders')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseOrders" role="button" aria-expanded="false" aria-controls="collapseOrders"><i class="fas fa-shipping-fast"></i> Orders</span></h5>
        <div class="collapse" id="collapseOrders">
            <div class="submenuitem">- <a href="{{ route('orders.index') }}">List of orders</a></div>
        </div>
    @endpermission
</div>
{{-- /Orders --}}


{{-- Categories --}}
<div class="admin_menu_block">
    @permission('view_categories')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseCategories" role="button" aria-expanded="false" aria-controls="collapseCategories"><i class="fas fa-folder"></i> Categories</span></h5>
        <div class="collapse" id="collapseCategories">
            <div class="submenuitem">- <a href="{{ route('categories.adminindex') }}">List of categories</a></div>
            @permission('create_categories')
                <div class="submenuitem">- <a href="{{ route('categories.create') }}">Create new category</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Categories --}}


{{-- Users --}}
<div class="admin_menu_block">
    @permission('view_users')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseUsers" role="button" aria-expanded="false" aria-controls="collapseUsers"><i class="fas fa-users"></i> Users</span></h5>
        <div class="collapse" id="collapseUsers">
            <div class="submenuitem">- <a href="{{ route('users.index') }}">List of users</a></div>
        </div>
    @endpermission
</div>
{{-- /Users --}}
    

{{-- Roles --}}
<div class="admin_menu_block">
    @permission('view_roles')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseRoles" role="button" aria-expanded="false" aria-controls="collapseRoles"><i class="fas fa-sliders-h"></i> Roles</span></h5>
        <div class="collapse" id="collapseRoles">
            <div class="submenuitem">- <a href="{{ route('roles.index') }}">List of roles</a></div>
            @permission('create_roles')
                <div class="submenuitem">- <a href="{{ route('roles.create') }}">Create new role</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Roles --}}


{{-- Settings --}}
<div class="admin_menu_block">
    @permission('view_settings')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseSettings" role="button" aria-expanded="false" aria-controls="collapseSettings"><i class="fas fa-cogs"></i> Settings</span></h5>
        <div class="collapse" id="collapseSettings">
            @permission('edit_settings')
                <div class="submenuitem">- <a href="{{ route('settings.index') }}">List of settings</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Settings --}}


{{-- History --}}
<div class="admin_menu_block">
    @permission('view_actions')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseHistory" role="button" aria-expanded="false" aria-controls="collapseHistory"><i class="fas fa-history"></i> History</span></h5>
        <div class="collapse" id="collapseHistory">
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
        </div>
    @endpermission
</div>
{{-- /History --}}



