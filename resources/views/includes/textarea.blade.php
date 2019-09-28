<div class="form-group">
    <label class="h3 blue" for="{{ $name }}">{{ $label ?? $title ?? $name }}</label><br>
    {!! $value !!} {{ !empty($required) ? '*' : '' }}
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        cols="{{ $cols ?? '30' }}"
        rows="{{ $rows ?? '3' }}"
        class="form-control"
        placeholder="{{ $label ?? $title ?? $name }}"
        {{ $required ?? '' }}
    >{{ $value }}</textarea>                       
</div>