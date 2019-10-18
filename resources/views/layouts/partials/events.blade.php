
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Описание</th>
            @if ( Auth::user()->can('view_orders') )
                <th>Инициатор</th>
            @endif
        </tr>
    </thead>
    @foreach( $events as $event )
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                {{-- <span title="{{ $event->created_at }}">{{ substr($event->created_at, 0, 10) }}</span> --}}
                <span title="{{ $event->created_at }}">{{ $event->created_at }}</span>
            </td>
            <td>
                {{-- {{ $event->description }} --}}
                <span title="{{ $event->description }}">{{ str_limit($event->description, 50) }}</span>
            </td>
            @if ( Auth::user()->can('view_orders') )
                <td>
                    <a 
                        href="{{ route('events.index') }}?users[]={{ $event->getInitiator->id }}" 
                        title="view all events {{ $event->getInitiator->name }}"
                    >
                        {{ $event->getInitiator->name }}
                    </a>
                </td>
            @endif
    @endforeach
</table>