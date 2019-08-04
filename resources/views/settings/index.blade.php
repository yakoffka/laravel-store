@extends('layouts.app')

@php
    $title = 'Settings Store';
@endphp

@section('title', $title)

@section('content')
    
    <h1>{{ $title }}</h1>

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
                        <p class="card-text">
                            {{ $setting->description }}{{--  ({{ $setting->value }}) --}}
                        </p>
                        <form action="{{ route('settings.update', ['setting' => $setting->id]) }}" method="POST">
                            @csrf
                            @method("PATCH")

                            <select name="value" id="setting_{{ $setting->id }}">

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

                            <button type="submit"  class="btn btn-primary">применить</button>
                        </form>
                    </div>

                @endif

            @endforeach
        </div>
    @endforeach


@endsection