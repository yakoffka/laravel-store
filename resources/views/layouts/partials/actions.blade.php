                <table class="table blue_table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Тип</th>
                            <th>Дата</th>
                            <th>Описание</th>
                            @if ( Auth::user()->can('view_orders') )
                                <th>Исполнитель</th>
                            @endif
                            {{-- <th>Наличие</th> --}}
                        </tr>
                    </thead>
                    @foreach( $actions as $action )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $action->type }} {{ $action->action }}
                            </td>
                            <td>
                                {{-- {{ $action->created_at }} --}}
                                <span title="{{ $action->created_at }}">{{ substr($action->created_at, 0, 10) }}</span>
                            </td>
                            <td class="description">
                                {{-- {{ $action->description }} --}}
                                {{-- <span title="{{ $action->description }}">{{ str_limit($action->description, 50) }}</span> --}}
                                <span title="{{ $action->description }}">{{ $action->description }}</span>
                            </td>
                            @if ( Auth::user()->can('view_orders') )
                                <td>
                                    {{-- {{ $action->getInitiator->name }} --}}
                                    <a 
                                        href="{{ route('actions.user', $action->getInitiator) }}" 
                                        title="view all actions {{ $action->getInitiator->name }}"
                                    >
                                        {{ $action->getInitiator->name }}
                                    </a>
                                </td>
                            @endif
                    @endforeach
                    {{-- <tr>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        @if ( Auth::user()->can('view_orders') )
                            <td>...</td>
                        @endif
                    </tr> --}}
                </table>
