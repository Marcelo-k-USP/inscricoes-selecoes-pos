@section('styles')
@parent
  <style>
    #card-niveislinhaspesquisa {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_niveislinhaspesquisa"></a>
<div class="card bg-light mb-3" id="card-niveislinhaspesquisa">
  <div class="card-header">
    Combinações Níveis com Linhas de Pesquisa/Temas
    <span class="badge badge-pill badge-primary">{{ is_null($selecao->niveislinhaspesquisa) ? 0 : $selecao->niveislinhaspesquisa->count() }}</span>
    @can('selecoes.update', $selecao)
      @include('niveislinhaspesquisa.partials.modal-add', ['inclusor_url' => 'selecoes', 'inclusor_objeto' => $selecao])
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionNiveisLinhasPesquisa">
      @if (!is_null($selecao->niveislinhaspesquisa))
        @foreach ($selecao->niveislinhaspesquisa as $nivellinhapesquisa)
          <div class="card nivellinhapesquisa-item">
            <div class="card-header" style="font-size:15px">
              @include('niveislinhaspesquisa.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
