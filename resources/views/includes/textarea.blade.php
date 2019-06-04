<div class="form-group">
    <label for="{{ $name }}">{{ $name }}</label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        cols="{{ $cols ?? '30' }}"
        rows="{{ $rows ?? '3' }}"
        class="form-control"
        placeholder="{{ $name }}"
    >{{ $value }}</textarea>                       
</div>