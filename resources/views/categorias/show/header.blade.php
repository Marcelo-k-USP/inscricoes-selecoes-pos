<div class="d-flex">
  <b>
    {{ $categoria->nome }}
  </b>
  <div class="hidden-btn d-none ml-auto">
    @can('tiposarquivo.update', $tipoarquivo)
      @include('common.btn-delete-sm', ['action' => "tiposarquivo/{$tipoarquivo->id}/categorias/{$categoria->id}"])
    @endcan
  </div>
</div>

@once
@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(function() {
      $('.categoria-item').hover(
        function() {
          $(this).find('.hidden-btn').removeClass('d-none');
        },
        function() {
          $(this).find('.hidden-btn').addClass('d-none');
        }
      );
    });
  </script>
@endsection
@endonce
