@section('styles')
@parent
  <style>
    #card-selecao-solicitacoesisencaotaxa {
      border: 1px solid blue;
      border-top: 3px solid blue;
    }
  </style>
@endsection

<div class="card mb-3" id="card-selecao-solicitacoesisencaotaxa">
  <div class="card-header">
    <i class="fas fa-chart-line"></i> Solicitações de Isenção de Taxa
  </div>
  <div class="card-body">
    <ul class="list-unstyled">
      <li>Contagem (últimos 5 anos)
          @include('selecoes.partials.solicitacoesisencaotaxa-por-ano')
      </li>
    </ul>
  </div>
</div>
