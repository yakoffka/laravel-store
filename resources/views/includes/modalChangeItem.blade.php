<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-{{ $class ?? 'primary' }}" data-toggle="modal" data-target="#{{ $cssId }}">
    {{ $qty }}
</button>

<!-- Modal -->
<div class="modal fade" id="{{ $cssId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $cssId }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $cssId }}Label">change quantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="describe"> {{ $product->name }}</div>
        <form method="POST" action="{{ route('cart.change-item', ['product' => $product->id]) }}" enctype="multipart/form-data">
          @csrf

          @method('PATCH')

          @input(['name' => 'quantity', 'type' => 'number', 'value' => $qty, 'min' => $min])          

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