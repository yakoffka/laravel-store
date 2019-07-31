
<div class="greygrey">manufacturer filter</div>
@if($manufacturers->count())
    <ul class="filter navbar-nav mr-auto">
        @foreach($manufacturers as $manufacturer)
            <p class="filters">
                {{ $manufacturer->title }}
                <input 
                    type="checkbox" 
                    name="manufacturers[]" 
                    value="{{ $manufacturer->id }}"
                    
                    @if ( !empty($appends['manufacturers']) and in_array($manufacturer->id, $appends['manufacturers']) )
                        checked
                    @endif

                >
            </p>
        @endforeach
    </ul>
@endif
