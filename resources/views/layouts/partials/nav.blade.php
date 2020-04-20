{{--многоуровневое меню--}}
{{-- src html: https://www.codeply.com/go/K1gXPZwV59/bootstrap-vertical-sidebar-_-accordion-menu-with-submenus--}}
<div id="left_menu">
    <div class="list-group category">
        @include('includes.recursive_part_nav', [
            'categories' => $sharedRecursiveCategories,
            'data_parent' => 'left_menu',
            'sub_class' => 'sub_category',
        ])
    </div>
</div>
