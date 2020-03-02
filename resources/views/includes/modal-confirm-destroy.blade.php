{{--
  @modalConfirmDestroy([
    'btn_class' => 'btn btn-outline-danger del_btn',
    'cssId' => 'delete_',
    'item' => $role,
    'action' => route('roles.destroy', ['role' => $role->id]),
  ])
--}}

<!-- Button trigger modal -->
<button type="button" class="{{ $btn_class }}" title="{{ __('delete_action') }}"
  data-toggle="modal" data-target="#{{ $cssId }}_{{ $item->id }}">
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
        <p>
          Вы действительно желаете удалить '{{ $name_item ?? $item->name ?? $item->title ?? '' }}'?
        </p>

      </div>
      <div class="modal-footer">

        <!-- form delete item -->
        <form action="{{ $action }}" method="POST" style="width: 100%">

            @csrf

            @method("DELETE")

            {{-- <div class="form-group row"> --}}
              <button type="button" class="btn btn-outline-primary form-control col-sm-5" data-dismiss="modal">отмена</button>
              <button type="submit" class="btn btn-outline-danger form-control col-sm-5">удалить</button>
            {{-- </div> --}}

        </form>

        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>
