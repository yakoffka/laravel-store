<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-{{ $class ?? 'primary' }}" data-toggle="modal" data-target="#{{ 'change_' . $img->id }}">
  {{ $img->sort_order }} <i class="fas fa-pen-nib"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="{{ 'change_' . $img->id }}" tabindex="-1" role="dialog" aria-labelledby="{{ 'change_' . $img->id }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ 'change_' . $img->id }}Label">change sorting</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="describe"> {{ $title }}</div>
        <form method="POST" action="{{ route('images.update', ['image' => $img->id]) }}">
          @csrf

          @method('PATCH')

          @input([
            'name' => 'sort_order', 
            'label' => 'Change sort order for image "' . $img->name . '"', 
            'type' => 'number', 
            'value' => $img->sort_order,
          ])          

          <button type="submit" class="btn btn-primary form-control">change order!</button>

        </form>

      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
  </div>
</div>