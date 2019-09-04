{{-- 

    @modalSelect([
        'id' => $task, // for 
        'options' => $tasksstatuses, 
        'item' => $task->getStatus, 
        'action' => route('tasks.update', ['task' => $task]),
        'select_name' => 'tasksstatus_id',
    ])

--}}


<!-- Button trigger modal -->
    {{-- <button type="button" class="btn btn-{{ $item->style ?? 'primary' }} form-control" data-toggle="modal" 
        data-target="#select_{{ $select_name }}_{{ $id }}">
        {{ $item->title }}
    </button> --}}
    {{-- <span class="pointer text-{{ $item->style ?? 'primary' }}" data-toggle="modal" data-target="#select_{{ $select_name }}_{{ $id }}"> --}}
    <span class="pointer {{ $item->class ?? '' }}" data-toggle="modal" data-target="#select_{{ $select_name }}_{{ $id }}">
        <i class="fas fa-pen-nib"></i>
        {{ $item->title }}
    </span>
<!-- Button trigger modal -->
<!-- Modal -->
    <div 
        class="modal fade" 
        id="select_{{ $select_name }}_{{ $id }}" 
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="select_{{ $select_name }}_{{ $id }}Label" 
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="select_{{ $select_name }}_{{ $id }}Label">
                    change item for #{{ $id }}
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
                    
                    {{-- <select name="status_id" id="status_id"> --}}
                    <select name="{{ $select_name }}" id="{{ $select_name }}_{{ $id }}">
                        <?php
                            // foreach ( $statuses as $status ) {
                            foreach ( $options as $option ) {
                                if ( $item->display_name == $option->display_name ) {
                                    echo '
                                    <option value="' . $option->id . '" selected>' . $option->display_name . '</option>';
                                } else {
                                    echo '
                                    <option value="' . $option->id . '">' . $option->display_name . '</option>';
                                }
                            }
                        ?>

                    </select>
                    <br><br>

                    <button type="submit" class="btn btn-primary form-control">change item!</button>

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
