{{-- 

    @lfmImageButton(['id' => 'lfm_category_' . $category->id, 'name' => 'imagepath', 'value' => old('imagepath') ?? $category->imagepath ?? ''])

--}}
<h2 class="mt-4">Standalone Image Button</h2>
<div class="input-group">
    <span class="input-group-btn">
        <a id="{{ $id }}" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
            <i class="far fa-image"></i> Выберите
        </a>
    </span>
    <input id="thumbnail" class="form-control" type="text" name="{{ $name }}" value="{{ $value }}">
</div>
<div id="holder" style="margin-top:15px;max-height:100px;"></div>


<script>
    var route_prefix = "{{ url(config('lfm.url_prefix')) }}";
</script>
{{-- <script>
    {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/stand-alone-button.js')) !!}
</script> --}}
<script src="{{ asset('vendor/lfm/js/stand-alone-button.js') }}"></script>

{{-- <script>
    $('#lfm').filemanager('image', {prefix: route_prefix});
    $('#lfm2').filemanager('file', {prefix: route_prefix});
</script> --}}
<script>
    $('#{{ $id }}').filemanager('image', {prefix: route_prefix});
</script>
<style>
    .popover {
        top: auto;
        left: auto;
    }
</style>
{{-- <script>
    $(document).ready(function(){
        // Define function to open filemanager window
        var lfm = function(options, cb) {
            var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };
    });
</script> --}}
{{-- change prefix --}}
<script>
    $(document).ready(function(){
        // Define function to open filemanager window
        var lfm = function(options, cb) {
            // var route_prefix = "{{ url(config('lfm.url_prefix')) }}";
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };
    });
</script>
