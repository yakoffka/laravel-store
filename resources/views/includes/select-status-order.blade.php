<td>
    <!-- Button trigger modal -->
    <button
        type="button" 
        class="btn btn-{{ $order->status->style ?? 'primary' }} form-control" 
        data-toggle="modal" 
        data-target="#select_status_order_{{ $order->id }}"
    >
        {{ $order->status->name }}
    </button>

    <!-- Modal -->
    <div 
        class="modal fade" 
        id="select_status_order_{{ $order->id }}" 
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="select_status_order_{{ $order->id }}Label" 
        aria-hidden="true"
    >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="select_status_order_{{ $order->id }}Label">change order status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            {{-- <div class="describe">change order status</div> --}}
            <form 
                method="POST" 
                action="{{ route('orders.update', ['order' => $order->id]) }}" 
                enctype="multipart/form-data"
            >
                @csrf

                @method('PATCH')

                <select name="status_id" id="status_id">
                    <?php
                        foreach ( $statuses as $status ) {
                            if ( $order->status->id == $status->id ) {
                                echo '<option value="' . $status->id . '" selected>' . $status->name . '</option>';
                            } else {
                                echo '<option value="' . $status->id . '">' . $status->name . '</option>';
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
</td>
