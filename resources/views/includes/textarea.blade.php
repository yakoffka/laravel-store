<div class="form-group">
    <label for="{{ $name }}">{{ $title ?? $name }}</label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        cols="{{ $cols ?? '30' }}"
        rows="{{ $rows ?? '3' }}"
        class="form-control"
        placeholder="{{ $title ?? $name }}"
        {{ $required ?? '' }}
    >{{ $value }}</textarea>                       
</div>