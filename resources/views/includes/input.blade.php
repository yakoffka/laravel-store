<div class="form-group">
    <label for="{{ $name }}">{{ $label ?? $name }}</label>
    <input 
        type="{{ $type ?? 'text' }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        class="form-control" 
        placeholder="{{ $name }}" 
        value="{{ $value }}" 
        {{ $required ?? '' }}
        {{-- {{ $min ? 'min="' . $min . '"' : '' }}{{ $max ? 'max="' . $max . '"' : '' }} --}}
    >
</div>
