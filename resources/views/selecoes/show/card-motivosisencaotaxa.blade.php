@section('styles')
@parent
  <style>
    #card-motivosisencaotaxa {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_motivosisencaotaxa"></a>
<div class="card bg-light mb-3" id="card-motivosisencaotaxa">
  <div class="card-header">
    Motivos de Isenção de Taxa
    <span class="badge badge-pill badge-primary">{{ is_null($selecao->motivosisencaotaxa) ? 0 : $selecao->motivosisencaotaxa->count() }}</span>
    @can('selecoes.update')
      @if ($condicao_ativa)
        @include('motivosisencaotaxa.partials.modal-add')
      @endif
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionMotivosIsencaoTaxa">
      @if (!is_null($selecao->motivosisencaotaxa))
        @foreach ($selecao->motivosisencaotaxa as $motivoisencaotaxa)
          <div class="card motivoisencaotaxa-item">
            <div class="card-header" style="font-size:15px">
              @include('motivosisencaotaxa.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
