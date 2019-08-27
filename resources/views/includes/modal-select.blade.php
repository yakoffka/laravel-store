{{-- 

    @modalSelect([
        'options' => array_column(config('task.statuses'), 'name'), 
        'item' => $task, 
        'action' => route('tasks.update', ['task' => $task]),
        'select_name' => 'status',
    ])

--}}


{{-- <!-- Button trigger modal --> --}}
    <button type="button" class="btn btn-{{ $btn_class ?? 'primary' }} form-control" data-toggle="modal" 
        data-target="#select_{{ $select_name }}_item_{{ $item->id }}">
        {{ $item->{$select_name}->name ?? $item->{$select_name} }}
    </button>
{{-- <!-- Button trigger modal --> --}}


{{-- <!-- Modal --> --}}
    <div 
        class="modal fade" 
        id="select_{{ $select_name }}_item_{{ $item->id }}" 
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="select_{{ $select_name }}_item_{{ $item->id }}Label" 
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="select_{{ $select_name }}_item_{{ $item->id }}Label">
                    change item {{ $select_name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <div class="describe">change item status</div> --}}
                
                <!-- form_{{ $select_name }} -->
                <form 
                    method="POST"
                    {{-- action="{{ route('items.update', ['item' => $item->id]) }}"  --}}
                    action="{{ $action }}" 
                >
                    @csrf

                    @method('PATCH')
                    
                    {{-- <select name="status_id" id="status_id"> --}}
                    <select name="{{ $select_name }}" id="{{ $select_name }}_{{ $item->id }}">

                        <?php
                            // foreach ( $statuses as $status ) {
                            foreach ( $options as $option ) {

                                // depricated
                                // if( !empty($item->status->id) ) {
                                //     $value = $item->status->id;
                                //     $status_value = $status->id;
                                //     $status_name = $status->name;
                                // } else {
                                //     $value = $item->status;
                                //     $status_value = $status;
                                //     $status_name = $status;
                                // }



                                if ( $item->{$select_name} == $option ) {
                                    echo '<option value="' . $option . '" selected>' . $option . '</option>';
                                } else {
                                    echo '<option value="' . $option . '">' . $option . '</option>';
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
