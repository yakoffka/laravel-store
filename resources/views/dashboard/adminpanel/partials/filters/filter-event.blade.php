@include('layouts.partials.separator')

<h4 class="ta_c">фильтры</h4>

<form class="formfilters" action="{{ route('events.index') }}">

    {{-- {{dd($models)}} --}}
    @if ( $models->count() )
        <div class="filter_block left_stylized_checkbox">
            <div class="filter_block_header">{{ __('models_filter') }}</div>
            @foreach ( $models as $model )
                <input type="checkbox" name="models[]" value="{{ $model }}"
                    id="filter_models_{{ $model }}"
                    @if ( !empty($appends['models']) and in_array($model, $appends['models']) )
                        checked
                    @endif
                >
                <label class="filters main_category" 
                    for="filter_models_{{ $model }}"
                    title="{{ $model }}"
                >
                    {{ __($model) }}
                </label>
            @endforeach
        </div>
    @else
    @endif

    {{-- {{dd($types)}} --}}
    @if ( $types->count() )
        <div class="filter_block left_stylized_checkbox">
            <div class="filter_block_header">{{ __('types_filter') }}</div>
            @foreach ( $types as $type )
                <input type="checkbox" name="types[]" value="{{ $type }}"
                    id="filter_types_{{ $type }}"
                    @if ( !empty($appends['types']) and in_array($type, $appends['types']) )
                        checked
                    @endif
                >
                <label class="filters main_category" 
                    for="filter_types_{{ $type }}"
                    title="{{ $type }}"
                >
                    {{ __($type) }}
                </label>
            @endforeach
        </div>
    @else
    @endif

    {{-- {{dd($users)}} --}}
    @if ( $users->count() )
        <div class="filter_block left_stylized_checkbox">
            <div class="filter_block_header">{{ __('users_filter') }}</div>
            @foreach ( $users as $user_id => $user_name )
                <input type="checkbox" name="users[]" value="{{ $user_id }}"
                    id="filter_users_{{ $user_id }}"
                    @if ( !empty($appends['users']) and in_array($user_id, $appends['users']) )
                        checked
                    @endif
                >
                <label class="filters main_category" 
                    for="filter_users_{{ $user_id }}"
                    title="{{ $user_name }}"
                >
                    {{ $user_name }}
                </label>
            @endforeach
        </div>
    @else
    @endif


    <button type="submit" class="btn btn-outline-success form-control">
        <i class="fas fa-filter"></i> filter out
    </button>

</form>

@if ( !empty($appends['models']) or !empty($appends['types']) or !empty($appends['users']) )
<form class="formfilters" action="{{ route('events.index') }}">
    <button type="submit" class="btn btn-outline-danger form-control">
        <i class="fas fa-eraser"></i> cleare filter
    </button>
</form>
@endif
