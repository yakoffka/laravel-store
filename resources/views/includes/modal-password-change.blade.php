{{--
  @modalConfirmDestroy([
    'btn_class' => 'btn btn-outline-danger del_btn',
    'cssId' => 'delele_',
    'item' => $role,
    'action' => route('roles.destroy', ['role' => $role->id]),
  ])
--}}

<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-danger" title="{{ __('change_passw_title') }}"
  data-toggle="modal" data-target="#change_password__{{ auth()->user()->id }}">
  {{ __('change_passw_title')}}
</button>

<!-- Modal -->
<div class="modal fade" id="change_password__{{ auth()->user()->id }}" tabindex="-1" role="dialog" aria-labelledby="change_password__{{ auth()->user()->id }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="change_password__{{ auth()->user()->id }}Label">{{ auth()->user()->name }}: Смена пароля</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <div class="modal-body ta_l"></div> --}}
      <div class="modal-footer">

        <!-- form delete item -->
        <form class="ta_c" 
          action="{{ route('users.password-change', ['user' => auth()->user()->id]) }}" method="POST" style="width: 100%">

            @csrf

            @method("PATCH")

            <div class="form-group row">
                <label for="new_password" class="col-md-6 col-form-label text-md-right">{{ __('newPassword') }}*</label>

                <div class="col-md-6">
                    <input id="new_password" type="password" class="form-control @error('password') is-invalid @enderror" name="new_password" required autocomplete="new-password">
                </div>
            </div>

            <div class="form-group row">
                <label for="new_password-confirm" class="col-md-6 col-form-label text-md-right">{{ __('ConfirmNewPassword') }}*</label>

                <div class="col-md-6">
                    <input id="new_password-confirm" type="password" class="form-control" name="new_password_confirmation" required autocomplete="new-password">
                </div>
            </div>



            <div class="form-group row">
              {{ __('Note: the new password must consist of at least 8 characters and contain at least one digit, one uppercase and one lowercase Latin letters') }}
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-6 col-form-label text-md-right">{{ __('inp_active_Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                </div>
            </div>


            {{-- <div class="form-group row"> --}}
              <button type="button" class="btn btn-outline-primary form-control col-sm-5" data-dismiss="modal">отмена</button>
              <button type="submit" class="btn btn-outline-danger form-control col-sm-5">{{ __('change_passw_title')}}</button>
            {{-- </div> --}}

        </form>

        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>