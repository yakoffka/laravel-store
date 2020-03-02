@extends('layouts.theme_switch')

@section('title', __('Manufacturers'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('manufacturers.index') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('Manufacturers') }}</h1>


    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            {{-- table manufacturers --}}
            <table class="blue_table overflow_x_auto">
                <tr>
                    <th width="30">{{ __('__id') }}</th>
                    <th>{{ __('__name') }}</th>
                    <th>{{ __('title') }}</th>
                    <th>{{ __('__description') }}</th>
                    <th width="60"><div class="verticalTableHeader ta_c">{{ __('__img') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('sort_order') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('added_by_user_id') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('edited_by_user_id') }}</div></th>
                    <th class="actions3">{{ __('__actions') }}</th>
                </tr>

                @foreach ( $manufacturers as $manufacturer )
                    <tr>
                        <td>{{ $manufacturer->id }}</td>
                        <td><a href="{{ route('manufacturers.show', $manufacturer) }}">{{ $manufacturer->name }}</a></td>
                        <td>{{ $manufacturer->title ?? '-' }}</td>
                        <td>{!! $manufacturer->description ?? '-' !!}</td>

                        {{-- image --}}
                        <td>
                            @if($manufacturer->imagepath)
                                <div class="card-img-top b_image"
                                    style="background-image: url({{ asset('storage') }}/images/manufacturers/{{$manufacturer->uuid}}/{{$manufacturer->imagepath}});">
                            @else
                                <div class="card-img-top b_image"
                                    style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});">
                            @endif
                                <div class="dummy perc100"></div>
                                <div class="element"></div>
                            </div>
                        </td>
                        {{-- image --}}

                        <td>{{ $manufacturer->sort_order }}</td>
                        <td title="{{ $manufacturer->creator->name }}">{{ $manufacturer->creator->id }}</td>
                        <td title="{{ $manufacturer->editor->name ?? '' }}">{{ $manufacturer->editor->id ?? '-' }}</td>

                        {{-- actions --}}
                        <td>
                            {{-- view --}}
                            <a href="{{ route('manufacturers.show', $manufacturer) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>

                            {{-- edit --}}
                            <a href="{{ route('manufacturers.edit', $manufacturer) }}"
                                class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>

                            {{-- delete --}}
                            @permission('delete_manufacturers')
                                @modalConfirmDestroy([
                                    'btn_class' => 'btn btn-outline-danger align-self-center',
                                    'cssId' => 'delete_',
                                    'item' => $manufacturer,
                                    'type_item' => 'категорию',
                                    'action' => route('manufacturers.destroy', $manufacturer),
                                ])
                            @endpermission
                        </td>
                        {{-- /actions --}}

                    </tr>
                @endforeach
            </table>
            {{-- /table manufacturers --}}

            <a href="{{ route('manufacturers.create') }}"
                class="btn btn-outline-success form-control">{{ __('Manufacturers_create') }}
            </a>

        </div>
    </div>
@endsection
