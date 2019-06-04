<div class="form-group">
    <label for="{{ $name }}">{{ $name }}</label>
    <input 
        type="{{ $type ?? 'text' }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        class="form-control" 
        placeholder="{{ $name }}" 
        value="{{ $value }}" 
        {{ $required ?? '' }}
    >
</div>
