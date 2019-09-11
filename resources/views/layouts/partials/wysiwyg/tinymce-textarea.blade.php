{{-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> --}}
<div class="form-group">
    <label for="images">{{ $label }}</label><br>
    <textarea name="{{ $name }}" class="form-control tinymce-editor">{!! $value ?? '' !!}</textarea>
</div>
