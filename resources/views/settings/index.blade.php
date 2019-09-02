@extends('layouts.app')

@php
    $title = 'Settings Store';
@endphp

@section('title', $title)

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('settings') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    
    <h1>{{ $title }}</h1>


    <div class="row">


        @include('layouts.partials.aside')


        {{-- content --}}
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            @if ($groups->count())
                <h4>группы настроек</h4>
                <ol>
                    {{-- {{dd(count($groups))}} --}}
                    @foreach ($groups as $group)
                        <li class=""><a href="#{{ $group }}">{{ $group }}</a>
                    @endforeach
                </ol>
            @endif

            @php
                $j = 1;
            @endphp

            @foreach ($groups as $group)
                <div class="" id="{{ $group }}"></div>
                <div class="card setting_group">

                    @php
                        if (!empty($i)) {
                            $j++;
                        }
                        $i = 0;
                    @endphp

                    <div class="card-header"><h3>{{ $j }} Настройки группы '{{ $group }}'</h3></div>

                    @foreach ($settings as $setting)
                    
                        @if ($setting->group == $group)

                            @php
                                $i++;
                                $permissible_values = unserialize($setting->permissible_values);
                            @endphp

                            <div class="card-body">
                                <h5 class="card-title">{{ $j . '.' . $i }} {{ $setting->display_name }}</h5>
                                {{-- <p class="card-text">
                                    {{ $setting->description }}
                                </p> --}}
                                <form action="{{ route('settings.update', ['setting' => $setting->id]) }}" 
                                    method="POST" class="row left_stylized_checkbox">

                                    @csrf

                                    @method("PATCH")

                                    {{-- @if ($setting->type == 'select') --}}
                                    @if ($setting->type == 'checkbox')

                                        {{-- <select name="value" id="setting_{{ $setting->id }}" class="{{ $setting->value ? 'on_select' : 'off_select' }}">
                                            @foreach($permissible_values as $permissible_value)
                                                @php
                                                    if ($permissible_value[0] == $setting->value) {
                                                        $selected = ' selected';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                @endphp
                                                <option 
                                                    value="{{ $permissible_value[0] }}" 
                                                    {{ $selected }}>{{ $permissible_value[1] }}
                                                </option>
                                            @endforeach
                                        </select> --}}

                                        {{-- переделать --}}
                                        <input 
                                            type="checkbox"
                                            id="setting_{{ $setting->id }}"
                                            name="value" 
                                            @if ( $setting->value )
                                                {{-- value="0" --}}
                                                checked
                                            @else
                                                {{-- value="1" --}}
                                            @endif
                                        >
                                        <label class="" for="setting_{{ $setting->id }}">
                                            {{ $setting->description }}
                                        </label>

                                    @elseif ($setting->type == 'select')

                                        <select name="value" id="setting_{{ $setting->id }}" class="{{ $setting->value ? 'on_select' : 'off_select' }}">
                                            @foreach($permissible_values as $permissible_value)
                                                @php
                                                    if ($permissible_value[0] == $setting->value) {
                                                        $selected = ' selected';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                @endphp
                                                <option 
                                                    value="{{ $permissible_value[0] }}" 
                                                    {{ $selected }}>{{ $permissible_value[1] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                    @elseif($setting->type == 'email')

                                        @php
                                            $value = explode(', ', $setting->value);
                                            for ( $i = 1; $i <= config('mail.max_quantity_add_bcc'); $i++ ) {
                                                if ( count($value) >= $i ) {
                                                    // echo '<input type="email" name="value_email' . $i . '" value="' . ($value[($i - 1)] ?? '') . '">';
                                                    echo '<input type="email" name="value_email' . $i . '" value="' . ($value[($i - 1)] ? $value[($i - 1)] : '') . '">';
                                                } else {
                                                    echo '<input type="email" name="value_email' . $i . '" value="">';
                                                }
                                            }
                                        @endphp

                                    @endif

                                    <button type="submit"  class="btn btn-primary">применить</button>
                                </form>
                            </div>

                        @endif

                    @endforeach
                </div>
            @endforeach
        </div>
        {{-- /content --}}

    </div>

@endsection