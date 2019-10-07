@extends('dashboard.layouts.app')

@section('title', __('Manufacturers_edit'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('manufacturers.edit', $manufacturer) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('Manufacturers_edit') }}</h1>


    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            {{-- ?? $manufacturer->imagepath --}}
            <form method="POST" action="{{ route('manufacturers.update', $manufacturer) }}" enctype="multipart/form-data">
                @method('PATCH')
                @csrf

                @lfmImageButton(['id' => 'lfm_images', 'name' => 'imagepath', 'value' => old('imagepath')])

                <div class="row">
                    <div class="col-12 col-md-6">
                        @input(['name' => 'name', 'label' => __('name'), 'value' => old('name') ?? $manufacturer->name, 'required' => 'required'])
                    </div>
                    <div class="col-12 col-md-6">
                        @input(['name' => 'title', 'label' => __('title'), 'value' => old('title') ?? $manufacturer->title])
                    </div>
                </div>

                {{-- description --}}
                {{ old('description') }}
                @if ( config('settings.description_wysiwyg') == 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'description', 'label' => 'Описание (редактор ckeditor)', 'value' => old('description') ?? $manufacturer->description])
                @elseif ( config('settings.description_wysiwyg') == 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'description', 'label' => 'Описание (редактор summernote)', 'value' => old('description') ?? $manufacturer->description])
                @elseif ( config('settings.description_wysiwyg') == 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'description', 'label' => 'Описание (редактор tinymce)', 'value' => old('description') ?? $manufacturer->description])
                @else
                    @textarea(['name' => 'description', 'label' => 'Описание (обычный режим)', 'value' => old('description') ?? $manufacturer->description])                
                @endif
                {{-- /description --}}                  


                <div class="row">
                    {{-- sort_order --}}
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="sort_order">{{ __('sort_order') }}</label>
                            <select name="sort_order" id="sort_order">
                                @for ( $i = 0; $i < 10; $i++ )
                                    @if ( $i ==  (old('sort_order') ?? $manufacturer->sort_order) )
                                        <option value="{{ $i }}" selected>{{ $i }}</option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                    {{-- sort_order --}}
                </div>

                <button type="submit" class="btn btn-primary form-control">{{ __('apply') }}</button>

            </form>
        </div>
    </div>
@endsection
