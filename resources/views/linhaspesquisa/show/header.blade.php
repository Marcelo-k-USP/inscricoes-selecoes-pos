<div class="d-flex">
  <b>
    {{ $linhapesquisa->nome }}
  </b>
  <div class="hidden-btn d-none ml-auto">
    @includewhen(Gate::check('update', $selecao), 'common.btn-delete-sm', [
      'action' => "selecoes/{$selecao->id}/linhaspesquisa/{$linhapesquisa->id}",
    ])
  </div>
</div>

@once
@section('javascripts_bottom')
@parent
  <script>
    $(function() {
      $('.linhapesquisa-item').hover(
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
