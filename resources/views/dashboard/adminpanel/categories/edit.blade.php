@extends('layouts.theme_switch')

@section('title', "Редактирование категории $category->title")

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('categories.edit', $category) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Редактирование категории '{{ $category->title }}'</h1>


    <div class="row">

        
        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                
            <form method="POST" action="{{ route('categories.update', ['category' => $category->id]) }}" enctype="multipart/form-data">
                @csrf

                @method('PATCH')

                {{-- image --}}
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card-img-top b_image" style="background-image: url(
                                @if($category->image)                    
                                    {{ asset('storage') }}/images/categories/{{$category->id}}/{{$category->image}}
                                @else
                                    {{ asset('storage') }}{{ config('imageyo.default_img') }}
                                @endif
                                );">
                                <div class="dummy"></div><div class="element"></div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            @lfmImageButton(['id' => 'lfm_category_' . $category->id, 'name' => 'imagepath', 'value' => old('imagepath') ?? $category->imagepath ?? ''])
                        </div>
                    </div>
                {{-- /image --}}
                

                <div class="row">
                    {{-- name --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="name">{{ __('__namerequired') }}*</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="{{__('Name')}} {{__('Category')}}"
                                value="{{ old('name') ?? $category->name }}" required>
                        </div>
                    </div>
                    {{-- /name --}}

                    {{-- title --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="title">{{ __('title') }}</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="{{__('Title')}} {{__('Category')}}"
                                value="{{ old('title') ?? $category->title }}">
                        </div>
                    </div>
                    {{-- /title --}}

                    {{-- slug --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="slug">{{ __('slug') }}</label>
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="{{__('Slug')}} {{__('Category')}}"
                                value="{{ old('slug') ?? $category->slug }}">
                        </div>
                    </div>
                    {{-- /slug --}}
                </div>

                <div class="form-group">
                    <label for="description">{{ __('description') }}</label>
                    <textarea id="description" name="description" cols="30" rows="4" class="form-control"
                        placeholder="{{ __('description') }}">{{ old('description') ?? $category->description }}</textarea>                       
                </div>


                <div class="row">
                    {{-- sort_order --}}
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="sort_order">{{ __('sort_order') }}</label>
                            <select name="sort_order" id="sort_order">
                                @for ( $i = 0; $i < 10; $i++ )
                                    @if ( $category->sort_order == $i )
                                        <option value="{{ $i }}" selected>{{ $i }}</option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                    {{-- sort_order --}}

                    {{-- parent category --}}
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label for="description">{{ __('category') }}</label>
                            <select name="parent_id" id="parent_id">
                                @foreach ( $categories as $parent_category )
                                    @if ( !$parent_category->countProducts() )
                                        <option 
                                            value="{{ $parent_category->id }}"
                                            {{ $parent_category->id == $category->parent_id ? ' selected' : ''}}
                                            >{{ $parent_category->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- /parent category --}}

                    {{-- seeable --}}
                    <div class="form-group right_stylized_checkbox">
                        <input type="checkbox" id="seeable" name="seeable"
                            @if($category->seeable)
                                checked
                            @endif
                        >
                        <label for="seeable">{{ __('seeable') }}</label>
                    </div>
                    {{-- /seeable --}}
                </div>

                <button type="submit" class="btn btn-primary form-control">{{ __('apply') }}</button>

            </form>
        </div>
    </div>
@endsection
