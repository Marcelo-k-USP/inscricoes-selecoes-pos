<button class="btn-remover-codpes btn btn-sm py-0" data-codpes="{{ $codpes }}">
  <i class="fas fa-trash text-danger"></i>
</button>
<input type="hidden" name="rem_codpes" value="0">

@once
@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $('.btn-remover-codpes').on('click', function() {
        if( confirm('Tem certeza?')) {
          $(':input[name=rem_codpes]').val($(this).data('codpes'))
        } else {
          return false
        }
      })

    })
  </script>
@endsection
@endonce
