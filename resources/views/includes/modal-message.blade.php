<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-secondary form-control" data-toggle="modal" data-target="#{{ $cssId }}">
    {{-- <i class="fas fa-eye"></i> --}} комментарий
</button>

<!-- Modal -->
<div class="modal fade" id="{{ $cssId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $cssId }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $cssId }}Label">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ta_l">
        {{ $message }}
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
  </div>
</div>