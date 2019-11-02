

{{-- <div class="filter_block right_stylized_checkbox"> --}}
<div class="filter_block left_stylized_checkbox">
    <div class="filter_block_header">ПО ПРОИЗВОДИТЕЛЯМ</div>
    @if($manufacturers->count())

        @foreach($manufacturers as $manufacturer)
            <input 
                type="checkbox"
                id="filter_manufacturers_{{ $manufacturer->id }}"
                name="manufacturers[]" 
                value="{{ $manufacturer->id }}"
                
                @if ( !empty($appends['manufacturers']) and in_array($manufacturer->id, $appends['manufacturers']) )
                    checked
                @endif
            >
            <label class="filters" for="filter_manufacturers_{{ $manufacturer->id }}">
                {{ $manufacturer->title }}
            </label>

        @endforeach

    @endif
</div>
