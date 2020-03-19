{{-- <h5>Admin aside</h5> --}}


{{-- Readme --}}
<div class="admin_menu_block">

    {{-- 'owner', 'developer' --}}
    @role(['owner', 'developer'])
    <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseReadme" role="button" aria-expanded="false" aria-controls="collapseReadme">
			<i class="fas fa-book-dead"></i> Readme</span>
		</h5>
        <div class="collapse" id="collapseReadme">
            <div class="submenuitem">- <a target="_blank" href="https://laravel.com">Laravel.com</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://laravel.com/api/5.8">api/5.8</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://freefrontend.com">freefrontend.com</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/Zizaco/entrust#usage">Zizaco/entrust</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs">Breadcrumbs</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/nicolaslopezj/searchable">Search</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/Intervention/image">Intervention/image</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/UniSharp/laravel-filemanager">UniSharp/lfm</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/alexeymezenin/laravel-best-practices/blob/master/russian.md">best-practices</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://si-dev.com/ru/blog/laravel-model-observers">model-observers</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://www.youtube.com/watch?v=oc1_DHfL89k&list=PL55RiY5tL51qUXDyBqx0mKVOhLNFwwxvH&index=2">look me up</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://github.com/caouecs/Laravel-lang">Laravel-lang</a></div>
            <div class="submenuitem">- <a target="_blank" href="https://laravel.com/docs/5.8/migrations#creating-columns">Columns</a></div>
        </div>
    @endrole
    {{-- /'owner', 'developer' --}}
</div>
{{-- /Readme --}}


{{-- Tools --}}
<div class="admin_menu_block">
    {{-- 'owner', 'developer' --}}
    @role(['owner', 'developer'])
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseTools" role="button" aria-expanded="false" aria-controls="collapseTools">
			<i class="fas fa-tools"></i> Инструменты</span>
		</h5>
        <div class="collapse" id="collapseTools">
            <div class="submenuitem">- <a target="_blank" href="/telescope">Telescope</a></div>
            <div class="submenuitem">- <a target="_blank" href="/phpmyadmin">PMA</a></div>
            <div class="submenuitem">- <a target="_blank" href="http://adminer.local">Adminer</a></div>
            <div class="submenuitem">- <a href="{{ route('clear') }}">CacheClear</a></div>
        </div>
    @endrole
    {{-- /'owner', 'developer' --}}
</div>
{{-- /Tools --}}


{{-- Tasks --}}
<div class="admin_menu_block">
    @auth
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseTasks" role="button" aria-expanded="false" aria-controls="collapseTasks">
			<i class="fas fa-tasks"></i> Задачи</span>
		</h5>
        <div class="collapse" id="collapseTasks">
            <div class="submenuitem">- <a href="{{ route('tasks.index') }}">задачи</a></div>
            <div class="submenuitem">- <a href="{{ route('directives.index') }}">поручения</a></div>
            <div class="submenuitem">- <a href="{{ route('tasks.create') }}">{{ __('create_new_task') }}</a></div>
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
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseProducts" role="button" aria-expanded="false" aria-controls="collapseProducts">
			<i class="fas fa-boxes"></i> Товары</span>
		</h5>
        <div class="collapse" id="collapseProducts">
            <div class="submenuitem">- <a href="{{ route('products.adminindex') }}">Список</a></div>
            @permission('create_products')
                <div class="submenuitem">- <a href="{{ route('products.create') }}">Создание</a></div>
            @endpermission
            @permission('edit_products')
                <div class="submenuitem">-
                    <a href="{{ route('products.rewatermark') }}" title="Resave all image with new Watermark">ReWaterMark</a>
                </div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Products --}}


