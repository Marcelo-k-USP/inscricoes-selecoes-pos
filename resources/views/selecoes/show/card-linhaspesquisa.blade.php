@section('styles')
@parent
  <style>
    #card-linhaspesquisa {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_linhaspesquisa"></a>
<div class="card bg-light mb-3" id="card-linhaspesquisa">
  <div class="card-header">
    Linhas de Pesquisa
    <span class="badge badge-pill badge-primary">{{ is_null($selecao->linhaspesquisa) ? 0 : $selecao->linhaspesquisa->count() }}</span>
    @can('selecoes.update')
      @include('linhaspesquisa.partials.modal-add')
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionLinhasPesquisa">
      @if (!is_null($selecao->linhaspesquisa))
        @foreach ($selecao->linhaspesquisa as $linhapesquisa)
          <div class="card linhapesquisa-item">
            <div class="card-header" style="font-size:15px">
              @include('linhaspesquisa.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
