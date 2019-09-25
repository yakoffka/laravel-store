{{-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> --}}
<div class="form-group">
    <label class="h3 blue" for="images">{{ $label }}</label><br>
    {!! $value ?? '' !!}
    <textarea name="{{ $name }}" class="form-control tinymce-editor">{!! $value ?? '' !!}</textarea>
</div>
