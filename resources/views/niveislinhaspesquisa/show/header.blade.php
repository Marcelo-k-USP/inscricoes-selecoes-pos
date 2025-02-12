<div class="d-flex">
  <b>
    {{ $nivellinhapesquisa->nivel->nome }} em {{ $nivellinhapesquisa->linhapesquisa->nome }}
  </b>
  <div class="hidden-btn d-none ml-auto">
    @can('selecoes.update', $selecao)
      @include('common.btn-delete-sm', ['action' => "selecoes/{$selecao->id}/niveislinhaspesquisa/{$nivellinhapesquisa->id}"])
    @endcan
  </div>
</div>

@once
@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(function() {
      $('.nivellinhapesquisa-item').hover(
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
