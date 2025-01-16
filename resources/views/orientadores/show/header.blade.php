<div class="d-flex">
  <b>
    {{ $orientador->nome }}
  </b>
  <div class="hidden-btn d-none ml-auto">
    @can('linhaspesquisa.update')
      @include('common.btn-delete-sm', ['action' => "linhaspesquisa/{$linhapesquisa->id}/orientadores/{$orientador->id}"])
    @endcan
  </div>
</div>

@once
@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(function() {
      $('.orientador-item').hover(
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
