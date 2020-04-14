@extends('layouts.theme_switch')
@section('title', __('Import_products'))
@section('description', __('Import_products'))
@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('admin.import') }}
        </div>
        <div
            class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    <h1>{{__('Import_products')}}</h1>

    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">

            <h2>Общие сведения</h2>
            <p>В приложении реализован функционал импорта, реагирующий на наличие файла импорта {{ $csv }} в директории '{{ $importPath }}'</p>
            <p>Предполагается периодический запуск команды 'php artisan import:check' (например с помощью планировщика cron), которая проверяет наличие файла импорта.</p>
            <p>Помимо файла импорта в процессе могут участвовать изображения, которые также загружаются в поддиректорию 'images/'</p>
            <p>При обнаружении файла инициируется процесс импорта, по окончании которого в канал slack отправляется уведомление о его результатах.</p>
            <p>Образец файла импорта и изображений товаров предоставлен в поддиректории 'examples/'; поля могут добавляться или удаляться без предварительного уведомления.</p>

        </div>
    </div>
@endsection
