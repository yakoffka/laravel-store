
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Описание</th>
            @if ( auth()->user()->can('view_orders') )
                <th>Инициатор</th>
            @endif
        </tr>
    </thead>
    @foreach( $customevents as $customevent )
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                {{-- <span title="{{ $customevent->created_at }}">{{ substr($customevent->created_at, 0, 10) }}</span> --}}
                <span title="{{ $customevent->created_at }}">{{ $customevent->created_at }}</span>
            </td>
            <td>
                {{-- {{ $customevent->description }} --}}
                <span title="{{ $customevent->description }}">{{ str_limit($customevent->description, 50) }}</span>
            </td>
            @if ( auth()->user()->can('view_orders') )
                <td>
                    <a
                        href="{{ route('customevents.index') }}?users[]={{ $customevent->getInitiator->id }}"
                        title="view all events {{ $customevent->getInitiator->name }}"
                    >
                        {{ $customevent->getInitiator->name }}
                    </a>
                </td>
            @endif
    @endforeach
</table>
