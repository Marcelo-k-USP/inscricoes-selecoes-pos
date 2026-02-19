{{-- Form de confirmar e-mail --}}
<form method="POST" action="localusers/adminconfirmaemail/{{ $localuser->id }}" style="display: inline;">
  @csrf
  @method('put')
  <button type="submit" class="btn btn-sm btn-light text-success" data-toggle="tooltip" title="Confirmar e-mail">
    <i class="far fa-check-circle"></i>
  </button>
</form>
