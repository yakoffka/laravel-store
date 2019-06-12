{{-- 
  @modalConfirmDestroy([
    'btn_class' => 'btn btn-outline-danger del_btn',
    'cssId' => 'delele_',
    'item' => $role,
    'action' => route('roles.destroy', ['role' => $role->id]), 
  ]) 
--}}

<!-- Button trigger modal -->
<button type="button" class="{{ $btn_class }}" data-toggle="modal" data-target="#{{ $cssId }}_{{ $item->id }}">
    <i class="fas fa-trash"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="{{ $cssId }}_{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $cssId }}_{{ $item->id }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $cssId }}_{{ $item->id }}Label">подтверждение действия</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ta_l">
        <p>Запрошенная Вами операция требует подтверждения.</p>
        <p>Вы действительно хотите удалить {{ $name_item ?? $item->name ?? 'это' }}?</p>

      </div>
      <div class="modal-footer">

        <!-- form delete item -->
        <form action="{{ $action }}" method="POST" style="width: 100%">
            @csrf

            @method("DELETE")

            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">отмена</button>
            <button type="submit" class="btn btn-outline-danger">удалить</button>

        </form>

        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>