{{-- Manufacturers --}}
<div class="admin_menu_block">
    @permission('view_manufacturers')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseManufacturers" role="button" aria-expanded="false" aria-controls="collapseManufacturers">
            <i class="far fa-copyright"></i> Производители</span>
		</h5>
        <div class="collapse" id="collapseManufacturers">
            <div class="submenuitem">- <a href="{{ route('manufacturers.index') }}">Список</a></div>
            @permission('create_manufacturers')
                <div class="submenuitem">- <a href="{{ route('manufacturers.create') }}">Создание</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Manufacturers --}}


{{-- Orders --}}
<div class="admin_menu_block">
    @permission('view_orders')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseOrders" role="button" aria-expanded="false" aria-controls="collapseOrders">
			<i class="fas fa-shipping-fast"></i> Заказы</span>
		</h5>
        <div class="collapse" id="collapseOrders">
            <div class="submenuitem">- <a href="{{ route('orders.adminindex') }}">Список</a></div>
        </div>
    @endpermission
</div>
{{-- /Orders --}}


{{-- Categories --}}
<div class="admin_menu_block">
    @permission('view_categories')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseCategories" role="button" aria-expanded="false" aria-controls="collapseCategories">
			<i class="fas fa-folder"></i> Категории</span>
		</h5>
        <div class="collapse" id="collapseCategories">
            <div class="submenuitem">- <a href="{{ route('categories.adminindex') }}">Список</a></div>
            @permission('create_categories')
                <div class="submenuitem">- <a href="{{ route('categories.create') }}">Создание</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Categories --}}


{{-- Users --}}
<div class="admin_menu_block">
    @permission('view_users')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseUsers" role="button" aria-expanded="false" aria-controls="collapseUsers">
			<i class="fas fa-users"></i> Пользователи</span>
		</h5>
        <div class="collapse" id="collapseUsers">
            <div class="submenuitem">- <a href="{{ route('users.index') }}">Список</a></div>
        </div>
    @endpermission
</div>
{{-- /Users --}}


{{-- Roles --}}
<div class="admin_menu_block">
    @permission('view_roles')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseRoles" role="button" aria-expanded="false" aria-controls="collapseRoles">
			<i class="fas fa-sliders-h"></i> Роли</span>
		</h5>
        <div class="collapse" id="collapseRoles">
            <div class="submenuitem">- <a href="{{ route('roles.index') }}">Список</a></div>
            @permission('create_roles')
                <div class="submenuitem">- <a href="{{ route('roles.create') }}">Создание</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Roles --}}


{{-- Settings --}}
<div class="admin_menu_block">
    @permission('view_settings')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseSettings" role="button" aria-expanded="false" aria-controls="collapseSettings">
			<i class="fas fa-cogs"></i> Настройки</span>
		</h5>
        <div class="collapse" id="collapseSettings">
            @permission('edit_settings')
                <div class="submenuitem">- <a href="{{ route('settings.index') }}">Список</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /Settings --}}


{{-- History --}}
<div class="admin_menu_block">
    @permission('view_customevents')
        <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseHistory" role="button" aria-expanded="false" aria-controls="collapseHistory">
			<i class="fas fa-history"></i> История</span>
		</h5>
        <div class="collapse" id="collapseHistory">
            @permission('view_users')
                <div class="submenuitem">- <a href="{{ route('customevents.index') }}">Полная</a></div>
            @endpermission
        </div>
    @endpermission
</div>
{{-- /History --}}


{{--Export--}}
<div class="admin_menu_block">
    @permission('view_products')
    <h5 class="grey"><span class="pointer" data-toggle="collapse" href="#collapseExport" role="button" aria-expanded="false" aria-controls="collapseExport">
			<i class="fas fa-export"></i> Обмен 1С</span>
    </h5>
    <div class="collapse" id="collapseExport">
        @permission('view_products')
        <div class="submenuitem">- <a href="{{ route('export.products') }}">экспорт товаров</a></div>
        <div class="submenuitem">- <a href="{{ route('import.products') }}">импорт товаров</a></div>
        @endpermission
    </div>
    @endpermission
</div>
{{--/Export--}}



