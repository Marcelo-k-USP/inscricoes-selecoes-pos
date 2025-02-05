<div class="d-flex">
  <b>
    {{ $nivel->nome }}
  </b>
  <div class="hidden-btn d-none ml-auto">
    @can($chamador_nome_plural . '.update', $chamador)
      @include('common.btn-delete-sm', ['action' => "{$chamador_nome_plural}/{$chamador->id}/niveis/{$nivel->id}"])
    @endcan
  </div>
</div>

@once
@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(function() {
      $('.nivel-item').hover(
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
