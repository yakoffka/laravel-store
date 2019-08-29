{{--

    @modalTextarea([
        'id' => $task,
        'textarea_name' => 'comment_slave'
        'textarea_display_name' => 'комментарий исполнителя'
        'value' => $task->comment_slave,
        'empty_value' => 'комментарий исполнителя отсутствует',
        'action' => route('tasks.update', ['task' => $task]),
    ])

--}}


<!-- Button trigger modal -->
    <span class="pointer" data-toggle="modal" data-target="#textarea_{{ $textarea_name }}_{{ $id }}">
        <i class="fas fa-pen-nib"></i>
        {{ $value ?? $empty_value }}
    </span>
<!-- Button trigger modal -->

<!-- Modal -->
    <div
        class="modal fade"
        id="textarea_{{ $textarea_name }}_{{ $id }}"
        tabindex="-1"
        role="dialog"
        aria-labelledby="textarea_{{ $textarea_name }}_{{ $id }}Label"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textarea_{{ $textarea_name }}_{{ $id }}Label">
                    Изменить {{ $textarea_display_name }} для задачи #{{ $id }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{-- <div class="describe">change item status</div> --}}

                <form class="ta_c" method="POST" action="{{ $action }}">

                    @csrf

                    @method('PATCH')

                    <textarea
                        id="{{ $textarea_name }}"
                        name="{{ $textarea_name }}"
                        cols="{{ $cols ?? '30' }}"
                        rows="{{ $rows ?? '3' }}"
                        class="form-control"
                        placeholder="{{ $textarea_display_name }}"
                        {{ $required ?? '' }}
                    >{{ $value ?? '' }}</textarea>
                    <br>

                    <button type="submit" class="btn btn-primary form-control">изменить</button>

                </form>

            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
            </div>
        </div>
    </div>
{{-- <!-- Modal --> --}}
